<?php
require_once 'connect.php';

$title = $_POST['title'];

$stmt = $pdo->prepare('INSERT INTO `courses_categories`(`title`) VALUES (:title)');
$stmt->bindParam(':title', $title);
$stmt->execute();

header('Location: ../admin/addCategory.php');

