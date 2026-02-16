<?php
require_once 'header.php';

$id = $_GET['id'];

// Запрос для получения информации о курсе
$stmt_course = $pdo->prepare('
    SELECT 
        courses.*,
        courses_categories.title as category,
        types.title as type,
        organizers.title as organizer
    FROM courses
    JOIN courses_categories ON courses.category_id = courses_categories.id
    JOIN types ON courses.type_id = types.id
    JOIN organizers ON courses.organizer_id = organizers.id
    WHERE courses.id = :id
');
$stmt_course->bindParam(':id', $id);
$stmt_course->execute();
$course = $stmt_course->fetch(PDO::FETCH_ASSOC);

// Запрос для получения преподавателей, проходящих этот курс
$stmt_teachers = $pdo->prepare('
    SELECT 
        teachers.id,
        teachers.surname,
        teachers.name,
        teachers.patronymic
    FROM teachers
    JOIN course_teachers ON teachers.id = course_teachers.teacher_id
    WHERE course_teachers.cours_id = :id
    ORDER BY teachers.surname, teachers.name
');
$stmt_teachers->bindParam(':id', $id);
$stmt_teachers->execute();
$teachers = $stmt_teachers->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content flex-grow-1 p-4">
    <div class="shadow container-teacher">
        <div class="boxx">
            <div class="info-teacher mb-4">
                <div class="header mb-3">
                    <h3>Информация о курсе</h3>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <p><strong>Название: </strong><?= $course['title'] ?></p>
                    <p><strong>Организация: </strong><?= $course['organizer'] ?></p>
                    <p><strong>Даты начала и завершения: </strong><?= date('d.m.Y', strtotime($course['start_date'])) ?>-<?= date('d.m.Y', strtotime($course['end_date'])) ?></p>
                    <p><strong>Категория курса: </strong><?= $course['category'] ?></p>
                    <p><strong>Тип обучения: </strong><?= $course['type'] ?></p>
                    <p><strong>Количество часов: </strong><?= $course['hours'] ?></p>
                </div>
            </div>
            <div class="cours-teacher">
                <div class="header mb-3">
                    <h3>Преподаватели на курсе</h3>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <?php if (empty($teachers)): ?>
                        <p class="text-muted">На этом курсе нет зарегистрированных преподавателей</p>
                    <?php else: ?>
                        <table class="table align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>ФИО</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $teacher): 
                                    $stmt_certificate = $pdo->prepare('SELECT * FROM certificates WHERE teacher_id = :teacher_id AND cours_id = :cours_id');
                                    $stmt_certificate->bindParam(':teacher_id', $teacher['id']);
                                    $stmt_certificate->bindParam(':cours_id', $id);
                                    $stmt_certificate->execute();
                                    $certificate = $stmt_certificate->fetch(PDO::FETCH_ASSOC);
                                    $hasCertificate = !empty($certificate);
                                ?>
                                    <tr>
                                        <td><?= $teacher['id'] ?></td>
                                        <td>
                                            <a href="teacher.php?id=<?= $teacher['id'] ?>"
                                                class="text-prymarys text-decoration-none me-2">                                           
                                            <?= $teacher['surname'] . ' ' .
                                                mb_substr($teacher['name'], 0, 1) . '.' .
                                                ($teacher['patronymic'] ? mb_substr($teacher['patronymic'], 0, 1) . '.' : '') ?>
                                            </a>
                                        </td>
                                        <td>
                                            
                                            
                                            <?php if ($hasCertificate): ?>
                                                <a href="../certificates/<?= $certificate['title'] ?>"
                                                   class="text-black text-decoration-none me-2"
                                                   title="Скачать сертификат"
                                                   download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="#"
                                                    class="text-black text-decoration-none me-2 add-certificate-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addTeacherCourseModal"
                                                    data-teacher-id="<?= $teacher['id'] ?>"
                                                    data-course-id="<?= $id ?>"
                                                    title="Добавить сертификат">
                                                    <i class="bi bi-file-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Модальное окно для добавления сертификата -->
<div class="modal fade" id="addTeacherCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-light">
            <div class="modal-header">
                <h5 class="modal-title">Добавить сертификат</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="../scripts/addCertificate.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="teacher_id" id="modalTeacherId">
                    <input type="hidden" name="course_id" id="modalCourseId">
                    <div class="mb-3">
                        <label class="form-label">Сертификат</label>
                        <input type="file" name="certificate" class="form-control" required
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../scripts/js/main.js"></script>
</body>
</html>