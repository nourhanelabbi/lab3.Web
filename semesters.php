<?php requireRole('admin'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Semestres</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include BASE_PATH . 'views/admin/navbar.php'; ?>
<div class="container mt-4">

    <?php $f = getFlash(); if ($f): ?>
        <div class="alert alert-<?= $f['type'] ?>"><?= $f['msg'] ?></div>
    <?php endif; ?>

    <h3>Gestion des Semestres</h3>

    <!-- Formulaire ajout -->
    <form method="POST" action="?page=admin.semesters" class="row g-2 mt-2">
        <input type="hidden" name="action" value="save">
        <div class="col-md-3">
            <input type="text" name="label" class="form-control" placeholder="Label (ex: S1)" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="academic_year" class="form-control" placeholder="Année (ex: 2024/2025)" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Ajouter</button>
        </div>
    </form>

    <!-- Liste -->
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Label</th>
                <th>Année</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($semesters as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['label']) ?></td>
                <td><?= htmlspecialchars($s['academic_year']) ?></td>
                <td>
                    <?php if ($s['is_active']): ?>
                        <span class="badge bg-success">Actif</span>
                    <?php else: ?>
                        <form method="POST" action="?page=admin.semesters" class="d-inline">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <button class="btn btn-sm btn-outline-success">Activer</button>
                        </form>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" action="?page=admin.semesters" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
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