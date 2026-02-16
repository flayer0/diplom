<?php
require_once '../vendor/autoload.php';

$title = 'Главная';
require_once 'header.php';

$reports = $pdo->query('SELECT * from reports');
$teachers = $pdo->query('SELECT COUNT(*) as total FROM teachers;');
$teachers_total = $teachers->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT COUNT(*) as total FROM courses
                    WHERE end_date > :date');
$stmt->bindParam(':date', date('Y-m-d'));
$stmt->execute();
$courses_total = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt_completed = $pdo->prepare('SELECT COUNT(*) as total FROM courses
                    WHERE end_date < :date');
$stmt_completed->bindParam(':date', date('Y-m-d'));
$stmt_completed->execute();
$courses_completed_total = $stmt_completed->fetch(PDO::FETCH_ASSOC);

// Получаем список преподавателей для фильтра
$all_teachers = $pdo->query('SELECT * FROM teachers');
$teachers_list = $all_teachers->fetchAll(PDO::FETCH_ASSOC);

// Получаем список курсов для фильтра
$all_courses = $pdo->query('SELECT id, title FROM courses ORDER BY title');
$courses_list = $all_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 top-panel">
        <h1 class="h3 mb-0">Главная</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow stat-card stat-card-1 h-100">
                <div class="card-body text-center py-4">
                    <h2 class="display-4 fw-bold"><?= $teachers_total['total'] ?></h2>
                    <p class="mb-0">Зарегистрированных преподавателей</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow stat-card stat-card-2 h-100">
                <div class="card-body text-center py-4">
                    <h2 class="display-4 fw-bold"><?= $courses_total['total'] ?></h2>
                    <p class="mb-0">Курсов</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow stat-card stat-card-3 h-100">
                <div class="card-body text-center py-4">
                    <h2 class="display-4 fw-bold"><?= $courses_completed_total['total'] ?></h2>
                    <p class="mb-0">Пройденных курсов</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Отчеты</h5>
            <button class="btn btn-primarys" data-bs-toggle="modal" data-bs-target="#newReportModal">
                <i class="bi bi-plus-circle me-2"></i>Новый отчет
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Отчет</th>
                            <th class="text-end">Дата</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= htmlspecialchars($report['title']) ?></td>
                                <td class="text-end"><?= htmlspecialchars($report['date']) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-download"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</div>

<div class="modal fade" id="newReportModal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="newReportModalLabel">
                    <i class="bi bi-file-earmark-plus me-2"></i>Новый отчет
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="periodType" class="form-label">Период отчета</label>
                            <select class="form-select" id="periodType" required>
                                <option value="" selected disabled>Выберите период</option>
                                <option value="3years">За последние 3 года</option>
                                <option value="year">За текущий год</option>
                                <option value="quarter">За текущий квартал</option>
                                <option value="custom">Произвольный период</option>
                            </select>
                        </div>

                        <!-- Блок для произвольного периода (скрыт по умолчанию) -->
                        <div class="col-12 custom-period" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Дата начала периода</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">Дата окончания периода</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Фильтры</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="filterTeachersSelect" class="form-label">По преподавателям</label>
                                            <select class="form-select" id="filterTeachersSelect" multiple>
                                                <option value="">Все преподаватели</option>
                                                <?php foreach ($teachers_list as $teacher): ?>
                                                    <option value="<?= $teacher['id'] ?>">
                                                        <?= htmlspecialchars($teacher['surname']) ?>, <?= htmlspecialchars(mb_substr($teacher['name'], 0, 1)) ?>.
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="filterCoursesSelect" class="form-label">По курсам</label>
                                            <select class="form-select" id="filterCoursesSelect" multiple>
                                                <option value="">Все курсы</option>
                                                <?php foreach ($courses_list as $course): ?>
                                                    <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Отмена
                </button>
                <button type="button" class="btn btn-primarys" id="generateReportBtn" onclick="generateReport()">
                    <i class="bi bi-file-earmark-plus me-1"></i>Создать отчет
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('periodType').addEventListener('change', function() {
        const customPeriodDiv = document.querySelector('.custom-period');
        if (this.value === 'custom') {
            customPeriodDiv.style.display = 'block';
        } else {
            customPeriodDiv.style.display = 'none';
        }
    });

    function generateReport() {
        const periodType = document.getElementById('periodType').value;
        let startDate = '';
        let endDate = '';

        if (periodType === 'custom') {
            startDate = document.getElementById('startDate').value;
            endDate = document.getElementById('endDate').value;
            if (!startDate || !endDate) {
                alert('Пожалуйста, заполните даты для произвольного периода');
                return;
            }
        }

        const selectedTeachers = Array.from(document.getElementById('filterTeachersSelect').selectedOptions)
            .map(option => option.value)
            .filter(value => value !== '');

        const selectedCourses = Array.from(document.getElementById('filterCoursesSelect').selectedOptions)
            .map(option => option.value)
            .filter(value => value !== '');

        const generateBtn = document.getElementById('generateReportBtn');
        const originalText = generateBtn.innerHTML;
        generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Создание PDF...';
        generateBtn.disabled = true;

        const formData = new FormData();
        formData.append('periodType', periodType);
        formData.append('startDate', startDate);
        formData.append('endDate', endDate);
        formData.append('teachers', JSON.stringify(selectedTeachers));
        formData.append('courses', JSON.stringify(selectedCourses));

        fetch('../scripts/generate_pdf_report.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const errorData = JSON.parse(text);
                            throw new Error(errorData.error || 'Ошибка сервера');
                        } catch (e) {
                            if (text.includes('<!DOCTYPE') || text.includes('<html')) {
                                throw new Error('Сервер вернул HTML вместо PDF');
                            }
                            throw new Error(text || 'Ошибка ' + response.status);
                        }
                    });
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/pdf')) {
                    return response.text().then(text => {
                        throw new Error('Ожидался PDF, но получен: ' + contentType);
                    });
                }

                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;

                const periodText = getPeriodTextJS(periodType, startDate, endDate);
                a.download = `Отчет_${periodText}_${new Date().toISOString().split('T')[0]}.pdf`;

                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

                const modal = bootstrap.Modal.getInstance(document.getElementById('newReportModal'));
                if (modal) {
                    modal.hide();
                }

                setTimeout(() => {
                    location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при создании отчёта: ' + error.message);
            })
            .finally(() => {
                generateBtn.innerHTML = originalText;
                generateBtn.disabled = false;
            });
    }

    function getPeriodTextJS(periodType, startDate, endDate) {
        switch (periodType) {
            case '3years':
                return '3года';
            case 'year':
                return new Date().getFullYear() + 'год';
            case 'quarter':
                return getCurrentQuarterJS() + 'квартал' + new Date().getFullYear();
            case 'custom':
                if (startDate && endDate) {
                    return formatDateJS(startDate) + '_' + formatDateJS(endDate);
                }
                return 'период';
            default:
                return 'отчет';
        }
    }

    function getCurrentQuarterJS() {
        const month = new Date().getMonth() + 1;
        if (month <= 3) return '1';
        if (month <= 6) return '2';
        if (month <= 9) return '3';
        return '4';
    }

    function formatDateJS(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>