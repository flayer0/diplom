<?php
$title = 'Курсы';
$headerName = 'добавитьКурс';
require_once 'header.php';
require_once 'headerAdditionally.php';

$categories = $pdo->query('SELECT * FROM `courses_categories`');
$types = $pdo->query('SELECT * FROM `types`');
$organizers = $pdo->query('SELECT * FROM `organizers`');
?>

            <div class=" shadow containerr">
                    <div class="boxx">
                        <form class="row g-3" method="POST" action="../scripts/addCours.php">
                            <div class="col-md-6">
                                <label class="form-label">Название</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Организация</label>
                                <select id="inputState" class="form-select" name="organizer">
                                    <option selected disabled hidden>Выберите организацию</option>
                                    <?foreach($organizers as $organizer):?>
                                        <option value="<?= $organizer['id'] ?>"><?= $organizer['title'] ?></option>
                                    <?endforeach;?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Категория</label>
                                <select id="inputState" class="form-select" name="category">
                                    <option selected disabled hidden>Выберите категорию</option>
                                    <?foreach($categories as $category):?>
                                        <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                    <?endforeach;?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Тип обучения</label>
                                <select id="inputState" class="form-select" name="type">
                                    <option selected disabled hidden>Выберите тип</option>
                                    <?foreach($types as $type):?>
                                        <option value="<?= $type['id'] ?>"><?= $type['title'] ?></option>
                                    <?endforeach;?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Дата начала</label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Дата завершения</label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Количество часов</label>
                                <input type="text" class="form-control" name="hours">
                            </div>
                            <div class="col-12 d-flex justify-content-end pt-4">
                                <button type="submit" class="btn btn-primarys px-4">Создать</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </main>

    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>