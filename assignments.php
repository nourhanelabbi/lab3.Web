<?php requireRole('admin'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Assignations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include BASE_PATH . 'views/admin/navbar.php'; ?>
<div class="container mt-4">

    <?php $f = getFlash(); if ($f): ?>
        <div class="alert alert-<?= $f['type'] ?>"><?= $f['msg'] ?></div>
    <?php endif; ?>

    <h3>Assignation Professeur → Cours</h3>

    <form method="POST" action="?page=admin.assignments" class="row g-2 mt-2">
        <input type="hidden" name="action" value="save">
        <div class="col-md-3">
            <select name="professor_id" class="form-select" required>
                <option value="">-- Professeur --</option>
                <?php foreach ($professors as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="course_id" class="form-select" required>
                <option value="">-- Cours --</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="semester_id" class="form-select" required>
                <option value="">-- Semestre --</option>
                <?php foreach ($semesters as $s): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= htmlspecialchars($s['label']) ?> — <?= htmlspecialchars($s['academic_year']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Assigner</button>
        </div>
    </form>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr><th>Professeur</th><th>Cours</th><th>Semestre</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($assignments as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['prof_name']) ?></td>
                <td><?= htmlspecialchars($a['course_name']) ?></td>
                <td><?= htmlspecialchars($a['semester_label']) ?> — <?= htmlspecialchars($a['academic_year']) ?></td>
                <td>
                    <form method="POST" action="?page=admin.assignments" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Supprimer?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>