<?php
require_once 'connect.php';

$title = $_POST['title'];
$organizer = $_POST['organizer'];
$category = $_POST['category'];
$type = $_POST['type'];
$hours = $_POST['hours'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$stmt = $pdo->prepare('INSERT INTO `courses`(`organizer_id`, `title`, `category_id`, `type_id`, `hours`, `start_date`, `end_date`) VALUES (:organizer, :title, :category, :type, :hours, :start, :end)');

$data = [
    ':organizer' => $organizer,
    ':title' => $title,
    ':category' => $category,
    ':type' => $type,
    ':hours' => $hours,
    ':start' => $start_date,
    ':end' => $end_date
];

$stmt->execute($data);

header('Location: ../admin/addCourses.php');
