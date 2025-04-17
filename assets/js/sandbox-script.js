document.addEventListener('DOMContentLoaded', function () {
    const viewSwitcher = document.getElementById('viewSwitcher');
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const tabContent = document.querySelector('.tab-content');

    // Функция для установки активного таба
    function setActiveTab(view) {
        if (view === 'list') {
            listView.classList.add('active');
            gridView.classList.remove('active');
            document.getElementById('Course').classList.remove('show', 'active');
            document.getElementById('Curriculum').classList.add('show', 'active');
        } else {
            gridView.classList.add('active');
            listView.classList.remove('active');
            document.getElementById('Curriculum').classList.remove('show', 'active');
            document.getElementById('Course').classList.add('show', 'active');
        }
    }

    // Чтение сохраненного вида
    const savedView = localStorage.getItem('productView') || 'grid';
    setActiveTab(savedView);

    // Обработка кликов по кнопкам
    viewSwitcher.addEventListener('click', function (e) {
        if (e.target.closest('a')) {
            const view = e.target.closest('a').id === 'list-view' ? 'list' : 'grid';
            setActiveTab(view);
            localStorage.setItem('productView', view);
        }
    });
});
