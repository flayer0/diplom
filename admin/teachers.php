<?php
$title = 'Преподаватели';
$headerName == 'Преподаватели';
require_once 'header.php';
require_once 'headerAdditionally.php';
$teachers = $pdo->query('Select * from teachers');
$courses = $pdo->query('Select * from courses');
?>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ФИО</th>
                            <th>Дисциплины</th>
                            <th class="text-end">Курсы</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach($teachers as $teacher):?>
                        <tr>
                            <td><a href="./teacher.html" class="text-decoration-none text-prymarys">
                                <?= $teacher['surname'],' ', mb_substr($teacher['name'], 0, 1),'.', mb_substr($teacher['patronymic'], 0, 1),'.'?>
                            </a></td>
                            <td>Информационные технологии (ИТ)…</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-primarys px-4" data-bs-toggle="modal"
                                    data-bs-target="#addTeacherCourseModal">
                                    Назначить
                                </a>
                            </td>
                        </tr>
                        <?endforeach;?>
                    </tbody>
                </table>
            </div>
        </main>
        <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content  bg-light">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить преподавателя</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">ФИО</label>
                                <input type="text" name="fio" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Дисциплина</label>
                                <input type="text" name="discipline" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Начало стажа</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primarys w-100">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addTeacherCourseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h5 class="modal-title">Назначить курс</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">ФИО</label>
                                <input type="text" name="fio" class="form-control" disabled value="Клочева Е.А">
                            </div>
                            <div class="mb-3">
                                <label for="validationCustom04" class="form-label">Курсы</label>
                                <select class="form-select" id="validationCustom04" required>
                                    <option selected disabled value="">Выберите...</option>
                                    <?foreach($courses as $cours):?>
                                        <option value="/<?= $cours['id'] ?>"><?= $cours['title'] ?></option>
                                    <?endforeach;?>
                                </select>

                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primarys w-100">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>