<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once 'connect.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function sendError($message)
{
    http_response_code(400);
    echo json_encode(['error' => $message]);
    exit;
}

$periodType = $_POST['periodType'] ?? '3years';
$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';
$selectedTeachers = json_decode($_POST['teachers'] ?? '[]', true);
$selectedCourses = json_decode($_POST['courses'] ?? '[]', true);

if (empty($periodType)) {
    sendError('Не указан тип периода');
}

try {
    $sql = "
    SELECT 
        t.id,
        t.surname,
        t.name,
        t.patronymic,
        GROUP_CONCAT(DISTINCT CONCAT(j.title, ' (', COALESCE(qc.title, 'нет категории'), ')') SEPARATOR ', ') as job_info,
        GROUP_CONCAT(DISTINCT d.title SEPARATOR ', ') as disciplines,
        MAX(ad.title) as academic_degree,
        GROUP_CONCAT(DISTINCT c.title SEPARATOR '; ') as courses_completed,
        (SELECT COUNT(*) FROM course_teachers tc WHERE tc.teacher_id = t.id) as courses_count
    FROM teachers t
    LEFT JOIN teacher_job_category tjc ON t.id = tjc.teacher_id
    LEFT JOIN job j ON tjc.job_id = j.id
    LEFT JOIN qualification_categories qc ON tjc.category_id = qc.id
    LEFT JOIN teacher_disciplines td ON t.id = td.teacher_id
    LEFT JOIN disciplines d ON td.disciplines_id = d.id
    LEFT JOIN academic_degrees ad ON t.academic_degree_id = ad.id
    LEFT JOIN course_teachers tc ON t.id = tc.teacher_id
    LEFT JOIN courses c ON tc.cours_id = c.id
";

    $whereConditions = [];
    $params = [];

    if (!empty($selectedTeachers) && !in_array('', $selectedTeachers)) {
        $placeholders = implode(',', array_fill(0, count($selectedTeachers), '?'));
        $whereConditions[] = "t.id IN ($placeholders)";
        $params = array_merge($params, $selectedTeachers);
    }

    if (!empty($selectedCourses) && !in_array('', $selectedCourses)) {
        $whereConditions[] = "c.id IN (" . implode(',', array_fill(0, count($selectedCourses), '?')) . ")";
        $params = array_merge($params, $selectedCourses);
    }

    if ($periodType === 'custom' && $startDate && $endDate) {
        $whereConditions[] = "(c.start_date >= ? AND c.end_date <= ?)";
        $params[] = $startDate;
        $params[] = $endDate;
    } elseif ($periodType === 'year') {
        $currentYear = date('Y');
        $whereConditions[] = "YEAR(c.start_date) = ?";
        $params[] = $currentYear;
    } elseif ($periodType === 'quarter') {
        $quarter = ceil(date('n') / 3);
        $startMonth = ($quarter - 1) * 3 + 1;
        $endMonth = $quarter * 3;
        $currentYear = date('Y');
        $whereConditions[] = "(YEAR(c.start_date) = ? AND MONTH(c.start_date) BETWEEN ? AND ?)";
        $params[] = $currentYear;
        $params[] = $startMonth;
        $params[] = $endMonth;
    } elseif ($periodType === '3years') {
        $threeYearsAgo = date('Y-m-d', strtotime('-3 years'));
        $whereConditions[] = "c.start_date >= ?";
        $params[] = $threeYearsAgo;
    }

    if (!empty($whereConditions)) {
        $sql .= " WHERE " . implode(' AND ', $whereConditions);
    }

    $sql .= " GROUP BY t.id, t.surname, t.name, t.patronymic ORDER BY t.surname, t.name";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($teachers)) {
        sendError('Нет данных для отчета по выбранным критериям');
    }
} catch (PDOException $e) {
    sendError('Ошибка базы данных: ' . $e->getMessage());
}

$html = generateHTMLReport($teachers, $periodType, $startDate, $endDate);

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isPhpEnabled', false);
$options->set('defaultPaperSize', 'A4');
$options->set('defaultPaperOrientation', 'landscape');

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html, 'UTF-8');

try {
    $dompdf->render();
} catch (Exception $e) {
    sendError('Ошибка при создании PDF: ' . $e->getMessage());
}

$periodText = getPeriodText($periodType, $startDate, $endDate);
$filename = 'Отчет_' . str_replace([' ', ':', '/', '\\'], '_', $periodText) . '_' . date('Y-m-d') . '.pdf';

$dompdf->stream($filename, [
    'Attachment' => true,
    'compress' => true
]);

exit;

function generateHTMLReport($teachers, $periodType, $startDate, $endDate)
{
    $periodText = getPeriodText($periodType, $startDate, $endDate);
    $currentDate = date('d.m.Y');

    $totalCourses = 0;
    $highestCat = 0;
    $firstCat = 0;
    $noCat = 0;

    foreach ($teachers as $teacher) {
        $totalCourses += $teacher['courses_count'] ?? 0;
        
        $jobInfo = $teacher['job_info'] ?? '';
        if (strpos($jobInfo, 'Высшая категория') !== false) {
            $highestCat++;
        } elseif (strpos($jobInfo, 'Первая категория') !== false) {
            $firstCat++;
        } else {
            $categoryFound = false;
            $commonCategories = ['Высшая', 'Первая', 'Вторая', 'Соответствие занимаемой должности'];
            foreach ($commonCategories as $cat) {
                if (strpos($jobInfo, $cat) !== false) {
                    $categoryFound = true;
                    break;
                }
            }
            if (!$categoryFound) {
                $noCat++;
            }
        }
    }

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Отчет по педагогическим работникам</title>
        <style>
            @font-face {
                font-family: "DejaVu Sans";
                src: url("https://cdn.jsdelivr.net/npm/dejavu-sans@2.37/fonts/DejaVuSans.ttf") format("truetype");
            }
            * {
                font-family: "DejaVu Sans", sans-serif;
                box-sizing: border-box;
            }
            body {
                font-size: 7pt;
                margin: 0;
                padding: 5px;
            }
            .header {
                text-align: center;
                margin-bottom: 10px;
                border-bottom: 2px solid #333;
                padding-bottom: 5px;
            }
            .header h1 {
                font-size: 12pt;
                margin: 2px 0;
                font-weight: bold;
            }
            .header h2 {
                font-size: 10pt;
                margin: 2px 0;
                font-weight: normal;
            }
            .header h3 {
                font-size: 9pt;
                margin: 2px 0;
                font-weight: normal;
            }
            .header p {
                margin: 1px 0;
                font-size: 8pt;
            }
            .info-block {
                margin-bottom: 8px;
                font-size: 8pt;
                padding: 3px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 5px;
                font-size: 6pt;
                table-layout: fixed;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            th, td {
                border: 1px solid #000;
                padding: 2px;
                text-align: left;
                vertical-align: top;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
                text-align: center;
                vertical-align: middle;
                padding: 1px;
            }
            .center {
                text-align: center;
            }
            .number {
                text-align: center;
                width: 20px;
            }
            .name-col {
                width: 90px;
            }
            .job-col {
                width: 100px;
            }
            .disciplines-col {
                width: 120px;
            }
            .education-col {
                width: 100px;
            }
            .degree-col {
                width: 60px;
            }
            .courses-col {
                width: 100px;
            }
            .retraining-col {
                width: 80px;
            }
            .experience-col {
                width: 50px;
                text-align: center;
            }
            .programs-col {
                width: 100px;
            }
            .footer {
                margin-top: 10px;
                font-size: 7pt;
                text-align: right;
                border-top: 1px solid #333;
                padding-top: 3px;
            }
            .summary {
                margin-top: 8px;
                font-size: 7pt;
                padding: 3px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
            }
            .summary p {
                margin: 2px 0;
            }
            th div {
                line-height: 1.1;
                padding: 1px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .table-caption {
                font-weight: bold;
                text-align: center;
                font-size: 8pt;
                margin-bottom: 3px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>ОТЧЕТ</h1>
            <h2>о педагогических работниках</h2>
            <h3>за период: ' . htmlspecialchars($periodText) . '</h3>
            <p>Дата формирования: ' . $currentDate . '</p>
            <p>Образовательное учреждение: ГПОУ "Сыктывкарский политехнический техникум"</p>
        </div>
        
        <div class="info-block">
            <p><strong>Всего преподавателей:</strong> ' . count($teachers) . '</p>
            <p><strong>Общее количество курсов повышения квалификации:</strong> ' . $totalCourses . '</p>
        </div>
        
        <div class="table-caption">Сведения о педагогических работниках</div>
        
        <table>
            <thead>
                <tr>
                    <th class="number"><div>№ п/п</div></th>
                    <th class="name-col"><div>Ф.И.О. педагогического работника</div></th>
                    <th class="job-col"><div>Занимаемая должность (должности)</div></th>
                    <th class="disciplines-col"><div>Преподаваемые учебные предметы, курсы, дисциплины (модули)</div></th>
                    <th class="education-col"><div>Уровень (уровни) профессионального образования с указанием наименования направления подготовки и (или) специальности, в том числе научной, и квалификации</div></th>
                    <th class="degree-col"><div>Учетная степень, ученое звание (при наличии)</div></th>
                    <th class="courses-col"><div>Сведения о повышении квалификации (за последние 3 года)</div></th>
                    <th class="retraining-col"><div>Сведения о профессиональной переподготовке (при наличии)</div></th>
                    <th class="experience-col"><div>Сведения о продолжительности опыта (лет) работы в профессиональной сфере, соответствующей образовательной деятельности по реализации учебных программ, курсов, дисциплин (модулей)</div></th>
                    <th class="programs-col"><div>Наименование общеобразовательной программы (общеобразовательных программ), код и наименование профессии, специальности (специальностей), направления (направлений) подготовки или укрупненной группы профессий, в реализации которых участвует педагогический работник</div></th>
                </tr>
            </thead>
            <tbody>';

    $counter = 1;

    foreach ($teachers as $teacher) {
        $fio = htmlspecialchars(trim(
            ($teacher['surname'] ?? '') . ' ' .
                ($teacher['name'] ?? '') . ' ' .
                ($teacher['patronymic'] ?? '')
        ));
        $jobInfo = htmlspecialchars($teacher['job_info'] ?? 'Должность не указана');
        $disciplines = htmlspecialchars($teacher['disciplines'] ?? 'Не указано');
        $educationInfo = htmlspecialchars('');
        $academicDegree = htmlspecialchars($teacher['academic_degree'] ?? 'Нет');
        $coursesCompleted = htmlspecialchars($teacher['courses_completed'] ?? 'Курсы не указаны');
        $retrainingInfo = htmlspecialchars('Нет');
        $workExperience = htmlspecialchars('');
        $educationalPrograms = htmlspecialchars('');

        $html .= '
                <tr>
                    <td class="number">' . $counter++ . '</td>
                    <td>' . $fio . '</td>
                    <td>' . $jobInfo . '</td>
                    <td>' . $disciplines . '</td>
                    <td>' . $educationInfo . '</td>
                    <td>' . $academicDegree . '</td>
                    <td>' . $coursesCompleted . '</td>
                    <td>' . $retrainingInfo . '</td>
                    <td class="center">' . $workExperience . '</td>
                    <td>' . $educationalPrograms . '</td>
                </tr>';
    }

    $html .= '
            </tbody>
        </table>
            
        <div class="footer">
            <p>Отчет сгенерирован автоматически. ' . date('d.m.Y H:i') . '</p>
        </div>
    </body>
    </html>';

    return $html;
}

function getPeriodText($periodType, $startDate, $endDate)
{
    switch ($periodType) {
        case '3years':
            return 'последние 3 года';
        case 'year':
            return date('Y') . ' год';
        case 'quarter':
            $quarter = ceil(date('n') / 3);
            return $quarter . ' квартал ' . date('Y') . ' года';
        case 'custom':
            if ($startDate && $endDate) {
                return date('d.m.Y', strtotime($startDate)) . ' - ' . date('d.m.Y', strtotime($endDate));
            }
            return 'указанный период';
        default:
            return 'отчетный период';
    }
}