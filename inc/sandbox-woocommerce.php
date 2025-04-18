<?php
function sandbox_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'sandbox_add_woocommerce_support' );

//Регистрация архивов для таксономий атрибутов WooCommerce (по умолчанию не зарегистрированы), чтобы адекватно
// работала фильтрация по атрибутам

//add_action('init', function () {
//	// Получаем все атрибуты WooCommerce
//	$attributes = wc_get_attribute_taxonomies();
//
//	if (!empty($attributes)) {
//		foreach ($attributes as $attribute) {
//			$taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
//
//			register_taxonomy($taxonomy, ['product'], array(
//				'label'             => $attribute->attribute_label,
//				'public'            => true,
//				'hierarchical'      => true,
//				'show_ui'           => true,
//				'show_admin_column' => true,
//				'query_var'         => true,
//				'show_in_nav_menus' => true,
//				'rewrite'           => array(
//					'slug'         => 'pa_' . $attribute->attribute_name,
//					'with_front'   => false,
//					'hierarchical' => true,
//				),
//				'has_archive' => true,
//			));
//		}
//	}
//});
//add_action('init', function () {
//	$taxonomies = get_taxonomies([], 'names');
//	echo '<pre>';
//	print_r($taxonomies);
//	echo '</pre>';
//});
