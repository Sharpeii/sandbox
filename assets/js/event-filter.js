
document.addEventListener('DOMContentLoaded', function() {
    console.log("filterData:", filterdata); //Проверка правильно ли переданы данные через filterdata
    let eventDates = filterdata.event_dates; // Даты событий, переданные из PHP
    const monthDisplay = document.getElementById('month-display');
    const calendarDays = document.getElementById('calendar-days');
    const eventList = document.getElementById('event-list');
    let currentDate = new Date(); // сегодняшняя дата
    let visibleMonthIndex = 0; // Индекс видимого месяца относительно текущего месяца

    // Функция для рендеринга двух месяцев (выбранного и следующего)
    function renderMonths() {
        monthDisplay.innerHTML = '';
        for (let i = 0; i < 2; i++) {
            const monthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + visibleMonthIndex + i, 1);
            const monthElement = document.createElement('div');
            monthElement.classList.add('month');
            monthElement.textContent = monthDate.toLocaleString('default', { month: 'long' });

            // Выделение активного месяца
            if (i === 0) monthElement.classList.add('active');
            monthDisplay.appendChild(monthElement);
        }
    }

    // Функция для рендеринга дней выбранного месяца
    function renderDays() {
        calendarDays.innerHTML = '';
        const selectedMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + visibleMonthIndex, 1);
        const year = selectedMonthDate.getFullYear();
        const month = selectedMonthDate.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        console.log(today);

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const dayDate = new Date(year, month, day);
            const dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.textContent = `${day} ${dayDate.toLocaleDateString('default', { weekday: 'short' })}`;

            // Помечаем прошедшие дни текущего месяца
            if (month === today.getMonth() && year === today.getFullYear() && dayDate < today) {
                dayElement.classList.add('past-day');
            }

            // Подсвечиваем дни с событиями
            if (eventDates.includes(dateStr)) {
                dayElement.classList.add('has-event');
            }

            // Обработчик клика по дню
            dayElement.addEventListener('click', function() {
                calendarDays.querySelectorAll('.day').forEach(el => el.classList.remove('selected'));
                dayElement.classList.add('selected');
                loadEvents(dateStr);
            });

            calendarDays.appendChild(dayElement);
        }
    }

    // Функция для загрузки событий на выбранный день
    function loadEvents(date) {
        console.log("Загрузка событий для даты:", date); // Отладочный вывод
        jQuery.ajax({
            url: filterdata.ajax_url,
            method: 'POST',
            data: {
                action: 'filter_events',
                event_date: date
            },
            success: function(response) {
                console.log("Ответ AJAX:", response); // Отладочный вывод
                eventList.innerHTML = response;
            },
            error: function(xhr, status, error) {
                console.error("Ошибка AJAX:", status, error); // Отладочный вывод
            }
        });
    }

    // Функция для загрузки всех событий (по умолчанию)
    function loadAllEvents() {
        console.log("Загрузка всех событий"); //  отладочный вывод
        jQuery.ajax({
            url: filterdata.ajax_url,
            method: 'POST',
            data: {
                action: 'load_all_events'
            },
            success: function(response) {
                console.log("Ответ AJAX для всех событий:", response); // Отладочный вывод
                eventList.innerHTML = response;
            },
            error: function(xhr, status, error) {
                console.error("Ошибка AJAX при загрузке всех событий:", status, error); // Отладочный вывод
            }
        });
    }

    // Обработчики для кнопок "предыдущий месяц" и "следующий месяц"
    document.getElementById('prev-month').addEventListener('click', function() {
        visibleMonthIndex -= 1;
        renderMonths();
        renderDays();
    });

    document.getElementById('next-month').addEventListener('click', function() {
        visibleMonthIndex += 1;
        renderMonths();
        renderDays();
    });

    // Инициализация календаря и загрузка всех событий
    renderMonths();
    renderDays();
    loadAllEvents(); // Загрузка всех событий при первой загрузке страницы
});
