$(document).ready(function () {

    // Semestre sélectionné → charger cours
    $('#semesterSelect').change(function () {
        var semId = $(this).val();
        if (!semId) return;
        
        $.get('api/grades.php',
            { action: 'courses', semester_id: semId },
            function (data) {
                var opts = '<option value="">-- Choisir cours --</option>';
                $.each(data, function (i, c) {
                    opts += '<option value="' + c.id + '">' + c.name + '</option>';
                });
                $('#courseSelect').html(opts).prop('disabled', false);
                $('#gradeTable').hide();
                $('#saveBtn').hide();
            }, 'json'
        );
    });

    // Cours sélectionné → charger étudiants
    $('#courseSelect').change(function () {
        var semId    = $('#semesterSelect').val();
        var courseId = $(this).val();
        if (!courseId) return;
        
        $.get('api/grades.php',
            { action: 'students', semester_id: semId, course_id: courseId },
            function (students) {
                var html = '<tbody>';
                $.each(students, function (i, s) {
                    var gradeVal = s.grade !== null ? s.grade : '';
                    html += '<tr>'
                        + '<td>' + s.name + '</td>'
                        + '<td>' + s.id + '</td>'
                        + '<td><select name="grade_' + s.id + '" '
                        + 'data-student="' + s.id + '" '
                        + 'class="form-select grade-input">'
                        + buildOptions(gradeVal)
                        + '</select></td></tr>';
                });
                html += '</tbody>';
                $('#gradeTable').find('tbody').replaceWith(html);
                $('#gradeTable').show();
            }, 'json'
        );
    });

    // Sauvegarder notes
    $('#saveBtn').click(function () {
        var semId    = $('#semesterSelect').val();
        var courseId = $('#courseSelect').val();
        var grades   = [];
        
        $('.grade-input').each(function () {
            grades.push({
                student_id: $(this).data('student'),
                grade:      $(this).val()
            });
        });
        
        $.post('api/grades.php',
            { action: 'save', semester_id: semId,
              course_id: courseId, grades: grades },
            function (res) {
                var cls = res.success ? 'alert-success' : 'alert-danger';
                var msg = res.success
                    ? res.saved + ' note(s) enregistrée(s).'
                    : res.error;
                $('#feedback').html(
                    '<div class="alert ' + cls + '">' + msg + '</div>'
                );
            }, 'json'
        );
    });

    function buildOptions(selected) {
        var opts = [['', '-- Note --'], ['4.0', 'A'], ['3.0', 'B'],
                    ['2.0', 'C'], ['1.0', 'D'], ['0.0', 'F']];
        return opts.map(function (o) {
            return '<option value="' + o[0] + '"'
                + (o[0] == selected ? ' selected' : '') + '>'
                + o[1] + '</option>';
        }).join('');
    }
});