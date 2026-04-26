<?php requireRole('admin'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Professeurs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include BASE_PATH . 'views/admin/navbar.php'; ?>
<div class="container mt-4">

    <?php $f = getFlash(); if ($f): ?>
        <div class="alert alert-<?= $f['type'] ?>"><?= $f['msg'] ?></div>
    <?php endif; ?>

    <h3>Gestion des Professeurs</h3>

    <form method="POST" action="?page=admin.professors" class="row g-2 mt-2">
        <input type="hidden" name="action" value="save">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Nom" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-md-3">
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Ajouter</button>
        </div>
    </form>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr><th>Nom</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($professors as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td>
                    <form method="POST" action="?page=admin.professors" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
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