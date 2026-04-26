<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie des Notes</title>
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="?page=professor.grades">Professor</a>
        <span class="navbar-text text-white">
            <?= htmlspecialchars($_SESSION['name']) ?>
        </span>
        <a href="?page=logout" class="btn btn-outline-danger btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Saisie des Notes</h3>
    
    <div id="feedback"></div>
    
    <div class="row g-3 mt-2">
        <!-- Dropdown Semestre -->
        <div class="col-md-4">
            <label class="form-label">Semestre</label>
            <select id="semesterSelect" class="form-select">
                <option value="">-- Choisir semestre --</option>
                <?php foreach ($semesters as $s): ?>
                    <option value="<?= $s['id'] ?>">
                      <?= htmlspecialchars($s['label']) ?>
                        — <?= htmlspecialchars($s['academic_year']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Dropdown Cours -->
        <div class="col-md-4">
            <label class="form-label">Cours</label>
            <select id="courseSelect" class="form-select" disabled>
                <option value="">-- Choisir cours --</option>
            </select>
        </div>
    </div>

    <!-- Table étudiants -->
    <table class="table table-bordered mt-4" id="gradeTable" style="display:none;">
        <thead class="table-dark">
            <tr>
                <th>Étudiant</th>
                <th>ID</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
    <button id="saveBtn" class="btn btn-success mt-2" style="display:none;">
        Enregistrer les notes
    </button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="public/js/professor.js"></script>
</body>
</html>