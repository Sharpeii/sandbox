// assets/js/ajax-pagination-switch-view.js

jQuery(function ($) {
    const $container = $('#filtered-products');
    const $spinner = $('.ajax-loader');
    const $paginationWrapper = $(document);
    const viewSwitcher = $('#viewSwitcher');
    const gridViewBtn = $('#grid-view');
    const listViewBtn = $('#list-view');
    const sortSelect = $('#sort-by');
    const $filterForm = $('#product-filter-form');
    const $resultCount = $('#products-count-wrap'); // Добавляем обёртку для количества товаров

    let viewType = localStorage.getItem('view_type') || 'grid';
    let orderby = sortSelect.val() || 'date';
    function setView(view) {
        viewType = view;
        localStorage.setItem('view_type', view);

        if (view === 'list') {
            listViewBtn.addClass('active');
            gridViewBtn.removeClass('active');
            $container.removeClass('grid-layout').addClass('list-layout');
        } else {
            gridViewBtn.addClass('active');
            listViewBtn.removeClass('active');
            $container.removeClass('list-layout').addClass('grid-layout');
        }
    }

    function loadProducts(page = 1, view = viewType, sort = orderby) {
        $spinner.show();

        // Сериализуем форму фильтра и добавляем сортировку, вид и пагинацию
        const formData = $filterForm.serialize();

        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'load_products',
                form_data: formData,
                page: page,
                view_type: view,
                orderby: sort
            },
            success: function (response) {
                $container.html(response.data.html);

                // // Обновление количества товаров
                if (response.data.count_html) {
                    $resultCount.html(response.data.count_html);
                }

                setView(view);
                $container.hide().fadeIn(300);
                window.scrollTo({ top: $container.offset().top - 100, behavior: 'smooth' });
                $spinner.hide();

            }
        });
    }

    // Инициализация ползунка цены через jQuery UI с wp_localize_script
    const priceSlider = $('#price-slider-range');
    const $minInput = $('#min-price');
    const $maxInput = $('#max-price');
    const $minLabel = $('#min-price-label');
    const $maxLabel = $('#max-price-label');
    if (typeof my_ajax_object !== 'undefined') {
        const min = parseFloat(my_ajax_object.min_price);
        const max = parseFloat(my_ajax_object.max_price);

        if (!isNaN(min) && !isNaN(max) && priceSlider.length) {
            $minLabel.text(min);
            $maxLabel.text(max);
            $minInput.val(min);
            $maxInput.val(max);

            priceSlider.slider({
                range: true,
                min: min,
                max: max,
                values: [min, max],
                slide: function (event, ui) {
                    $minLabel.text(ui.values[0]);
                    $maxLabel.text(ui.values[1]);
                    $minInput.val(ui.values[0]);
                    $maxInput.val(ui.values[1]);
                }
            });
        }
    }



    // Переключение через кнопки-свитчеры (сеткой/списком)
    viewSwitcher.on('click', 'a', function (e) {
        e.preventDefault();
        const view = $(this).attr('id') === 'list-view' ? 'list' : 'grid';
        orderby = sortSelect.val();
        loadProducts(1, view, orderby);
    });

    // Пагинация
    $paginationWrapper.on('click', '.page-nav-wrap a.page-numbers', function (e) {
        e.preventDefault();
        const $this = $(this);
        const currentView = localStorage.getItem('view_type') || 'grid';
        const orderby = sortSelect.val();

        let page;
        if ($this.hasClass('next')) {
            page = parseInt($('.page-numbers.current').text()) + 1;
        } else if ($this.hasClass('prev')) {
            page = parseInt($('.page-numbers.current').text()) - 1;
        } else {
            page = parseInt($this.text());
        }
        loadProducts(page, viewType, orderby);
    });

    //Сортировка (изменение селекта)
    sortSelect.on('change', function () {
        const orderby = $(this).val();
        const currentView = localStorage.getItem('view_type') || 'grid';
        loadProducts(1, currentView, orderby);
    });

    // Клик по кнопке "Применить фильтр"
    $('#apply-filter').on('click', function (e) {
        e.preventDefault();
        orderby = sortSelect.val();
        loadProducts(1, viewType, orderby);

        // Закрываем оффканвас
        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('filterOffcanvas'));
        if (offcanvas) {
            offcanvas.hide();
        }
    });

    // Кнопка сброса фильтра (если есть)
    $('#reset-filter').on('click', function () {
        $filterForm[0].reset();

        // Обновим значения ползунка до начальных
        if (typeof my_ajax_object !== 'undefined') {
            const min = parseFloat(my_ajax_object.min_price);
            const max = parseFloat(my_ajax_object.max_price);

            if (!isNaN(min) && !isNaN(max)) {
                priceSlider.slider('values', [min, max]);
                $minLabel.text(min);
                $maxLabel.text(max);
                $minInput.val(min);
                $maxInput.val(max);
            }
        }


        loadProducts(1, viewType, orderby);
    });

    // Первая загрузка
    loadProducts(1);
});
