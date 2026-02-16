<?php
require_once 'connect.php';

$teacher_id = $_POST['teacher_id'];
$course_id = $_POST['course_id'];
$file = $_FILES['certificate'];

$fileName = $file['name'];
$tmp_name = $file['tmp_name'];

$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$newFileName = 'certificate_' . $teacher_id . '_' . $course_id . '_' . time() . '.' . $fileExt;

$destination = '../certificates/' . $newFileName;
$stmt = $pdo->prepare('INSERT INTO `certificates`(`title`, `teacher_id`, `cours_id`) VALUES (:title, :teacher, :cours)');
$stmt->execute([
    ':title' => $newFileName,
    ':teacher' => $teacher_id,
    ':cours' => $course_id
]);
if (move_uploaded_file($tmp_name, $destination)) {
    header("Location: ../admin/cours.php?id=$course_id");
} else {
    echo "Ошибка при загрузке файла";
}
?>