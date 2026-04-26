<?php requireRole('admin'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cours</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include BASE_PATH . 'views/admin/navbar.php'; ?>
<div class="container mt-4">

    <?php $f = getFlash(); if ($f): ?>
        <div class="alert alert-<?= $f['type'] ?>"><?= $f['msg'] ?></div>
    <?php endif; ?>

    <h3>Gestion des Cours</h3>

    <form method="POST" action="?page=admin.courses" class="row g-2 mt-2">
        <input type="hidden" name="action" value="save">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Nom du cours" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="credits" class="form-control" placeholder="Crédits" min="1" required>
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
            <button class="btn btn-primary">Ajouter</button>
        </div>
    </form>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Crédits</th>
                <th>Semestre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><?= $c['credits'] ?></td>
                <td><?= htmlspecialchars($c['semester_label']) ?> — <?= htmlspecialchars($c['academic_year']) ?></td>
                <td>
                    <form method="POST" action="?page=admin.courses" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
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