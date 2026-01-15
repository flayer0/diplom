<?php
try {
    $dsn = 'mysql:host=localhost;dbname=diplom;charset=utf8';
    $pdo = new PDO(
        $dsn,
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
