<?php
function sandbox_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'sandbox_add_woocommerce_support' );

//Функция для кастомных хлебных крошек (полный контроль)

function custom_woo_breadcrumbs() {
	$crumbs = [];
	
	// Главная
	$crumbs[] = ['Главная', home_url('/')];
	
	// Каталог
	$shop_page_id = wc_get_page_id('shop');
	if ($shop_page_id && get_permalink($shop_page_id)) {
		$crumbs[] = [get_the_title($shop_page_id), get_permalink($shop_page_id)];
	}
	
	// Таксономии товара: категории, теги, атрибуты
	if (is_product_category()) {
		$term = get_queried_object();
		$parents = get_ancestors($term->term_id, 'product_cat');
		
		foreach (array_reverse($parents) as $parent_id) {
			$parent = get_term($parent_id, 'product_cat');
			$crumbs[] = [$parent->name, get_term_link($parent)];
		}
		
		$crumbs[] = [$term->name, ''];
	}
	elseif (is_tax()) {
		$term = get_queried_object();
		if ($term && !is_wp_error($term)) {
			$taxonomy = get_taxonomy($term->taxonomy);
			if (!empty($taxonomy->labels->singular_name)) {
				// Можно добавить, если хочешь выводить заголовок таксономии (например "Тип кожи")
				// $crumbs[] = [$taxonomy->labels->singular_name, ''];
			}
			$crumbs[] = [$term->name, ''];
		}
	}
	elseif (is_product()) {
		global $post;
		$product = wc_get_product($post);
		
		// Основная категория (можно поменять стратегию выбора)
		$terms = get_the_terms($post->ID, 'product_cat');
		if (!empty($terms)) {
			$term = array_shift($terms);
			$parents = get_ancestors($term->term_id, 'product_cat');
			
			foreach (array_reverse($parents) as $parent_id) {
				$parent = get_term($parent_id, 'product_cat');
				$crumbs[] = [$parent->name, get_term_link($parent)];
			}
			
			$crumbs[] = [$term->name, get_term_link($term)];
		}
		
		$crumbs[] = [$product->get_name(), ''];
	}
	
	// Вывод
	echo '<ul class="list">';
	foreach ($crumbs as $index => $crumb) {
		[$label, $link] = $crumb;
		if (!empty($link)) {
			echo '<li><a href="' . esc_url($link) . '">' . esc_html($label) . '</a></li>';
		} else {
			echo '<li>' . esc_html($label) . '</li>';
		}
		if ($index < count($crumbs) - 1) {
			echo '<li class="separator"><div></div></li>';
		}
	}
	echo '</ul>';
}

//Вывод на фронт:  custom_woo_breadcrumbs();






//Регистрация архивов для таксономий атрибутов WooCommerce (по умолчанию не зарегистрированы), чтобы адекватно
// работала фильтрация по атрибутам (необязательно регистрировать, можно просто выбрать при создании таксономий
// "архив" в админке. Не забыть потом зайти и пересохраниться в постоянных ссылках)

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
