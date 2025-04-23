<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sandbox
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'sandbox' ); ?></a>

	<!-- Preloader Start -->
	<div id="preloader" class="preloader">
		<div class="animation-preloader">
			<div class="spinner">
			</div>
			<div class="txt-loading">
                    <span data-text-preloader="E" class="letters-loading">
                        E
                    </span>
				<span data-text-preloader="C" class="letters-loading">
                        C
                    </span>
				<span data-text-preloader="O" class="letters-loading">
                        O
                    </span>
				<span data-text-preloader="M" class="letters-loading">
                        M
                    </span>
				<span data-text-preloader="A" class="letters-loading">
                        A
                    </span>
				<span data-text-preloader="S" class="letters-loading">
                        S
                    </span>
			</div>
			<p class="text-center">Загрузка</p>
		</div>
		<div class="loader">
			<div class="row">
				<div class="col-3 loader-section section-left">
					<div class="bg"></div>
				</div>
				<div class="col-3 loader-section section-left">
					<div class="bg"></div>
				</div>
				<div class="col-3 loader-section section-right">
					<div class="bg"></div>
				</div>
				<div class="col-3 loader-section section-right">
					<div class="bg"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Back To Top Start -->
	<button id="back-top" class="back-to-top">
		<i class="fa-regular fa-arrow-up"></i>
	</button>

	<!--<< Mouse Cursor Start >>-->
	<div class="mouse-cursor cursor-outer"></div>
	<div class="mouse-cursor cursor-inner"></div>

	<!-- fix-area Start -->
	<div class="fix-area">
		<div class="offcanvas__info">
			<div class="offcanvas__wrapper">
				<div class="offcanvas__content">
					<div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
						<div class="offcanvas__logo">
							<a href="index.html">
								<img src="assets/img/logo/black-logo.svg" alt="logo-img">
							</a>
						</div>
						<div class="offcanvas__close">
							<button>
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<p class="text d-none d-xl-block">
						Nullam dignissim, ante scelerisque the  is euismod fermentum odio sem semper the is erat, a feugiat leo urna eget eros. Duis Aenean a imperdiet risus.
					</p>
					<div class="mobile-menu fix mb-3"></div>
					<div class="offcanvas__contact">
						<h4>Contact Info</h4>
						<ul>
							<li class="d-flex align-items-center">
								<div class="offcanvas__contact-icon">
									<i class="fal fa-map-marker-alt"></i>
								</div>
								<div class="offcanvas__contact-text">
									<a target="_blank" href="#">Main Street, Melbourne, Australia</a>
								</div>
							</li>
							<li class="d-flex align-items-center">
								<div class="offcanvas__contact-icon mr-15">
									<i class="fal fa-envelope"></i>
								</div>
								<div class="offcanvas__contact-text">
									<a href="mailto:info@example.com"><span class="mailto:info@example.com">info@example.com</span></a>
								</div>
							</li>
							<li class="d-flex align-items-center">
								<div class="offcanvas__contact-icon mr-15">
									<i class="fal fa-clock"></i>
								</div>
								<div class="offcanvas__contact-text">
									<a target="_blank" href="#">Mod-friday, 09am -05pm</a>
								</div>
							</li>
							<li class="d-flex align-items-center">
								<div class="offcanvas__contact-icon mr-15">
									<i class="far fa-phone"></i>
								</div>
								<div class="offcanvas__contact-text">
									<a href="tel:+11002345909">+11002345909</a>
								</div>
							</li>
						</ul>
						<div class="header-button mt-4">

						</div>
						<a href="contact.html" class="theme-btn"><span>Let’s Talk <i class="fa-solid fa-arrow-right"></i></span></a>
						<div class="social-icon d-flex align-items-center">
							<a href="#"><i class="fab fa-facebook-f"></i></a>
							<a href="#"><i class="fab fa-twitter"></i></a>
							<a href="#"><i class="fab fa-youtube"></i></a>
							<a href="#"><i class="fab fa-linkedin-in"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="offcanvas__overlay"></div>

	<!-- Sidebar Area Here -->
	<div id="targetElement" class="side_bar slideInRight side_bar_hidden">
		<div class="side_bar_overlay"></div>
		<div class="cart-title mb-50">
			<h4>Log in</h4>
		</div>
		<div class="login-sidebar">
			<form action="#" id="contact-form" method="POST">
				<div class="row g-4">
					<div class="col-lg-12">
						<div class="form-clt">
							<span>Username or email address *</span>
							<input type="text" name="name15" id="name15" placeholder="">
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-clt">
							<span>Password *</span>
							<input id="password" type="password" placeholder="">
							<div class="icon"><i class="fa-regular fa-eye"></i></div>
						</div>
					</div>
					<div class="col-lg-12">
						<button class="theme-btn style-2" type="submit"><span>Log In</span></button>
					</div>
					<div class="col-lg-12">
						<div class="from-cheak-items">
							<div class="form-check d-flex gap-2 from-customradio">
								<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
								<label class="form-check-label" for="flexRadioDefault1">
									Remember Me
								</label>
							</div>
							<p>Forgot Password?</p>
						</div>
					</div>
				</div>
			</form>
			<p class="text">Or login with</p>
			<div class="social-item">
				<a href="#" class="facebook-text style-2"><img src="assets/img/facebook.png" alt="img">FACEBOOK</a>
				<a href="#" class="facebook-text google-text style-2"><img src="assets/img/google.png" alt="img">Google</a>
			</div>
			<div class="user-icon-box">
				<img src="assets/img/user.png" alt="img">
				<p>No account yet?</p>
				<a href="account.html">Create an Account</a>
			</div>
		</div>
		<button id="closeButton" class="x-mark-icon"><i class="fas fa-times"></i></button>
	</div>

	<!-- Header top Section Start -->
	<div class="header-top-section style-2">
		<div class="container-fluid">
			<div class="header-top-wrapper style-2">
				<ul class="contact-list">
					<li>
						<i class="fa-brands fa-facebook-f"></i>
						7500k Followers
					</li>
					<li>
						<i class="fa-solid fa-phone"></i>
						<a href="tel:+40276328246">+402 763 282 46</a>
					</li>
				</ul>
				<div class="flag-wrapper">
					<div class="flag-wrap">
						<div class="nice-select" tabindex="0">
                                <span class="current">
                                    English
                                </span>
							
							<ul class="list">
								<li data-value="1" class="option selected focus">
									English
								</li>
								<li data-value="1" class="option">
									Bangla
								</li>
								<li data-value="1" class="option">
									Hindi
								</li>
							</ul>
						</div>
					</div>
					<div class="flag-wrap">
						<div class="nice-select style-2" tabindex="0">
                                <span class="current">
                                    $Usd
                                </span>
							<ul class="list">
								<li data-value="1" class="option selected focus">
									$Usd
								</li>
								<li data-value="1" class="option">
									$AUD
								</li>
								<li data-value="1" class="option">
									$Eur
								</li>
							</ul>
						</div>
					</div>
					<div class="content">
						<button id="openButton" class="account-text d-flex align-items-center gap-2">
							<i class="fa-regular fa-user"></i>
							Log in
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Header Section Start -->
	<header id="header-sticky" class=" header-1 header-2">
		<div class="container-fluid">
			<div class="mega-menu-wrapper">
				<div class="header-main">
					<div class="header-left">
						<div class="logo">
							<a href="index.html" class="header-logo">
								<img src="assets/img/logo/black-logo.svg" alt="logo-img">
							</a>
							<a href="index.html" class="header-logo-2 d-none">
								<img src="assets/img/logo/black-logo.svg" alt="logo-img">
							</a>
						</div>
						<div class="mean__menu-wrapper">
							<div class="main-menu">
								<nav id="mobile-menu" style="display: block;">
									<?php
									wp_nav_menu(array(
										'theme_location' => 'main_menu',
										'container' => false,
										'menu_class' => 'menu',
										'walker' => new Custom_Menu_Walker(), // Используем наш волкер
									));
									?>
								
								</nav>
							</div>
						</div>
					</div>
					<div class="header-right d-flex justify-content-end align-items-center">

						<a href="#0" class="search-trigger search-icon"><i class="fa-regular fa-magnifying-glass"></i></a>
						<ul class="header-icon">

							<li>
								<a href="#"><i class="fa-regular fa-heart"></i><span class="number">4</span></a>
							</li>
						</ul>
						<div class="menu-cart style-2">
							<div class="cart-box">
								<ul>
									<li>
										<img src="assets/img/cart/01.jpg" alt="image">
										<div class="cart-product">
											<a href="#">Android phone</a>
											<span>118$</span>
										</div>
									</li>
								</ul>
								<ul>
									<li class="border-none">
										<img src="assets/img/cart/02.jpg" alt="image">
										<div class="cart-product">
											<a href="#">Macbook Book</a>
											<span>268$</span>
										</div>
									</li>
								</ul>
								<div class="shopping-items">
									<span>Total :</span>
									<span>$386.00</span>
								</div>
								<div class="cart-button mb-4">
									<a href="shop-cart.html" class="theme-btn">
										View Cart
									</a>
								</div>
							</div>
							<a href="shop-cart.html" class="cart-icon">
								<i class="fa-sharp fa-regular fa-bag-shopping"></i>
							</a>
						</div>
						<div class="header__hamburger d-xl-none my-auto">
							<div class="sidebar__toggle">
								<i class="fas fa-bars"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- search-wrap Start -->
	<div class="search-wrap">
		<div class="search-inner">
			<i class="fas fa-times search-close" id="search-close"></i>
			<div class="search-cell">
				<form method="get">
					<div class="search-field-holder">
						<input type="search" class="main-search-input" placeholder="Search...">
					</div>
				</form>
			</div>
		</div>
	</div>
