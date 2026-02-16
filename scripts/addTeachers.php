<?php
require_once 'connect.php';

$login = $_POST['surname'] . substr($_POST['name'], 0, 2);

$stmtTeacher = $pdo->prepare("
    INSERT INTO teachers (surname, name, patronymic, academic_degree_id, login, password) 
    VALUES (:surname, :name, :patronymic, :academic_degree_id, :login, :password)
");

$stmtTeacher->execute([
    ':surname' => $_POST['surname'],
    ':name' => $_POST['name'],
    ':patronymic' => $_POST['patronymic'],
    ':academic_degree_id' => $_POST['academic_degree'],
    ':login' => $login,
    ':password' => '1234'
]);

$teacherId = $pdo->lastInsertId();

foreach ($_POST['disciplines'] as $disciplineId) {
    $stmt = $pdo->prepare("INSERT INTO teacher_disciplines (teacher_id, disciplines_id) VALUES (?, ?)");
    $stmt->execute([$teacherId, $disciplineId]);
}

$stmt = $pdo->prepare("INSERT INTO teacher_job_category (teacher_id, job_id, category_id) VALUES (?, ?, ?)");
$stmt->execute([$teacherId, $_POST['job'], $_POST['category']]);


header('Location: ../admin/addTeachers.php');
