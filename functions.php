<?php
/**
 * sandbox functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sandbox
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sandbox_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on sandbox, use a find and replace
		* to change 'sandbox' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'sandbox', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'main_menu' => esc_html__( 'Главное меню', 'sandbox' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'sandbox_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'sandbox_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sandbox_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sandbox_content_width', 640 );
}
add_action( 'after_setup_theme', 'sandbox_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sandbox_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'sandbox' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'sandbox' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'sandbox_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sandbox_scripts() {
	//Дерегистрируем стили WC
	wp_deregister_style('woocommerce-general');
	wp_deregister_style('woocommerce-layout');
	
	//wp_enqueue_style( 'sandbox-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'sandbox-style', 'rtl', 'replace' );
	wp_enqueue_style('all.min', get_template_directory_uri() . '/assets/css/all.min.css', array(), _S_VERSION);
	wp_enqueue_style('bootstrap.min', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('animate', get_template_directory_uri() . '/assets/css/animate.css', array(), _S_VERSION);
	wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), _S_VERSION);
	wp_enqueue_style('meanmenu', get_template_directory_uri() . '/assets/css/meanmenu.css', array(), _S_VERSION);
	wp_enqueue_style('swiper-bundle', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), _S_VERSION);
	wp_enqueue_style('nice-select', get_template_directory_uri() . '/assets/css/nice-select.css', array(), _S_VERSION);
	wp_enqueue_style('color', get_template_directory_uri() . '/assets/css/color.css', array(), _S_VERSION);
	wp_enqueue_style('plugins', get_template_directory_uri() . '/assets/css/plugins.css', array(), _S_VERSION);
	wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css', array(), time());
	wp_enqueue_style('sandbox-main', get_template_directory_uri() . '/assets/css/sandbox-main.css', array(), time());
	
	
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'sandbox-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'viewport.jquery', get_template_directory_uri() . '/assets/js/viewport.jquery.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'bootstrap.bundle.min', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'jquery.nice-select.min', get_template_directory_uri() . '/assets/js/jquery.nice-select.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'jquery.waypoints', get_template_directory_uri() . '/assets/js/jquery.waypoints.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'jquery.counterup.min', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'swiper-bundle.min', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'jquery.meanmenu.min', get_template_directory_uri() . '/assets/js/jquery.meanmenu.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'jquery.magnific-popup.min', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'wow.min', get_template_directory_uri() . '/assets/js/wow.min.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'plugins', get_template_directory_uri() . '/assets/js/plugins.js', array(),
		_S_VERSION, true );
	wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array(),
		time(), true );
	
//	wp_enqueue_script( 'sandbox-filter', get_template_directory_uri() . '/assets/js/filter.js', array(),
//		time(), true );
	//wp_enqueue_script( 'ajax-pagination', get_template_directory_uri() . '/assets/js/ajax-pagination.js', array(),
		//time(), true );
//	wp_enqueue_script( 'sandbox-script', get_template_directory_uri() . '/assets/js/sandbox-script.js', array(),
//		time(), true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sandbox_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * IP Detection.
 */
require get_template_directory() . '/inc/sandbox-ip-detect.php';

/**
 * Custom Functions.
 */
require get_template_directory() . '/inc/sandbox-custom-functions.php';
/**
 * Custom Woocommerce Functions.
 */
require get_template_directory() . '/inc/sandbox-woocommerce.php';
/**
 *  Event Calendar.
 */
  require get_template_directory() . '/inc/event-calendar.php';
  /**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
/**
 * Product Ajax.
 */
require get_template_directory() . '/inc/ajax-products.php';

/**
 * Product Filter.
 */
//require get_template_directory() . '/inc/functions-pagination-sorting.php';
