<?php requireRole('admin'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscriptions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include BASE_PATH . 'views/admin/navbar.php'; ?>
<div class="container mt-4">

    <?php $f = getFlash(); if ($f): ?>
        <div class="alert alert-<?= $f['type'] ?>"><?= $f['msg'] ?></div>
    <?php endif; ?>

    <h3>Inscriptions aux Semestres</h3>

    <!-- Sélection étudiant -->
    <form method="GET" action="" class="row g-2 mt-2">
        <input type="hidden" name="page" value="admin.enrollments">
        <div class="col-md-4">
            <select name="student_id" class="form-select">
                <option value="">-- Choisir étudiant --</option>
                <?php foreach ($students as $s): ?>
                    <option value="<?= $s['id'] ?>"
                        <?= ($s['id'] == $studentId) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary">Afficher</button>
        </div>
    </form>

    <!-- Checkboxes -->
    <?php if ($studentId): ?>
    <form method="POST" action="?page=admin.enrollments" class="mt-4">
        <input type="hidden" name="student_id" value="<?= $studentId ?>">
        <h5>Semestres:</h5>
        <?php foreach ($semesters as $s): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       name="semester_ids[]"
                       value="<?= $s['id'] ?>"
                       <?= in_array($s['id'], $enrolled) ? 'checked' : '' ?>>
                <label class="form-check-label">
                    <?= htmlspecialchars($s['label']) ?> — <?= htmlspecialchars($s['academic_year']) ?>
                </label>
            </div>
        <?php endforeach; ?>
        <button class="btn btn-primary mt-3">Enregistrer</button>
    </form>
    <?php endif; ?>

</div>
</body>
</html>