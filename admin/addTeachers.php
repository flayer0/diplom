<?php
$title = 'Преподаватели';
$headerName = 'добавитьПреподавателя';
require_once 'header.php';
require_once 'headerAdditionally.php';

$degrees = $pdo->query("SELECT * FROM academic_degrees")->fetchAll();
$disciplines = $pdo->query("SELECT * FROM disciplines")->fetchAll();
$jobs = $pdo->query("SELECT * FROM job")->fetchAll();
$categories = $pdo->query("SELECT * FROM qualification_categories")->fetchAll();
?>

<div class="shadow containerr">
    <div class="boxx">
        <form method="POST" action="../scripts/addTeachers.php" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Фамилия</label>
                <input type="text" class="form-control" name="surname" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Имя</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Отчество</label>
                <input type="text" class="form-control" name="patronymic">
            </div>
            <div class="col-md-6">
                <label class="form-label">Академическая степень</label>
                <select name="academic_degree" class="form-select">
                    <option value="">Не выбрано</option>
                    <?php foreach ($degrees as $degree): ?>
                        <option value="<?= $degree['id'] ?>"><?= $degree['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Дисциплины</label>
                <select name="disciplines[]" class="form-select" multiple>
                    <?php foreach ($disciplines as $discipline): ?>
                        <option value="<?= $discipline['id'] ?>"><?= $discipline['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Должность</label>
                <select name="job" class="form-select">
                    <option value="">Не выбрано</option>
                    <?php foreach ($jobs as $job): ?>
                        <option value="<?= $job['id'] ?>"><?= $job['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Квалификационная категория</label>
                <select name="category" class="form-select">
                    <option value="">Не выбрано</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary px-4">Создать</button>
            </div>
        </form>
    </div>
</div>

</main>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>