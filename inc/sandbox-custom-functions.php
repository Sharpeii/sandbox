<?php
class Custom_Menu_Walker extends Walker_Nav_Menu {
	
	// Вывод начала элемента меню
	function start_lvl(&$output, $depth = 0, $args = null) {
		$submenu = ($depth > 0) ? 'submenu' : 'submenu has-homemenu';
		$output .= "\n<ul class=\"$submenu\">\n";
	}
	
	// Вывод начала элемента списка (li)
	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		// Добавляем класс menu-item-has-children, если пункт — "Каталог" и есть категории
		$has_children_class = ($item->title === 'Каталог' && $this->has_catalog_categories()) ? ' menu-item-has-children' : '';
		$classes = implode(' ', $item->classes) . $has_children_class;
		$output .= "<li class='" . esc_attr($classes) . "'>";
		
		$attributes = '';
		!empty($item->attr_title) && $attributes .= ' title="' . esc_attr($item->attr_title) . '"';
		!empty($item->url) && $attributes .= ' href="' . esc_url($item->url) . '"';
		
		$item_output = '<a' . $attributes . '>';
		$item_output .= $item->title;
		$item_output .= '</a>';
		
		$output .= $item_output;
	}
	
	// Проверка на наличие категорий
	private function has_catalog_categories() {
		$categories = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
		));
		return !empty($categories);
	}
	
	// Добавляем статический элемент "Каталог"
	function end_el(&$output, $item, $depth = 0, $args = null) {
		if ($item->title === 'Каталог') {
			$output .= $this->get_catalog_categories();
		}
		$output .= "</li>\n";
	}
	
	// Динамически подтягиваем категории товаров в подменю "Каталог"
	private function get_catalog_categories() {
		$categories = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
		));
		
		$html = '<ul class="submenu has-homemenu"><li><div class="homemenu-items">';
		
		foreach ($categories as $category) {
			$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
			$image_url = wp_get_attachment_url($thumbnail_id);
			$category_link = get_term_link($category);
			
			$html .= '<div class="homemenu">';
			$html .= '<div class="homemenu-thumb mb-15 ">';
			$html .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($category->name) . '">';
			$html .= '<div class="demo-button">';
			$html .= '<a href="' . esc_url($category_link) . '" class="theme-btn">Перейти</a>';
			$html .= '</div></div>';
			$html .= '<div class="homemenu-content text-center">';
			$html .= '<h4 class="homemenu-title">' . esc_html($category->name) . '</h4>';
			$html .= '</div></div>';
		}
		
		$html .= '</div></li></ul>';
		return $html;
	}
}
