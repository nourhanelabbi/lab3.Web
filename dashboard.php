<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Notes</title>
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
    <h3>Bonjour, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h3>
    
    <div id="content">
        <p>Chargement...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.get('api/gpa.php', { action: 'current' }, function(data) {
        if (data.error) {
            $('#content').html(
                '<div class="alert alert-warning">' + data.error + '</div>'
            );
            return;
        }
        
        // GPA color
        var gpa  = data.gpa;
        var cls  = 'alert-danger';
        var text = 'Échec';
        if (gpa >= 3.7)      { cls = 'alert-success'; text = 'Distinction'; }
        else if (gpa >= 3.0) { cls = 'alert-info';    text = 'Mérite'; }
        else if (gpa >= 2.0) { cls = 'alert-warning'; text = 'Passable'; }
        
        var html = '<h5>Semestre: ' + data.semester.label 
                 + ' — ' + data.semester.academic_year + '</h5>';
        
        // GPA badge
        if (gpa !== null) {
            html += '<div class="alert ' + cls + '">GPA: <strong>' 
                  + gpa + '</strong> — ' + text + '</div>';
        }
        
        // Table
        html += '<table class="table table-bordered">'
              + '<thead class="table-dark"><tr>'
              + '<th>Cours</th><th>Crédits</th><th>Note</th><th>Points</th>'
              + '</tr></thead><tbody>';
        
        $.each(data.courses, function(i, c) {
            var note = c.grade !== null ? c.grade : 
                '<span class="text-muted">En attente</span>';
            html += '<tr>'
                  + '<td>' + c.name + '</td>'
                  + '<td>' + c.credits + '</td>'
                  + '<td>' + note + '</td>'
                  + '<td>' + c.grade_points + '</td>'
                  + '</tr>';
        });
        
        html += '</tbody></table>';
        html += '<a href="?page=student.history" class="btn btn-primary">'
              + 'Voir historique</a>';
        
        $('#content').html(html);
    }, 'json');
});
</script>
</body>
</html>