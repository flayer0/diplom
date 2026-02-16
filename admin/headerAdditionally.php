<main class="content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 top-panel">
        <div class="nav">
            <?php if ($title == 'Курсы'): ?>
                <a class="nav-link <?= $headerName == 'Курсы' ? 'text-activee' : 'text-black' ?>" href="courses.php">Обзор</a>
                <a class="nav-link <?= $headerName == 'добавитьКурс' ? 'text-activee' : 'text-black' ?>" href="addCourses.php">Добавить</a>
                <a class="nav-link <?= $headerName == 'добавитьКатегорию' ? 'text-activee' : 'text-black' ?>" href="addCategory.php">Категории</a>
                <a class="nav-link <?= $headerName == 'добавитьОрганизатора' ? 'text-activee' : 'text-black' ?>" href="addOrganizer.php">Организаторы</a>
            <?php else: ?>
                <a class="nav-link <?= $headerName == 'Преподаватели' ? 'text-activee' : 'text-black' ?>" href="teachers.php">Обзор</a>
                <a class="nav-link <?= $headerName == 'добавитьПреподавателя' ? 'text-activee' : 'text-black' ?>" href="addTeachers.php">Добавить</a>
            <?php endif; ?>
        </div>
        <div class="input-group search-i" style="width: 260px;">
            <span class="input-group-text bg-white search-input"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control search-input" placeholder="Поиск">
        </div>
    </div>