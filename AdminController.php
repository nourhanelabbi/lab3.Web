<?php
require_once 'models/User.php';
require_once 'models/Semester.php';
require_once 'models/Course.php';
require_once 'models/Enrollment.php';
require_once 'models/Assignment.php';

class AdminController {
    public function __construct() {
        requireRole('admin');
        $page = $_GET['page'] ?? 'admin.dashboard';

        match($page) {
            'admin.dashboard'   => $this->dashboard(),
            'admin.semesters'   => $this->semesters(),
            'admin.courses'     => $this->courses(),
            'admin.professors'  => $this->professors(),
            'admin.students'    => $this->students(),
            'admin.enrollments' => $this->enrollments(),
            'admin.assignments' => $this->assignments(),
            default             => $this->dashboard()
        };
    }

    // ─── Dashboard ───────────────────────────────────────
    private function dashboard() {
        $userModel = new User();
        $data['total_students']   = count($userModel->getAllByRole('student'));
        $data['total_professors'] = count($userModel->getAllByRole('professor'));
        $data['total_semesters']  = count((new Semester())->getAll());
        include 'views/admin/dashboard.php';
    }

    // ─── Semesters ───────────────────────────────────────
    private function semesters() {
        $model = new Semester();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'save') {
                $label = htmlspecialchars(trim($_POST['label']));
                $year  = htmlspecialchars(trim($_POST['academic_year']));
                $id    = $_POST['id'] ?? null;
                if ($id) $model->update($id, $label, $year);
                else     $model->create($label, $year);
                flash('success', 'Semestre enregistré.');
            } elseif ($action === 'toggle') {
                $model->setAllInactive();
                $model->setActive(intval($_POST['id']));
                flash('success', 'Semestre activé.');
            } elseif ($action === 'delete') {
                $id = intval($_POST['id']);
                if ((new Course())->countBySemester($id) > 0) {
                    flash('danger', 'Impossible: des cours sont liés.');
                } else {
                    $model->delete($id);
                    flash('success', 'Semestre supprimé.');
                }
            }
            header('Location: ?page=admin.semesters'); exit;
        }
        $semesters = $model->getAll();
        include 'views/admin/semesters.php';
    }

    // ─── Courses ─────────────────────────────────────────
    private function courses() {
        $model = new Course();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'save') {
                $name    = htmlspecialchars(trim($_POST['name']));
                $credits = intval($_POST['credits']);
                $semId   = intval($_POST['semester_id']);
                $id      = $_POST['id'] ?? null;
                if ($credits <= 0) {
                    flash('danger', 'Les crédits doivent être positifs.');
                } else {
                    if ($id) $model->update($id, $name, $credits, $semId);
                    else     $model->create($name, $credits, $semId);
                    flash('success', 'Cours enregistré.');
                }
            } elseif ($action === 'delete') {
                $id = intval($_POST['id']);
                if ($model->countByCourse($id) > 0) {
                    flash('danger', 'Impossible: des notes existent.');
                } else {
                    $model->delete($id);
                    flash('success', 'Cours supprimé.');
                }
            }
            header('Location: ?page=admin.courses'); exit;
        }
        $courses   = $model->getAll();
        $semesters = (new Semester())->getAll();
        include 'views/admin/courses.php';
    }
// ─── Professors ──────────────────────────────────────
    private function professors() {
        $model = new User();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'save') {
                $name  = htmlspecialchars(trim($_POST['name']));
                $email = htmlspecialchars(trim($_POST['email']));
                $pass  = $_POST['password'];
                $id    = $_POST['id'] ?? null;
                if ($model->emailExists($email, $id)) {
                    flash('danger', 'Email déjà utilisé.');
                } else {
                    if ($id) {
                        $model->update($id, $name, $email);
                        if (!empty($pass))
                            $model->updatePassword($id, password_hash($pass, PASSWORD_BCRYPT));
                    } else {
                        $model->create($name, $email,
                            password_hash($pass, PASSWORD_BCRYPT), 'professor');
                    }
                    flash('success', 'Professeur enregistré.');
                }
            } elseif ($action === 'delete') {
                $model->delete(intval($_POST['id']));
                flash('success', 'Professeur supprimé.');
            }
            header('Location: ?page=admin.professors'); exit;
        }
        $professors = $model->getAllByRole('professor');
        include 'views/admin/professors.php';
    }

    // ─── Students ────────────────────────────────────────
    private function students() {
        $model = new User();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'save') {
                $name  = htmlspecialchars(trim($_POST['name']));
                $email = htmlspecialchars(trim($_POST['email']));
                $pass  = $_POST['password'];
                $id    = $_POST['id'] ?? null;
                if ($model->emailExists($email, $id)) {
                    flash('danger', 'Email déjà utilisé.');
                } else {
                    if ($id) {
                        $model->update($id, $name, $email);
                        if (!empty($pass))
                            $model->updatePassword($id, password_hash($pass, PASSWORD_BCRYPT));
                    } else {
                        $model->create($name, $email,
                            password_hash($pass, PASSWORD_BCRYPT), 'student');
                    }
                    flash('success', 'Étudiant enregistré.');
                }
            } elseif ($action === 'delete') {
                $model->delete(intval($_POST['id']));
                flash('success', 'Étudiant supprimé.');
            }
            header('Location: ?page=admin.students'); exit;
        }
        $students = $model->getAllByRole('student');
        include 'views/admin/students.php';
    }

    // ─── Enrollments ─────────────────────────────────────
    private function enrollments() {
        $studentId = intval($_GET['student_id'] ?? 0);
        $model     = new Enrollment();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = intval($_POST['student_id']);
            $newIds    = $_POST['semester_ids'] ?? [];
            $currentIds = $model->getSemesterIds($studentId);
            $toAdd    = array_diff($newIds, $currentIds);
            $toRemove = array_diff($currentIds, $newIds);
            foreach ($toAdd as $sid) $model->create($studentId, $sid);
            $skipped = 0;
            foreach ($toRemove as $sid) {
                if ($model->countByStudentSemester($studentId, $sid) > 0) {
                    $skipped++;
                } else {
                    $model->delete($studentId, $sid);
}
            }
            $msg = 'Inscriptions enregistrées.';
            if ($skipped) $msg .= " ($skipped semestre(s) non supprimé(s): notes existantes)";
            flash('success', $msg);
            header('Location: ?page=admin.enrollments&student_id=' . $studentId); exit;
        }
        $students  = (new User())->getAllByRole('student');
        $semesters = (new Semester())->getAll();
        $enrolled  = $studentId ? $model->getSemesterIds($studentId) : [];
        include 'views/admin/enrollments.php';
    }

    // ─── Assignments ─────────────────────────────────────
    private function assignments() {
        $model = new Assignment();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'save') {
                $profId   = intval($_POST['professor_id']);
                $courseId = intval($_POST['course_id']);
                $semId    = intval($_POST['semester_id']);
                if ($model->courseAlreadyAssigned($courseId, $semId)) {
                    flash('danger', 'Ce cours a déjà un professeur ce semestre.');
                } else {
                    $model->create($profId, $courseId, $semId);
                    flash('success', 'Assignation enregistrée.');
                }
            } elseif ($action === 'delete') {
                $model->delete(intval($_POST['id']));
                flash('success', 'Assignation supprimée.');
            }
            header('Location: ?page=admin.assignments'); exit;
        }
        $assignments = $model->getAll();
        $professors  = (new User())->getAllByRole('professor');
        $courses     = (new Course())->getAll();
        $semesters   = (new Semester())->getAll();
        include 'views/admin/assignments.php';
    }
}
?>
