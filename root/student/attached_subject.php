<?php
include('../header.php'); // Include header file
include('../functions.php'); // Include your functions
guard(); // Ensure the user is logged in

// Get student ID from the GET request
if (!isset($_GET['student_id'])) {
    header('Location: ../student/register.php?error=Student ID is missing.');
    exit();
}

$studentId = $_GET['student_id'];

// Retrieve students and subjects from session
$students = $_SESSION['students'] ?? [];
$subjects = $_SESSION['subjects'] ?? [];

// Find the student by ID
$student = null;
foreach ($students as $s) {
    if ($s['id'] == $studentId) {
        $student = $s;
        break;
    }
}

if (!$student) {
    header('Location: ../student/register.php?error=Student not found.');
    exit();
}

// Prepare unattached subjects
$unattachedSubjects = [];
foreach ($subjects as $subject) {
    if (empty($student['attached_subjects']) || !in_array($subject['id'], $student['attached_subjects'])) {
        $unattachedSubjects[] = $subject;
    }
}

// Process form submission for attaching subjects
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedSubjects = $_POST['subjects'] ?? [];

    // Validate that at least one subject is selected
    if (empty($selectedSubjects)) {
        $errors[] = 'At least one subject should be selected.';
    } else {
        // Attach selected subjects to the student
        foreach ($students as &$s) {
            if ($s['id'] == $studentId) {
                $s['attached_subjects'] = array_merge(
                    $s['attached_subjects'] ?? [],
                    $selectedSubjects
                );
                break;
            }
        }
        $_SESSION['students'] = $students;

        // Redirect with success message
        header("Location: attached_subject.php?student_id={$studentId}&success=Subjects attached successfully.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attach Subject to Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Attach Subject to Student</h3>
    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../student/register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attach Subject to Student</li>
        </ol>
    </nav>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>System Errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-header">
            Selected Student Information
        </div>
        <div class="card-body">
            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
        </div>
    </div>

    <!-- Attach Subjects Form -->
    <form action="attached_subject.php?student_id=<?php echo htmlspecialchars($studentId); ?>" method="POST" class="mt-4">
        <div class="mb-3">
            <h4>Available Subjects</h4>
            <?php if (empty($unattachedSubjects)): ?>
                <p>No subjects available to attach.</p>
            <?php else: ?>
                <?php foreach ($unattachedSubjects as $subject): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="subjects[]" id="subject-<?php echo htmlspecialchars($subject['id']); ?>" value="<?php echo htmlspecialchars($subject['id']); ?>">
                        <label class="form-check-label" for="subject-<?php echo htmlspecialchars($subject['id']); ?>">
                            <?php echo htmlspecialchars($subject['subject_code'] . ' - ' . $subject['subject_name']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Attach Subjects</button>
    </form>

    <!-- Attached Subjects List -->
    <div class="mt-4">
        <h4>Subject List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($student['attached_subjects'])): ?>
                    <tr>
                        <td colspan="3" class="text-center">No subjects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $subject): ?>
                        <?php if (in_array($subject['id'], $student['attached_subjects'])): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                <td>
                                    <a href="detach_subject.php?student_id=<?php echo htmlspecialchars($student['id']); ?>&subject_id=<?php echo htmlspecialchars($subject['id']); ?>" class="btn btn-danger btn-sm">Detach Subject</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
