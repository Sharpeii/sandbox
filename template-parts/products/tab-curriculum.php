<?php
/**
 * Шаблон карточки товара в виде списка (Curriculum)
 * Вызывается через get_template_part('template-parts/products/tab', 'curriculum')
 */

global $product;
?>
<div class="col-xl-12">
	<div class="product-details-item style-2">
		<div class="shop-image">
			<a href="<?php the_permalink(); ?>">
				<?php woocommerce_template_loop_product_thumbnail(); ?>
			</a>
			<ul class="shop-icon d-grid justify-content-center align-items-center">
				<li>
					<a href="<?php echo esc_url($product->add_to_cart_url()); ?>"><i
								class="fa-regular fa-cart-shopping"></i></a>
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
			<?php
			$attributes = $product->get_attributes();
			
			if ( !empty($attributes)) {
				$output = [];
				
				foreach ($attributes as $attribute) {
					// Получаем название атрибута, например "Цвет"
					$label = wc_attribute_label($attribute->get_name());
					
					if ($attribute->is_taxonomy()) {
						// Если атрибут таксономический — получаем термины
						$terms = wc_get_product_terms($product->get_id(), $attribute->get_name());
						
						$term_links = [];
						
						foreach ($terms as $term) {
							// Получаем ссылку на архивную страницу термина
							$term_link = get_term_link($term);
							if ( !is_wp_error($term_link)) {
								$term_links[] = '<a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a>';
							}
						}
						
						// Добавляем строку вида: Цвет: Красный, Синий
						if ( !empty($term_links)) {
							$output[] = '<strong>' . esc_html($label) . ':</strong> ' . implode(', ', $term_links);
						}
						
					} else {
						// Если это обычный текстовый атрибут
						$values = implode(', ', array_map('trim', explode('|', $attribute->get_options())));
						$output[] = '<strong>' . esc_html($label) . ':</strong> ' . esc_html($values);
					}
				}
				
				// Выводим
				echo '<p class="product-attributes">' . implode('<br>', $output) . '</p>';
			}
			?>


			<!--				--><?php
			//				//Второй вариант без названия категорий и ссылок на них
			//				$attributes = $product->get_attributes();
			//				if (!empty($attributes)) {
			//					$output = [];
			//					foreach ($attributes as $attribute) {
			//						$name = wc_attribute_label($attribute->get_name());
			//						if ($attribute->is_taxonomy()) {
			//							$terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
			//							$value = implode(', ', $terms);
			//						} else {
			//							$value = implode(', ', array_map('trim', explode('|', $attribute->get_options())));
			//						}
			//						$output[] = $value;
			//					}
			//					echo '<p>' . implode(', ', $output) . '</p>';
			//				}
			//				?>
			<h3>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<div class="star">
				<i class="fa-solid fa-star"></i>
				<i class="fa-solid fa-star"></i>
				<i class="fa-solid fa-star"></i>
				<i class="fa-solid fa-star"></i>
				<i class="fa-regular fa-star"></i>
			</div>
			<h6><?php woocommerce_template_loop_price(); ?></h6>
			<p>
				<?php echo wp_trim_words(get_the_excerpt(), 15); ?>
			</p>
			<a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="theme-btn">Добавить в корзину</a>
		</div>
	</div>
</div>
