<?php
require_once '../scripts/connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body class="bg-light">
    <nav class="navbar  d-lg-none px-3">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <span class="navbar-toggler-icon "></span>
        </button>
    </nav>
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Панель</h5>
            <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="d-flex align-items-center mb-4">
                <img src="../img/profile.png" class="rounded-circle me-3" width="50" height="50">
                <div class="fw-semibold"><?=$_SESSION['user']['surname'],' ', $_SESSION['user']['name']?></div>
            </div>
            <ul class="nav nav-pills flex-column gap-2">
                <li><a class="nav-link text-white <?php if($title == 'Главная'){echo 'activee';}?>" href="index.php"><i class="bi bi-house me-2"></i>Главная</a></li>
                <li><a class="nav-link text-white <?php if($title == 'Преподаватели'){echo 'activee';}?>" href="teachers.php"><i class="bi bi-person me-2"></i>Преподаватели</a></li>
                <li><a class="nav-link text-white <?php if($title == 'Курсы'){echo 'activee';}?>" href="courses.php"><i class="bi bi-journals me-2"></i>Курсы</a></li>
            </ul>
        </div>
    </div>
    <div class="d-flex">
        <aside class="sidebar bg-dark text-white p-4 d-none d-lg-block">
            <div class="d-flex align-items-center mb-4">
                <img src="../img/profile.png" class="rounded-circle me-3" width="50" height="50">
                <div class="fw-semibold"><?=$_SESSION['user']['surname'],' ', $_SESSION['user']['name']?></div>
            </div>
            <ul class="nav nav-pills flex-column gap-2">
                <li><a class="nav-link text-white <?php if($title == 'Главная'){echo 'activee';} ?>" href="index.php"><i class="bi bi-house me-2"></i>Главная</a></li>
                <li><a class="nav-link text-white <?php if($title == 'Преподаватели'){echo 'activee';}?>" href="teachers.php"><i class="bi bi-person me-2"></i>Преподаватели</a></li>
                <li><a class="nav-link text-white <?php if($title == 'Курсы'){echo 'activee';}?>" href="courses.php"><i class="bi bi-journals me-2"></i>Курсы</a></li>
            </ul>
        </aside>