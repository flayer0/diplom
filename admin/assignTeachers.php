<?php
$title = 'Назначение на курсы';
$headerName = 'Назначение на курсы';
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $teacher_ids = isset($_POST['teacher_ids']) ? (array)$_POST['teacher_ids'] : [];
    $course_id  = (int)$_POST['course_id'];

    foreach ($teacher_ids as $tid) {
        $tid = (int)$tid;
        if ($_POST['action'] === 'assign') {
            $pdo->prepare("INSERT IGNORE INTO course_teachers (teacher_id, cours_id) VALUES (?, ?)")
                ->execute([$tid, $course_id]);
        } elseif ($_POST['action'] === 'remove') {
            $pdo->prepare("DELETE FROM course_teachers WHERE teacher_id = ? AND cours_id = ?")
                ->execute([$tid, $course_id]);
        }
    }
    
    header('Location: assignTeachers.php?' . http_build_query($_GET));
    exit;
}

$all_disciplines = $pdo->query("SELECT * FROM disciplines ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);

$selected_discipline = isset($_GET['discipline_id']) ? (int)$_GET['discipline_id'] : 0;
if ($selected_discipline > 0) {
    $stmt = $pdo->prepare("
        SELECT t.id, t.surname, t.name, t.patronymic 
        FROM teachers t
        JOIN teacher_disciplines td ON t.id = td.teacher_id
        WHERE td.disciplines_id = ?
        ORDER BY t.surname
    ");
    $stmt->execute([$selected_discipline]);
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $teachers = $pdo->query("SELECT id, surname, name, patronymic FROM teachers ORDER BY surname")->fetchAll(PDO::FETCH_ASSOC);
}

$courses = $pdo->query("
    SELECT c.*, o.title as organizer 
    FROM courses c
    JOIN organizers o ON c.organizer_id = o.id
    WHERE c.end_date >= CURRENT_DATE 
    ORDER BY c.start_date ASC
")->fetchAll(PDO::FETCH_ASSOC);

$selected_teachers = isset($_GET['teacher_ids']) ? (array)$_GET['teacher_ids'] : [];
?>

<div class="p-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Дисциплина:</label>
                    <select name="discipline_id" class="form-select" onchange="this.form.submit()">
                        <option value="0">Все дисциплины</option>
                        <?php foreach ($all_disciplines as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $selected_discipline == $d['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Преподаватели:</label>
                        <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" onclick="selectAllVisible()">
                            <i class="bi bi-check2-all"></i> Выбрать всех в этой категории
                        </button>
                    </div>
                    
                    <div id="teachersContainer" class="d-flex flex-wrap gap-2">
                        <?php foreach ($teachers as $t): 
                            $fio = htmlspecialchars($t['surname'] . ' ' . mb_substr($t['name'], 0, 1) . '. ' . mb_substr($t['patronymic'], 0, 1) . '.');
                            $isChecked = in_array($t['id'], $selected_teachers);
                        ?>
                            <div class="teacher-item">
                                <input type="checkbox" name="teacher_ids[]" value="<?= $t['id'] ?>" 
                                       class="btn-check teacher-checkbox" id="t_<?= $t['id'] ?>" 
                                       <?= $isChecked ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="btn btn-sm <?= $isChecked ? 'btn-dark' : 'btn-outline-secondary' ?>" for="t_<?= $t['id'] ?>">
                                    <?= $fio ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($selected_teachers)): ?>
        <div class="alert alert-info">Выберите преподавателей выше, чтобы назначить их на курсы.</div>
    <?php else: ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Активные курсы</h5>
                <span class="badge bg-light text-dark">Выбрано: <?= count($selected_teachers) ?></span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Курс</th>
                            <th>Период</th>
                            <th class="text-end">Действие для группы</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $c): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($c['title']) ?></strong></td>
                                <td><?= date('d.m.Y', strtotime($c['start_date'])) ?> — <?= date('d.m.Y', strtotime($c['end_date'])) ?></td>
                                <td class="text-end">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="course_id" value="<?= $c['id'] ?>">
                                        <?php foreach ($selected_teachers as $st_id): ?>
                                            <input type="hidden" name="teacher_ids[]" value="<?= (int)$st_id ?>">
                                        <?php endforeach; ?>
                                        <button type="submit" name="action" value="assign" class="btn btn-sm btn-success">Назначить всех</button>
                                        <button type="submit" name="action" value="remove" class="btn btn-sm btn-outline-danger" onclick="return confirm('Снять всех?')">Снять всех</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>

function selectAllVisible() {
    const checkboxes = document.querySelectorAll('.teacher-checkbox');
    let changed = false;
    
    checkboxes.forEach(cb => {
        if (!cb.checked) {
            cb.checked = true;
            changed = true;
        }
    });

    if (changed) {
        document.getElementById('filterForm').submit();
    }
}
</script>