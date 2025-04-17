<?php
//Подключение API
function get_user_country() {
	$ip = $_SERVER['REMOTE_ADDR']; // Получаем IP пользователя
	$api_token = '92300f0e858005'; // Замените на свой токен
	$api_url = "https://ipinfo.io/{$ip}/json?token={$api_token}";
	$response = wp_remote_get($api_url);
	
	if (is_wp_error($response)) {
		return false; // Ошибка запроса
	}
	
	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);
	
	return $data['country'] ?? false; // Возвращаем код страны (например, "US", "RU")
}
//Метабокс в продукте для запрещенных стран
function add_custom_product_fields() {
	woocommerce_wp_text_input(array(
		'id' => '_banned_countries',
		'label' => __('Запрещенные страны (через запятую)', 'woocommerce'),
		'description' => __('Введите коды стран (например: US, DE, FR)', 'woocommerce'),
		'desc_tip' => true,
	));
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_fields');

function save_custom_product_fields($post_id) {
	$banned_countries = isset($_POST['_banned_countries']) ? sanitize_text_field($_POST['_banned_countries']) : '';
	update_post_meta($post_id, '_banned_countries', $banned_countries);
}
add_action('woocommerce_process_product_meta', 'save_custom_product_fields');

//Блокировка кнопки "Купить"
function restrict_purchase_button() {
	if (!is_product()) return; // Выполняем только на странице продукта
	
	$user_country = get_user_country(); // Определяем страну пользователя
	$banned_countries = get_post_meta(get_the_ID(), '_banned_countries', true);
	$banned_list = array_map('trim', explode(',', $banned_countries)); // Преобразуем строку в массив
	
	if ($user_country && in_array($user_country, $banned_list)) {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30); // Убираем кнопку "Купить"
		add_action('woocommerce_single_product_summary', function() {
			echo "<div class='banned-message' style='color: red; font-weight: bold;'>Извините, в вашей стране этот продукт недоступен для покупки.</div>";
		}, 30);
	}
}
add_action('wp', 'restrict_purchase_button');

//Блокировка добавления в корзину
function block_cart_addition($passed, $product_id) {
	$user_country = get_user_country();
	$banned_countries = get_post_meta($product_id, '_banned_countries', true);
	$banned_list = array_map('trim', explode(',', $banned_countries));
	
	if ($user_country && in_array($user_country, $banned_list)) {
		wc_add_notice('Извините, в вашей стране этот продукт недоступен для покупки.', 'error');
		return false;
	}
	
	return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'block_cart_addition', 10, 2);

//<Блокировка оформления заказа
function block_checkout() {
	$cart_items = WC()->cart->get_cart();
	$user_country = get_user_country();
	
	foreach ($cart_items as $item) {
		$banned_countries = get_post_meta($item['product_id'], '_banned_countries', true);
		$banned_list = array_map('trim', explode(',', $banned_countries));
		
		if ($user_country && in_array($user_country, $banned_list)) {
			wc_add_notice('Некоторые товары в вашей корзине недоступны для покупки в вашей стране.', 'error');
			wp_redirect(wc_get_cart_url()); // Перенаправляем обратно в корзину
			exit;
		}
	}
}
add_action('woocommerce_before_checkout_process', 'block_checkout');
