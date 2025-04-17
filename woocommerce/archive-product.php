<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
//do_action( 'woocommerce_before_main_content' );

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
//do_action( 'woocommerce_shop_loop_header' );?>

	<!-- Offcanvas для фильтра товаров -->
<?php
$price_range = wc_get_products([
	'status' => 'publish',
	'limit'  => -1,
	'return' => 'ids',
]);

$prices = [];
foreach ($price_range as $product_id) {
	$product = wc_get_product($product_id);
	if ($product && $product->get_price() > 0) {
		$prices[] = floatval($product->get_price());
	}
}

$min_price = !empty($prices) ? floor(min($prices)) : 0;
$max_price = !empty($prices) ? ceil(max($prices)) : 1000;
?>

	<div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
		<div class="offcanvas-header">
			<h5 id="filterOffcanvasLabel"><?php _e('Фильтр товаров', 'woocommerce'); ?></h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<form id="product-filter-form">
				<!-- Диапазон цен -->
				<h6><?php _e('Цена', 'woocommerce'); ?></h6>
				<input type="hidden" id="price-range" data-min="<?php echo esc_attr($min_price); ?>" data-max="<?php echo esc_attr($max_price); ?>" readonly>
				<input type="hidden" name="min_price" id="min-price">
				<input type="hidden" name="max_price" id="max-price">
				<div id="price-slider-range"></div>
				<div class="price-slider-container">
					<p>от <span id="min-price-label"><?php echo $min_price; ?></span></p>
					<p>до <span id="max-price-label"><?php echo $max_price; ?></span></p>
				</div>

				<!-- Чекбоксы наличия и распродажи -->
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="on_sale" id="on-sale">
					<label class="form-check-label" for="on-sale">
						<?php _e('Только распродажа', 'woocommerce'); ?>
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="in_stock" id="in-stock">
					<label class="form-check-label" for="in-stock">
						<?php _e('Только в наличии', 'woocommerce'); ?>
					</label>
				</div>
				
				<!-- Фильтрация по категориям -->
				<div class="accordion" id="filterAccordion">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingCategories">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
								<?php _e('Категории', 'woocommerce'); ?>
							</button>
						</h2>
						<div id="collapseCategories" class="accordion-collapse collapse show" aria-labelledby="headingCategories">
							<div class="accordion-body">
								<?php
								$categories = get_terms(array(
									'taxonomy' => 'product_cat',
									'hide_empty' => true
								));
								foreach ($categories as $category) {
									echo '<label><input type="checkbox" name="categories[]" value="' . esc_attr($category->term_id) . '"> ' . esc_html($category->name) . '</label>';
								}
								?>
							</div>
						</div>
					</div>

					<!-- Фильтрация по меткам -->
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTags">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTags" aria-expanded="false" aria-controls="collapseTags">
								<?php _e('Метки', 'woocommerce'); ?>
							</button>
						</h2>
						<div id="collapseTags" class="accordion-collapse collapse" aria-labelledby="headingTags">
							<div class="accordion-body">
								<?php
								$tags = get_terms('product_tag');
								foreach ($tags as $tag) {
									echo '<label><input type="checkbox" name="tags[]" value="' . esc_attr($tag->term_id) . '"> ' . esc_html($tag->name) . '</label>';
								}
								?>
							</div>
						</div>
					</div>

					<!-- Фильтрация по атрибутам -->
					<?php
					$attributes = wc_get_attribute_taxonomies();
					foreach ($attributes as $attribute) {
						$taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
						$terms = get_terms($taxonomy);
						if (!empty($terms)) {
							?>
							<div class="accordion-item">
								<h2 class="accordion-header" id="heading<?php echo esc_attr($taxonomy); ?>">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo esc_attr($taxonomy); ?>" aria-expanded="false" aria-controls="collapse<?php echo esc_attr($taxonomy); ?>">
										<?php echo esc_html($attribute->attribute_label); ?>
									</button>
								</h2>
								<div id="collapse<?php echo esc_attr($taxonomy); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo esc_attr($taxonomy); ?>">
									<div class="accordion-body">
										<?php foreach ($terms as $term) : ?>
											<label><input type="checkbox" name="attributes[<?php echo esc_attr($taxonomy); ?>][]" value="<?php echo esc_attr($term->term_id); ?>"> <?php echo esc_html($term->name); ?></label>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php }
					}?>
				</div>

				<!-- Кнопки -->
				<div class="mt-3">
					<button type="button" id="apply-filter" class="btn btn-primary"><?php _e('Применить фильтр', 'woocommerce'); ?></button>
					<button type="button" id="reset-filter" class="btn btn-secondary"><?php _e('Сбросить фильтр', 'woocommerce'); ?></button>
				</div>
			</form>
		</div>
	</div>

	<section class="product-details-section section-padding fix">
	<div class="container">
	<div class="product-details-wrapper">
	<div class="top-content">
		<h2><?php woocommerce_page_title(); ?></h2>
		<?php woocommerce_breadcrumb();?>
		<ul class="list">
			<li>Home</li>
			<li>
				Only Categories
			</li>
		</ul>
	</div>
	<div class="product-details-sideber">
		
		<?php
		$view_type = isset($_GET['view_type']) ? sanitize_text_field($_GET['view_type']) : 'grid';
		$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
		?>
		<div class="product-details-wrap">
			<ul id="viewSwitcher" class="nav">
				<li class="nav-item">
					<a id="grid-view" href="#" class="nav-link <?php echo $view_type === 'grid' ? 'active' : ''; ?>">
						<i class="fa-regular fa-grid-2"></i>
					</a>
				</li>
				<li class="nav-item">
					<a id="list-view" href="#" class="nav-link <?php echo $view_type === 'list' ? 'active' : ''; ?>">
						<i class="fas fa-bars"></i>
					</a>
				</li>
			</ul>
			<div id="products-count-wrap"></div>
		</div>

		<div class="shop-right">
			<div class="catalog-sort">
<!--				<label for="sort-by">--><?php //_e('сортировать:', 'woocommerce'); ?><!--</label>-->
				<select id="sort-by">
<!--					<option value="menu_order" --><?php //selected($orderby, 'menu_order'); ?><!-->--><?php //_e('сортировать по:', 'woocommerce'); ?><!--</option>-->
					<option value="date" <?php selected($orderby, 'date'); ?>><?php _e('по новизне', 'woocommerce'); ?></option>
					<option value="popularity" <?php selected($orderby, 'popularity'); ?>><?php _e('по популярности', 'woocommerce'); ?></option>
					<option value="price" <?php selected($orderby, 'price'); ?>><?php _e('цена: по возрастанию', 'woocommerce'); ?></option>
					<option value="price-desc" <?php selected($orderby, 'price-desc'); ?>><?php _e('цена: по убыванию', 'woocommerce'); ?></option>
				</select>
			</div>

			
			<div id="openButton2">
				<div class="filter-button">
					<button  data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
						<span><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/filter.png')?>"
								   alt="img"></span>
						<?php _e('Фильтр', 'woocommerce'); ?>
					</button>
					
				</div>
			</div>
		</div>
	</div>


		<div id="filtered-products" class=" tab-content product-layout <?php echo $view_type === 'list' ? 'list-layout' : 'grid-layout'; ?>">
			<?php
			global $wp_query;
			
			 $paged = get_query_var('paged') ? get_query_var('paged') : 1;
			
			$query_args = array(
				'post_type'      => 'product',
				'paged'          => $paged,
				'posts_per_page' => 1,
				'orderby'        => $orderby,
			);
			
			// Поддержка специальных типов сортировки
			if ($orderby === 'price') {
				$query_args['meta_key'] = '_price';
				$query_args['orderby']  = 'meta_value_num';
				$query_args['order']    = 'ASC';
			} elseif ($orderby === 'price-desc') {
				$query_args['meta_key'] = '_price';
				$query_args['orderby']  = 'meta_value_num';
				$query_args['order']    = 'DESC';
			} elseif ($orderby === 'date') {
				$query_args['orderby'] = 'date';
				$query_args['order'] = 'DESC';
			} elseif ($orderby === 'popularity') {
				$query_args['meta_key'] = 'total_sales';
				$query_args['orderby'] = 'meta_value_num';
				$query_args['order'] = 'DESC';
			}
			
			$query = new WP_Query($query_args);
			$wp_query = $query;
			$all_products = $wp_query->posts;
			?>

			<div class="row">
				<?php foreach ($all_products as $post) : setup_postdata($post); ?>
					<?php
					if ($view_type === 'list') {
						get_template_part('template-parts/products/tab', 'curriculum');
					} else {
						get_template_part('template-parts/products/tab', 'course');
					}
					?>
				<?php endforeach; ?>
				
				<?php wp_reset_postdata(); ?>
			</div>

			<div class="page-nav-wrap">
				<ul>
					<?php
					echo paginate_links(array(
						'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
						'format'    => '',
						'current'   => max(1, get_query_var('paged')),
						'total'     => $wp_query->max_num_pages,
						'prev_text' => '<i class="fa-solid fa-arrow-left-long"></i>',
						'next_text' => '<i class="fa-solid fa-arrow-right-long"></i>',
						'type'      => 'list',
					));
					?>
				</ul>
			</div>
		</div>

		<div id="ajax-spinner" class="ajax-loader" style="display: none;">
			<div class="spinner"></div>
		</div>
		
	
	</div>
	</div>
	</section>


<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
//do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
