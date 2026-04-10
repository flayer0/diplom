<?php
$title = 'Курсы';
$headerName = 'Курсы';
require_once 'header.php';
$teachers = $pdo->query('Select * from teachers');
$courses = $pdo->query('Select * from courses
                ORDER by courses.end_date DESC');
?>
<main class="content flex-grow-1 p-4">
    <div class="shadow container-teacher">
        <div class="boxx">
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
                        <? foreach ($courses as $cours): ?>
                            <? if ($cours['end_date'] > date('Y-m-d')): ?>

                                <tr>
                                    <td class="text-start"><a href="./cours.php?id=<?= $cours['id'] ?>"
                                            class="text-decoration-none text-prymarys"><?= $cours['title'] ?></a></td>
                                    <td><?= $cours['start_date'] ?></td>
                                    <td><?= $cours['end_date'] ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primarys px-4" data-bs-toggle="modal"
                                            data-bs-target="#addCoursTeacherModal">
                                            Записаться
                                        </a>
                                    </td>
                                </tr>
                            <? endif; ?>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div>
</main>

</div>
<div class="modal fade" id="addCoursTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-light">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение записи</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="" class="btn btn-danger w-100" data-bs-dismiss="addCoursTeacherModal">Отмена</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success w-100">Подтвердить</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>