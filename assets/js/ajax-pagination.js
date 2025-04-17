jQuery(document).ready(function ($) {
    $(document).on('click', '.page-numbers a', function (e) {
        e.preventDefault();

        const page = $(this).text(); // номер страницы
        const activeTab = $('.tab-pane.active').attr('id'); // 'Course' или 'Curriculum'

        $('.ajax-loader').fadeIn(200); // показываем спиннер

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'load_products',
                page: page,
                view_type: activeTab,
            },
            // beforeSend: function () {
            //     $('.ajax-loader').fadeIn(200);
            //     //$('#filtered-products').addClass('loading');
            // },
            success: function (response) {
                // $('#filtered-products').html(response);
                // Вставляем контент
                const container = $('#filtered-products');
                container.html(response);

                const cards = container.find('.product-details-item');

                // Добавляем анимацию с задержкой
                cards.each(function (i) {
                    const delay = i * 200; // задержка в мс (0.2 сек)
                    $(this)
                        .css('--delay', `${delay}ms`)
                        .addClass('fade-in');

                    setTimeout(() => {
                        $(this).addClass('visible');
                    }, delay);
                });

                // Добавляем анимацию
                // container.find('.product-details-item').addClass('fade-in');
                // setTimeout(function () {
                //     container.find('.fade-in').addClass('visible');
                // }, 50);
            },
            complete: function () {
                $('.ajax-loader').fadeOut(200); // скрываем спиннер

                // Плавный скролл к карточкам
                $('html, body').animate({
                    scrollTop: $('#filtered-products').offset().top - 80 // отступ от верха
                }, 500);
                //$('#filtered-products').removeClass('loading');
            },
            error: function () {
                alert('Ошибка загрузки товаров');
            },
        });
    });
});
