<?php
function theme_enqueue_scripts() {
	wp_enqueue_script('my-ajax-pagination', get_template_directory_uri() . '/assets/js/ajax-pagination-switch-view.js', array('jquery'), ('plugins'), null, true);
	
	// Получаем минимальную и максимальную цену из каталога товаров
	global $wpdb;
	$min_price = $wpdb->get_var("SELECT MIN(meta_value+0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'");
	$max_price = $wpdb->get_var("SELECT MAX(meta_value+0) FROM {$wpdb->postmeta} WHERE meta_key = '_price'");
	
	wp_localize_script('my-ajax-pagination', 'my_ajax_object', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'min_price' => $min_price,
		'max_price' => $max_price
	));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

add_action('wp_ajax_load_products', 'load_products_callback');
add_action('wp_ajax_nopriv_load_products', 'load_products_callback');

function load_products_callback() {
	$paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$view_type = $_POST['view_type'] ?? 'grid';
	$orderby = $_POST['orderby'] ?? 'date';
	
	parse_str($_POST['form_data'], $form_data);
	
	// Фильтры
	$price_min = isset($form_data['min_price']) ? floatval($form_data['min_price']) : 0;
	$price_max = isset($form_data['max_price']) ? floatval($form_data['max_price']) : PHP_INT_MAX;
	$categories = isset($form_data['categories']) ? array_map('intval', (array)$form_data['categories']) : [];
	$tags = isset($form_data['tags']) ? array_map('intval', (array)$form_data['tags']) : [];
	$attributes = isset($form_data['attributes']) ? $form_data['attributes'] : [];
	$on_sale = isset($form_data['on_sale']) && $form_data['on_sale'] === '1';
	$in_stock = isset($form_data['in_stock']) && $form_data['in_stock'] === '1';
	
	$args = array(
		'post_type'      => 'product',
		'paged'          => $paged,
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	);
	
	// Поддержка сортировки WooCommerce
	switch ($orderby) {
		
		case 'popularity':
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;
		case 'price':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
			break;
		case 'price-desc':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;
		case 'date':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;
		default:
			$args['orderby'] = 'menu_order';
			$args['order']   = 'DESC';
			break;
	}
	
	// Мета-запросы (цена, распродажа, наличие)
	$meta_query = array();
	
	if ($price_min || $price_max !== PHP_INT_MAX) {
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => array($price_min, $price_max),
			'compare' => 'BETWEEN',
			'type'    => 'NUMERIC'
		);
	}
	
	if ($on_sale) {
		$meta_query[] = array(
			'key'     => '_sale_price',
			'value'   => 0,
			'compare' => '>',
			'type'    => 'NUMERIC'
		);
	}
	
	if ($in_stock) {
		$meta_query[] = array(
			'key'     => '_stock_status',
			'value'   => 'instock',
			'compare' => '='
		);
	}
	
	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}
	
	$tax_query = array('relation' => 'AND');
	
	// Текущий термин страницы (например, атрибут), для архивной страницы
	if (!empty($form_data['current_taxonomy']) && !empty($form_data['current_term'])) {
		$tax_query[] = array(
			'taxonomy' => sanitize_text_field($form_data['current_taxonomy']),
			'field'    => 'slug',
			'terms'    => sanitize_text_field($form_data['current_term']),
		);
	}
	
	// Категории
	if (!empty($categories)) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $categories
		);
	}
	
	// Метки
	if (!empty($tags)) {
		$tax_query[] = array(
			'taxonomy' => 'product_tag',
			'field'    => 'term_id',
			'terms'    => $tags
		);
	}
	
	// Атрибуты
	if (!empty($attributes)) {
		foreach ($attributes as $taxonomy => $terms) {
			$tax_query[] = array(
				'taxonomy' => sanitize_text_field($taxonomy),
				'field'    => 'term_id',
				'terms'    => array_map('intval', (array)$terms)
			);
		}
	}
	
	if (count($tax_query) > 1) {
		$args['tax_query'] = $tax_query;
	}
//	if (!empty($tax_query)) {
//		$args['tax_query'] = $tax_query;
//	}
	
	$query = new WP_Query($args);
	ob_start();
	if ($query->have_posts()) { ?>
	

			<div class="row">
				<?php
				while ($query->have_posts()) {
					$query->the_post();
					
					global $product;
					$product = wc_get_product(get_the_ID()); //явно создаем объект $product
					
					if ($view_type === 'list') {
						get_template_part('template-parts/products/tab', 'curriculum');
					} else {
						get_template_part('template-parts/products/tab', 'course');
					}
				}
				wp_reset_postdata();
				?>
			</div>

		<!-- Пагинация -->
		<div class="page-nav-wrap">
			<ul>
				<?php echo paginate_links(array(
					'base'      => '#',
					'format'    => '',
					'current'   => $paged,
					'total'     => $query->max_num_pages,
					'prev_text' => '<i class="fa-solid fa-arrow-left-long"></i>',
					'next_text' => '<i class="fa-solid fa-arrow-right-long"></i>',
					'type'      => 'list',
				)); ?>
			</ul>
		</div>
		
		<?php
//		echo ob_get_clean();
	} else {
		echo '<p>Товары не найдены.</p>';
	}
	
	$products_html = ob_get_clean();

// Подсчет найденных товаров
	$total_products = $query->found_posts;

// Сбор текста "Показано X из Y" вручную
	$count_html = sprintf(
		__('Показано %1$d товаров из %2$d', 'woocommerce'),
		$query->post_count,
		$total_products
	);
	
	wp_send_json_success([
		'html' => $products_html,
		'count_html' => '<p class="woocommerce-result-count">' . $count_html . '</p>',
	]);
	
	
	wp_die();
}
