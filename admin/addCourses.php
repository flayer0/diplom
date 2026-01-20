<?php
$title = 'Курсы';
$headerName = 'добавитьКурс';
require_once 'header.php';
require_once 'headerAdditionally.php';

?>

            <div class=" shadow containerr">
                    <div class="boxx">
                        <form class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Название</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Организация</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Категория</label>
                                <select id="inputState" class="form-select">
                                    <option selected>Choose...</option>
                                    <option>...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Количество часов</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата начала</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата завершения</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label>Comments</label>
                                <textarea class="form-control" placeholder=""></textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">Создать</button>
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