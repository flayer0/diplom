<?php
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Test PDF</h1><p>Если это работает, значит dompdf настроен правильно.</p>');
$dompdf->render();
$dompdf->stream('test.pdf');
?>