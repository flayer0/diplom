<?php
require_once 'header.php';

$id = $_GET['id'];

// Основной запрос для получения данных преподавателя с ученой степенью
$stmt = $pdo->prepare('
    SELECT 
        teachers.*,
        academic_degrees.title as academic_degree,
        GROUP_CONCAT(DISTINCT CONCAT(job.title, " (", qualification_categories.title, ")") SEPARATOR ", ") as job_info,
        GROUP_CONCAT(DISTINCT disciplines.title SEPARATOR ", ") as disciplines
    FROM teachers
    LEFT JOIN academic_degrees ON teachers.academic_degree_id = academic_degrees.id
    LEFT JOIN teacher_job_category ON teachers.id = teacher_job_category.teacher_id
    LEFT JOIN job ON teacher_job_category.job_id = job.id
    LEFT JOIN qualification_categories ON teacher_job_category.category_id = qualification_categories.id
    LEFT JOIN teacher_disciplines ON teachers.id = teacher_disciplines.teacher_id
    LEFT JOIN disciplines ON teacher_disciplines.disciplines_id = disciplines.id
    WHERE teachers.id = :id
    GROUP BY teachers.id
');
$stmt->bindParam(':id', $id);
$stmt->execute();
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

// Запрос для получения курсов, которые проходит преподаватель
$stmt_courses = $pdo->prepare('
    SELECT 
        courses.*,
        courses_categories.title as category,
        types.title as type,
        organizers.title as organizer,
        CASE 
            WHEN courses.end_date >= CURDATE() THEN "Активно"
            ELSE "Завершено"
        END as status
    FROM courses
    JOIN teachers_courses ON courses.id = teachers_courses.cours_id
    JOIN courses_categories ON courses.category_id = courses_categories.id
    JOIN types ON courses.type_id = types.id
    JOIN organizers ON courses.organizer_id = organizers.id
    WHERE teachers_courses.teacher_id = :id
    ORDER BY courses.start_date DESC
');
$stmt_courses->bindParam(':id', $id);
$stmt_courses->execute();
$courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content flex-grow-1 p-4">
    <div class="shadow containerr">
        <div class="boxx">
            <div class="info-teacher mb-4">
                <div class="header mb-3">
                    <h3>Информация о преподавателе</h3>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <p><strong>ФИО:</strong> 
                        <?= $teacher['surname'],' ', mb_substr($teacher['name'], 0, 1),'.', mb_substr($teacher['patronymic'], 0, 1),'.'?>
                    </p>
                    <p><strong>Ученая степень:</strong> <?= $teacher['academic_degree'] ?? 'Не указана' ?></p>
                    <p><strong>Должность и категория:</strong> <?= $teacher['job_info'] ?? 'Не указана' ?></p>
                    <p><strong>Преподаваемые дисциплины:</strong> <?= $teacher['disciplines'] ?? 'Не указаны' ?></p>
                </div>
            </div>
            
            <div class="cours-teacher">
                <div class="header mb-3">
                    <h3>Курсы повышения квалификации</h3>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <?php if (empty($courses)): ?>
                        <p class="text-muted">Преподаватель не зарегистрирован на курсы</p>
                    <?php else: ?>
                        <table class="table align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Название курса</th>
                                    <th>Организатор</th>
                                    <th>Тип</th>
                                    <th>Категория</th>
                                    <th>Часы</th>
                                    <th>Период</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td class="text-start">
                                        <a href="cours.php?id=<?= $course['id'] ?>" class="text-decoration-none text-primary">
                                            <?= $course['title'] ?>
                                        </a>
                                    </td>
                                    <td><?= $course['organizer'] ?></td>
                                    <td><?= $course['type'] ?></td>
                                    <td><?= $course['category'] ?></td>
                                    <td><?= $course['number_of_hours'] ?></td>
                                    <td><?= date('d.m.Y', strtotime($course['start_date'])) ?> - <?= date('d.m.Y', strtotime($course['end_date'])) ?></td>
                                    <td>
                                        <?php if ($course['status'] == 'Активно'): ?>
                                            <div class="circle circle-success d-inline-block me-2"></div>Активно
                                        <?php else: ?>
                                            <div class="circle circle-secondary d-inline-block me-2"></div>Завершено
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($course['status'] == 'Завершено'): ?>
                                            <a href="generate_certificate.php?course_id=<?= $course['id'] ?>&teacher_id=<?= $id ?>" 
                                               class="text-black text-decoration-none" 
                                               title="Скачать сертификат">
                                                <i class="bi bi-download"></i>
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
