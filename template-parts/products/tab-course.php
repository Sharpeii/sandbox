<?php
/**
 * Шаблон карточки товара в виде плитки (Course)
 * Вызывается через get_template_part('template-parts/products/tab', 'course')
 */

global $product;
?>
<div class="col-xl-3 col-lg-6 col-md-6">
	<div class="product-details-item">
		<div class="shop-image">
			<?php woocommerce_template_loop_product_thumbnail(); ?>
			<ul class="shop-icon d-grid justify-content-center align-items-center">
				<li><a href="<?php echo esc_url($product->add_to_cart_url()); ?>"><i class="fa-regular fa-cart-shopping"></i></a></li>
				<li><button data-bs-toggle="modal" data-bs-target="#exampleModal2"><i class="fa-regular fa-eye"></i></button></li>
				<li><a href="shop-cart.html"><i class="far fa-heart"></i></a></li>
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
			<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<?php woocommerce_template_loop_price(); ?>
		</div>
	</div>
</div>
