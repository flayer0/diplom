<?php
require_once 'connect.php';

$title = $_POST['title'];

$stmt = $pdo->prepare('INSERT INTO `organizers`(`title`) VALUES (:title)');
$stmt->bindParam(':title', $title);
$stmt->execute();

header('Location: ../admin/addOrganizer.php');

