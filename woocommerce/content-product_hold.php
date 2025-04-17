<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>

<?php
// Получаем текущий вид из localStorage через AJAX
$view = isset($_COOKIE['productView']) ? sanitize_text_field($_COOKIE['productView']) : 'grid';
?>

<div class="tab-content">
	<!-- Вид плиткой -->
	<div id="Course" class="tab-pane fade show active">
		<div class="row">
			<?php while (have_posts()) : the_post(); ?>
				<div class="col-xl-3 col-lg-6 col-md-6">
					<div class="product-details-item">
						<div class="shop-image">
							<a href="<?php the_permalink(); ?>">
								<?php woocommerce_template_loop_product_thumbnail(); ?>
							</a>
						</div>
						<div class="content">
							<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							<?php woocommerce_template_loop_price(); ?>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>

	<!-- Вид списком -->
	<div id="Curriculum" class="tab-pane fade">
		<div class="row">
			<?php while (have_posts()) : the_post(); ?>
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
</div>



<!--<div --><?php //wc_product_class( 'col-xl-3 col-lg-6 col-md-6', $product ); ?><!-->-->
<!--	<div class="product-details-item">-->
<!--		<div class="shop-image">-->
<!--			--><?php //echo $product->get_image()?>
<!--			-->
<!--			<ul class="shop-icon d-grid justify-content-center align-items-center">-->
<!--				<li>-->
<!--					<a href="--><?php //echo $product->add_to_cart_url()?><!--"><i class="fa-regular fa-cart-shopping"></i></a>-->
<!--				</li>-->
<!--				<li>-->
<!--					<button data-bs-toggle="modal" data-bs-target="#exampleModal2">-->
<!--						<i class="fa-regular fa-eye"></i>-->
<!--					</button>-->
<!--				</li>-->
<!--				<li>-->
<!--					<a href="shop-cart.html"><i class="far fa-heart"></i></a>-->
<!--				</li>-->
<!--			</ul>-->
<!--		</div>-->
<!--		<div class="content">-->
<!--			--><?php //$attributes = $product->get_attributes();?>
<!--			--><?php
//			if (!empty($attributes)) {
//			$output = [];
//				foreach ($attributes as $attribute) {
//				 // Получаем имя атрибута
//            $name = wc_attribute_label($attribute->get_name());
//
//            // Получаем все значения атрибута
//            if ($attribute->is_taxonomy()) {
//                $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
//                $value = implode(', ', $terms);
//            } else {
//                $value = implode(', ', array_map('trim', explode('|', $attribute->get_options())));
//            }
//
//            // Формируем строку "Название атрибута: значение"
//            $output[] =$value;
//
//				}
//				// Выводим строку со всеми атрибутами
//				echo '<p>' . implode(', ', $output) . '</p>';
//			}?>
<!--			-->
<!--			<h4>-->
<!--				<a href="--><?php //echo $product->get_permalink()?><!--">--><?php //echo $product->get_title()?><!--</a>-->
<!--			</h4>-->
<!--			<div class="star">-->
<!--				<i class="fa-solid fa-star"></i>-->
<!--				<i class="fa-solid fa-star"></i>-->
<!--				<i class="fa-solid fa-star"></i>-->
<!--				<i class="fa-solid fa-star"></i>-->
<!--				<i class="fa-regular fa-star"></i>-->
<!--			</div>-->
<!--			<h6>--><?php //echo $product->get_price_html()?><!--</h6>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
<!---->
<!---->
<!--	<div --><?php //wc_product_class( 'product-details-item style-2', $product ); ?><!-->-->
<!--	<div class="shop-image">-->
<!--		--><?php //echo $product->get_image()?>
<!--		<ul class="shop-icon d-grid justify-content-center align-items-center">-->
<!--			<li>-->
<!--				<a href="--><?php //echo $product->add_to_cart_url()?><!--"><i class="fa-regular fa-cart-shopping"></i></a>-->
<!--			</li>-->
<!--			<li>-->
<!--				<button data-bs-toggle="modal" data-bs-target="#exampleModal2">-->
<!--					<i class="fa-regular fa-eye"></i>-->
<!--				</button>-->
<!--			</li>-->
<!--			<li>-->
<!--				<a href="shop-cart.html"><i class="far fa-heart"></i></a>-->
<!--			</li>-->
<!--		</ul>-->
<!--	</div>-->
<!--	<div class="content">-->
<!--		--><?php
//			if (!empty($attributes)) {
//				echo '<p>' . implode(', ', $output) . '</p>';
//			}?>
<!--		<h3>-->
<!--			<a href="--><?php //echo $product->get_permalink()?><!--">--><?php //echo $product->get_title()?><!--</a>-->
<!--		</h3>-->
<!--		<div class="star">-->
<!--			<i class="fa-solid fa-star"></i>-->
<!--			<i class="fa-solid fa-star"></i>-->
<!--			<i class="fa-solid fa-star"></i>-->
<!--			<i class="fa-solid fa-star"></i>-->
<!--			<i class="fa-regular fa-star"></i>-->
<!--		</div>-->
<!--		<h6>--><?php //echo $product->get_price_html()?><!--</h6>-->
<!--		<p>-->
<!--			--><?php //echo $product->get_short_description()?>
<!--		</p>-->
<!--		<a href="--><?php //echo $product->add_to_cart_url()?><!--" class="theme-btn">--><?php //echo $product->add_to_cart_text()?><!--</a>-->
<!--	</div>-->
<!--</div>-->

<!-- Modal Version 2 -->
<div class="modal modal-common-wrap fade" id="exampleModal2" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="shop-details-wrapper">
					<div class="row">
						<div class="col-lg-6">
							<div class="shop-details-image">
								<div class="tab-content">
									<div class="shop-thumb">
										<img src="assets/img/shop/popup.jpg" alt="img">
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="product-details-content">
								<h3 class="pb-3">Sulwhasoo Essential Cream</h3>
								<div class="star pb-3">
									<a href="#"> <i class="fas fa-star"></i></a>
									<a href="#"><i class="fas fa-star"></i></a>
									<a href="#"> <i class="fas fa-star"></i></a>
									<a href="#"><i class="fas fa-star"></i></a>
									<a href="#"><i class="fas fa-star"></i></a>
									<span>(25 Customer Review)</span>
								</div>
								<p class="mb-3">
									In today’s online world, a brand’s success lies in combining
									technological planning and social strategies to draw
									customers in–and keep them coming back
								</p>
								<div class="price-list">
									<h3><?php echo $product->get_price_html()?></h3>
								</div>
								<div class="cart-wrp">
									<div class="cart-quantity">
										<form id='myform' method='POST' class='quantity' action='#'>
											<input type='button' value='-' class='qtyminus minus'>
											<input type='text' name='quantity' value='0' class='qty'>
											<input type='button' value='+' class='qtyplus plus'>
										</form>
									</div>
									<a href="product-details.html" class="icon">
										<i class="far fa-heart"></i>
									</a>
									<div class="social-profile">
										<span class="plus-btn"><i class="far fa-share"></i></span>
										<ul>
											<li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
											<li><a href="#"><i class="fab fa-twitter"></i></a></li>
											<li><a href="#"><i class="fab fa-youtube"></i></a></li>
											<li><a href="#"><i class="fab fa-instagram"></i></a></li>
										</ul>
									</div>
								</div>
								<div class="shop-btn">
									<a href="shop-cart.html" class="theme-btn">
										<span> Add to cart</span>
									</a>
									<a href="product-details.html" class="theme-btn">
										<span> Buy now</span>
									</a>
								</div>
								<h6 class="details-info"><span>SKU:</span> <a href="product-details.html">124224</a></h6>
								<h6 class="details-info"><span>Categories:</span> <a href="product-details.html">Crux Indoor Fast and Easy</a></h6>
								<h6 class="details-info style-2"><span>Tags:</span> <a href="product-details.html"> <b>accessories</b> <b>business</b></a></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
