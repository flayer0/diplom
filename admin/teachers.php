<?php
$title = 'Преподаватели';
$headerName = 'Преподаватели'; // Было == вместо =
require_once 'header.php';
require_once 'headerAdditionally.php';
$teachers = $pdo->query('SELECT 
        teachers.*,
        academic_degrees.title as academic_degree,
        GROUP_CONCAT(DISTINCT disciplines.title SEPARATOR ", ") as disciplines
    FROM teachers
    LEFT JOIN academic_degrees ON teachers.academic_degree_id = academic_degrees.id
    LEFT JOIN teacher_disciplines ON teachers.id = teacher_disciplines.teacher_id
    LEFT JOIN disciplines ON teacher_disciplines.disciplines_id = disciplines.id
    GROUP BY teachers.id
    ORDER BY teachers.surname, teachers.name');
$courses = $pdo->query('Select * from courses');
?>

<div class="table-responsive">
    <table class="table align-middle">
        <thead class="table-light">
            <tr>
                <th>ФИО</th>
                <th>Дисциплины</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($teachers as $teacher): ?>
                <tr>
                    <td>
                        <a href="./teacher.php?id=<?= $teacher['id'] ?>" class="text-decoration-none text-prymarys">
                            <?= $teacher['surname'] . ' ' . mb_substr($teacher['name'], 0, 1) . '. ' . mb_substr($teacher['patronymic'], 0, 1) . '.' ?>
                        </a>
                    </td>
                    <td><?= $teacher['disciplines'] ?></td>

                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</div>
</main>

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
                        <input type="text" name="fio" id="modalFio" class="form-control" disabled>
                        <input type="hidden" name="teacher_id" id="modalTeacherId">
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom04" class="form-label">Курсы</label>
                        <select class="form-select" name="course_id" id="validationCustom04" required>
                            <option selected disabled value="">Выберите...</option>
                            <? foreach ($courses as $cours): ?>
                                <option value="<?= $cours['id'] ?>"><?= $cours['title'] ?></option>
                            <? endforeach; ?>
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
<script>
    const modal = document.getElementById('addTeacherCourseModal');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('modalFio').value = button.getAttribute('data-fio');
        document.getElementById('modalTeacherId').value = button.getAttribute('data-id');
    });
</script>

</body>
</html>