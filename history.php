<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique GPA</title>
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="?page=student.dashboard">Étudiant</a>
        <div class="navbar-nav flex-row gap-3">
            <a class="nav-link text-white" href="?page=student.dashboard">Dashboard</a>
            <a class="nav-link text-white" href="?page=student.history">Historique</a>
            <a class="nav-link text-danger" href="?page=logout">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Historique GPA</h3>
    <a href="api/gpa.php?action=export" class="btn btn-success mb-3">
        Exporter CSV
    </a>
    <div id="history">Chargement...</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.get('api/gpa.php', { action: 'history' }, function(semesters) {
        var html = '';
        $.each(semesters, function(i, sem) {
            html += '<div class="card mb-4"><div class="card-header">'
                  + '<strong>' + sem.label + ' — ' + sem.academic_year + '</strong>';
            if (sem.gpa !== null) {
                html += ' &nbsp; GPA: <strong>' + sem.gpa + '</strong>';
            }
            html += '</div><div class="card-body">'
                  + '<table class="table table-sm table-bordered">'
                  + '<thead><tr><th>Cours</th><th>Crédits</th>'
                  + '<th>Note</th><th>Points</th></tr></thead><tbody>';
            $.each(sem.courses, function(j, c) {
                var note = c.grade !== null ? c.grade : 
                    '<span class="text-muted">En attente</span>';
                html += '<tr><td>' + c.name + '</td><td>' + c.credits 
                      + '</td><td>' + note + '</td><td>' 
                      + c.grade_points + '</td></tr>';
            });
            html += '</tbody></table></div></div>';
        });
        $('#history').html(html || '<p>Aucun semestre trouvé.</p>');
    }, 'json');
});
</script>
</body>
</html>