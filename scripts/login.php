<?php
require_once 'connect.php';

$login = $_POST['login'];
$password = $_POST['password'];

$stmt = $pdo->prepare('SELECT * FROM `teachers` WHERE login = :login');
$stmt->bindParam(':login', $login);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && ($password === $user['password'])) {
    session_start();
    $_SESSION['user'] = $user;
    
    $checkMethodistStmt = $pdo->prepare('
        SELECT COUNT(*) as is_methodist 
        FROM teacher_job_category 
        WHERE teacher_id = :teacher_id AND job_id = 1
    ');
    $checkMethodistStmt->bindParam(':teacher_id', $user['id']);
    $checkMethodistStmt->execute();
    $methodistResult = $checkMethodistStmt->fetch(PDO::FETCH_ASSOC);
    
    $_SESSION['user']['is_methodist'] = ($methodistResult['is_methodist'] > 0);
    
    $positionsStmt = $pdo->prepare('
        SELECT j.title 
        FROM teacher_job_category tjc
        JOIN job j ON tjc.job_id = j.id
        WHERE tjc.teacher_id = :teacher_id
    ');
    $positionsStmt->bindParam(':teacher_id', $user['id']);
    $positionsStmt->execute();
    $positions = $positionsStmt->fetchAll(PDO::FETCH_COLUMN);
    $_SESSION['user']['positions'] = $positions;
    
    if ($_SESSION['user']['is_methodist']) {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../ff.php');
    }
    exit();
} else {
    header('Location: login.php?error=1');
    exit();
}