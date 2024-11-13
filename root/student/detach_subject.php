<?php
include('../header.php');
include('../functions.php');
guard();

// Get student ID and subject ID from GET request
$studentId = $_GET['student_id'] ?? null;
$subjectId = $_GET['subject_id'] ?? null;

if (!$studentId || !$subjectId) {
    header('Location: ../student/register.php?error=Missing student or subject ID.');
    exit();
}

// Retrieve students and subjects from the session
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

// Find the subject by ID
$subject = null;
foreach ($subjects as $sub) {
    if ($sub['id'] == $subjectId) {
        $subject = $sub;
        break;
    }
}

if (!$subject) {
    header('Location: attached_subject.php?student_id=' . $studentId . '&error=Subject not found.');
    exit();
}

// Handle detachment confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($students as &$s) {
        if ($s['id'] == $studentId && !empty($s['attached_subjects'])) {
            $s['attached_subjects'] = array_filter(
                $s['attached_subjects'],
                fn($id) => $id != $subjectId
            );
            break;
        }
    }
    $_SESSION['students'] = $students;

    // Redirect to the attach subject page with success message
    header("Location: attached_subject.php?student_id={$studentId}&success=Subject detached successfully.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detach Subject from Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Detach Subject from Student</h3>
    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../student/register.php">Register Student</a></li>
            <li class="breadcrumb-item"><a href="attached_subject.php?student_id=<?php echo htmlspecialchars($studentId); ?>">Attach Subject to Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detach Subject from Student</li>
        </ol>
    </nav>

    <div class="alert alert-warning mt-4">
        <strong>Are you sure you want to detach this subject from this student record?</strong>
        <ul>
            <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student['id']); ?></li>
            <li><strong>First Name:</strong> <?php echo htmlspecialchars($student['first_name']); ?></li>
            <li><strong>Last Name:</strong> <?php echo htmlspecialchars($student['last_name']); ?></li>
            <li><strong>Subject Code:</strong> <?php echo htmlspecialchars($subject['subject_code']); ?></li>
            <li><strong>Subject Name:</strong> <?php echo htmlspecialchars($subject['subject_name']); ?></li>
        </ul>
    </div>

    <form method="POST" action="detach_subject.php?student_id=<?php echo htmlspecialchars($studentId); ?>&subject_id=<?php echo htmlspecialchars($subjectId); ?>">
        <button type="submit" class="btn btn-primary">Detach Subject from Student</button>
        <a href="attached_subject.php?student_id=<?php echo htmlspecialchars($studentId); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
