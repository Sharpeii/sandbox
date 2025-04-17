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
						<a href="<?php echo esc_url(wc_get_cart_url()); ?>"><i class="fa-regular fa-cart-shopping"></i></a>
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
				if (!empty($attributes)) {
					$output = [];
					foreach ($attributes as $attribute) {
						$name = wc_attribute_label($attribute->get_name());
						if ($attribute->is_taxonomy()) {
							$terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
							$value = implode(', ', $terms);
						} else {
							$value = implode(', ', array_map('trim', explode('|', $attribute->get_options())));
						}
						$output[] = $value;
					}
					echo '<p>' . implode(', ', $output) . '</p>';
				}
				?>
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
				<h6>$102.00 <del>$226.00</del></h6>
				<p>
					Auctor urna nunc id cursus. Scelerisque purus semper eget duis at pharetra vel turpis nunc eget.
				</p>
				<a href="shop-cart.html" class="theme-btn">Add To Cart</a>
			</div>
		
		
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
