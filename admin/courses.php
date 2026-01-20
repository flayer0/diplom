<?php
$title = 'Курсы';
$headerName = 'Курсы';
require_once 'header.php';
require_once 'headerAdditionally.php';
$teachers = $pdo->query('Select * from teachers');
$courses = $pdo->query('Select * from courses');
?>

            <div class="table-responsive">
                <table class="table align-middle ms-3 text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Название</th>
                            <th>Дата начала</th>
                            <th>Дата завершения</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach($courses as $cours):?>
                        <tr>
                            <td class="text-start"><a href="./cours.html"
                                    class="text-decoration-none text-prymarys"><?= $cours['title'] ?></a></td>
                            <td><?= $cours['start_date'] ?></td>
                            <td><?= $cours['end_date'] ?></td>
                            <td>
                                <a href="#" class="btn btn-primarys px-4" data-bs-toggle="modal"
                                    data-bs-target="#addCoursTeacherModal">
                                    Назначить
                                </a>
                            </td>
                        </tr>
                        <?endforeach;?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>
    <div class="modal fade" id="addCoursTeacherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title">Назначить курс</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Курс</label>
                            <input type="text" name="fio" class="form-control" disabled
                                value="Проф подготовка к демо-экзамену">
                        </div>
                        <div class="mb-3">
                            <label for="validationCustom04" class="form-label">Курсы</label>
                            <select class="select2 course-select" name="tag_ids[]" multiple="multiple"
                                data-placeholder="Выберите теги" style="width: 100%; z-index: 999;">
                                <option>uu</option>
                                <option>udu</option>
                                <option>usu</option>
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2({
            dropdownParent: $('#addCoursTeacherModal')
        });
    </script>


</body>

</html>