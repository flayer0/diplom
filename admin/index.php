<?php
$title = 'Главная';
require_once 'header.php';
$reports = $pdo->query('SELECT * from reports')
?>

        <main class="content flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 top-panel">
                <h1 class="h3 mb-0">Главная</h1>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card shadow stat-card stat-card-1 h-100">
                        <div class="card-body text-center py-4">
                            <h2 class="display-4 fw-bold">28</h2>
                            <p class="mb-0">Зарегистрированных преподавателей</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow stat-card stat-card-2 h-100">
                        <div class="card-body text-center py-4">
                            <h2 class="display-4 fw-bold">28</h2>
                            <p class="mb-0">Курсов</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow stat-card stat-card-3 h-100">
                        <div class="card-body text-center py-4">
                            <h2 class="display-4 fw-bold">28</h2>
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
                                <?foreach($reports as $report):?>
                                <tr>
                                    <td><?=$report['title'] ?></td>
                                    <td class="text-end"><?=$report['date']  ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?endforeach;?>
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
                        <i class="bi bi-file-earmark-plus me-2"></i>Создание нового отчета
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="reportName" class="form-label">Название отчета</label>
                                <input type="text" class="form-control" id="reportName" placeholder="Введите название отчета" required>
                            </div>
                            <div class="col-md-6">
                                <label for="reportType" class="form-label">Тип отчета</label>
                                <select class="form-select" id="reportType" required>
                                    <option value="" selected disabled>Выберите тип отчета</option>
                                    <option value="courses">Отчет по курсам</option>
                                    <option value="teachers">Отчет по преподавателям</option>
                                    <option value="progress">Отчет по прогрессу</option>
                                    <option value="attendance">Отчет по посещаемости</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Дата начала периода</label>
                                <input type="date" class="form-control" id="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">Дата окончания периода</label>
                                <input type="date" class="form-control" id="endDate" required>
                            </div>
                            <div class="col-md-12">
                                <label for="reportFormat" class="form-label">Формат отчета</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportFormat" id="formatPDF" value="pdf" checked>
                                        <label class="form-check-label" for="formatPDF">PDF</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportFormat" id="formatExcel" value="excel">
                                        <label class="form-check-label" for="formatExcel">Excel</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportFormat" id="formatWord" value="word">
                                        <label class="form-check-label" for="formatWord">Word</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="reportFilters" class="form-label">Фильтры</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="filterTeachers">
                                                <label class="form-check-label" for="filterTeachers">
                                                    По конкретным преподавателям
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="filterCourses">
                                                <label class="form-check-label" for="filterCourses">
                                                    По конкретным курсам
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="filterDates">
                                                <label class="form-check-label" for="filterDates">
                                                    Только за указанный период
                                                </label>
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
                    <button type="button" class="btn btn-primarys">
                        <i class="bi bi-file-earmark-plus me-1"></i>Создать отчет
                    </button>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    

</body>

</html>