<?php

//Определение минимальной и максимальной цены товаров
function get_min_max_price(): array
{
    global $wpdb;

    $min_price = $wpdb->get_var("SELECT MIN(meta_value+0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'");
    $max_price = $wpdb->get_var("SELECT MAX(meta_value+0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'");

    return array(
        'min_price' => $min_price ?: 0,
        'max_price' => $max_price ?: 100,
    );
}



// Локализация скрипта с начальными значениями
function enqueue_filter_assets(): void
{
    if (is_shop() || is_product_category()) {
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('filter-js', get_template_directory_uri() . '/assets/js/filter.js', array('jquery', 'jquery-ui-slider'), null, true);

// Получаем реальные значения минимальной и максимальной цены
        $price_range = get_min_max_price();

        wp_localize_script('filter-js', 'filterData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'min_price' => (float) $price_range['min_price'],  // Используем реальное значение минимальной цены
            'max_price' => (float) $price_range['max_price'],  // Используем реальное значение максимальной цены
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_filter_assets');

//AJAX-функция для фильтрации
  function filter_products_ajax()
  {
      // Проверяем, что form_data передан и это массив
      if (!isset($_POST['form_data']) || !is_array($_POST['form_data'])) {
          wp_send_json(['error' => 'Неверные данные формы']);
          wp_die();
      }

      // Преобразуем form_data в ассоциативный массив для удобства
      $form_data = [];
      foreach ($_POST['form_data'] as $item) {
          // Проверяем, если это массив с атрибутами или категориями
          if (strpos($item['name'], 'categories') === 0) {
              $form_data['categories'][] = $item['value'];
          } elseif (strpos($item['name'], 'attributes') === 0) {
              // Извлекаем название атрибута
              preg_match('/attributes\[(.*?)\]\[\]/', $item['name'], $matches);
              if (!empty($matches[1])) {
                  $taxonomy = $matches[1];
                  $form_data['attributes'][$taxonomy][] = $item['value'];
              }
          } else {
              $form_data[$item['name']] = $item['value'];
          }
      }

      // Устанавливаем номер страницы, передаваемый через AJAX
      $paged = isset($_POST['paged']) ? (int) $_POST['paged'] : 1;
      // Устанавливаем сортировку
      $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'menu_order';

      error_log('Orderby параметр: ' . $orderby); // Проверяем значение `orderby`

      $args = array(
          'post_type' => 'product',
          'posts_per_page' => 3,
          'paged' => $paged,
          'meta_query' => array(),
          'tax_query' => array('relation' => 'AND'),

      );

      // Добавление параметров сортировки
      if ($orderby === 'price') {
          $args['orderby'] = 'meta_value_num';
          $args['meta_key'] = '_price';
          $args['order'] = 'ASC';
      } elseif ($orderby === 'price-desc') {
          $args['orderby'] = 'meta_value_num';
          $args['meta_key'] = '_price';
          $args['order'] = 'DESC';
      } elseif ($orderby === 'popularity') {
          $args['orderby'] = 'meta_value_num';
          $args['meta_key'] = 'total_sales';
          $args['order'] = 'DESC';
      } elseif ($orderby === 'date') {
          $args['orderby'] = 'date';
          $args['order'] = 'DESC';
      } else {
          $args['orderby'] = 'menu_order';
          $args['order'] = 'ASC';
      }

      // Добавляем отладочный вывод в лог
      error_log(print_r($args, true));

      // Фильтр по диапазону цен
      if (isset($form_data['min_price']) && isset($form_data['max_price'])) {
          $min_price = (float) $form_data['min_price'];
          $max_price = (float) $form_data['max_price'];
          $args['meta_query'][] = array(
              'key' => '_price',
              'value' => array($min_price, $max_price),
              'compare' => 'BETWEEN',
              'type' => 'NUMERIC',
          );
      }

      // Фильтр по категориям
      if (!empty($form_data['categories'])) {
          $args['tax_query'][] = array(
              'taxonomy' => 'product_cat',
              'field' => 'term_id',
              'terms' => array_map('intval', $form_data['categories']), // Преобразуем значения в int
              'operator' => 'IN',
          );
      }

      // Фильтр по атрибутам
      if (!empty($form_data['attributes'])) {
          foreach ($form_data['attributes'] as $taxonomy => $terms) {
              $args['tax_query'][] = array(
                  'taxonomy' => $taxonomy,
                  'field' => 'term_id',
                  'terms' => array_map('intval', $terms), // Преобразуем значения в int
                  'operator' => 'IN',
              );
          }
      }

      // Запрос WooCommerce товаров
      $query = new WP_Query($args);

      $min_price = PHP_INT_MAX;
      $max_price = PHP_INT_MIN;
      $products_html = '';
      $pagination_html = '';

      if ($query->have_posts()) {?>
		  
		  <?php ob_start();?>
		<div id="Course" class="tab-pane fade show active">
			<div class="row">
				<?php while ($query->have_posts()) : $query->the_post();
				global $product;
				?>
					<div class="col-xl-3 col-lg-6 col-md-6">
						<div class="product-details-item">
							<div class="shop-image">
								
									<?php woocommerce_template_loop_product_thumbnail();?>
								
								<ul class="shop-icon d-grid justify-content-center align-items-center">
									<li>
										<a href="<?php echo $product->add_to_cart_url()?>"><i class="fa-regular
										fa-cart-shopping"></i></a>
									</li>
									<li>
										<button data-bs-toggle="modal" data-bs-target="#exampleModal2">
											<i class="fa-regular fa-eye"></i>
										</button>
									</li>
									<li>
										<a href="shop-cart.html"><i class="far fa-heart"></i></a>
									</li>
								</ul>
							</div>
							<div class="content">
											<?php $attributes = $product->get_attributes();?>
											<?php
											if (!empty($attributes)) {
											$output = [];
												foreach ($attributes as $attribute) {
												 // Получаем имя атрибута
								            $name = wc_attribute_label($attribute->get_name());
								
								            // Получаем все значения атрибута
								            if ($attribute->is_taxonomy()) {
								                $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
								                $value = implode(', ', $terms);
								            } else {
								                $value = implode(', ', array_map('trim', explode('|', $attribute->get_options())));
								            }
								
								            // Формируем строку "Название атрибута: значение"
								            $output[] =$value;
								
												}
												// Выводим строку со всеми атрибутами
												echo '<p>' . implode(', ', $output) . '</p>';
											}?>
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<?php woocommerce_template_loop_price(); ?>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

		
		<div id="Curriculum" class="tab-pane fade">
			<div class="row">
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<div class="col-xl-12">
						<div class="product-details-item style-2">
							<div class="shop-image">
								<a href="<?php the_permalink(); ?>">
									<?php woocommerce_template_loop_product_thumbnail(); ?>
								</a>
							</div>
							<div class="content">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php woocommerce_template_loop_price(); ?>
								<p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
								<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="theme-btn">Add To Cart</a>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
		  
		  <?php  $price = (float) get_post_meta(get_the_ID(), '_price', true);
		  if ($price) {
			  $min_price = min($min_price, $price);
			  $max_price = max($max_price, $price);
		  }?>
		  
		  <?php $products_html = ob_get_clean();?>
		  
		  
		  
		  
		  
		  
<!--         --><?php //ob_start();
//          woocommerce_product_loop_start();
//          while ($query->have_posts()) {
//              $query->the_post();
//              wc_get_template_part('content', 'product');
//
//              $price = (float) get_post_meta(get_the_ID(), '_price', true);
//              if ($price) {
//                  $min_price = min($min_price, $price);
//                  $max_price = max($max_price, $price);
//              }
//          }
//          woocommerce_product_loop_end();
//          $products_html = ob_get_clean();

          // Пагинация
          $pagination_html = paginate_links(array(
              'total' => $query->max_num_pages,
              'current' => $paged,
              'format' => '?paged=%#%',
              'type' => 'list', // Возвращаем HTML-список для рендера на фронтенде
              'prev_text' => __('« Previous', 'woocommerce'),
              'next_text' => __('Next »', 'woocommerce'),
          ));

      } else {
          $products_html = '<p>' . __('Товары не найдены', 'woocommerce') . '</p>';
          //$pagination_html = '';
      }

      // Устанавливаем значения в 0 и 100, если не нашли значений
      $min_price = $min_price == PHP_INT_MAX ? 0 : $min_price;
      $max_price = $max_price == PHP_INT_MIN ? 100 : $max_price;

// Отправляем JSON-ответ
      wp_send_json_success(array(
          'html' => $products_html,
          'pagination' => $pagination_html,
          'min_price' => $min_price,
          'max_price' => $max_price,

      ));

      wp_die();
  }
add_action('wp_ajax_filter_products', 'filter_products_ajax');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_ajax');
