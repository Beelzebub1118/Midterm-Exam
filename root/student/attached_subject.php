<?php

require '../functions.php'; // Ensure you include your functions.php file

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch the student ID from the URL
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

// Check if student ID is valid
if (!$student_id) {
    echo "Error: Student ID is missing.";
    exit;
}

// Fetch the student details using your existing function
$student = getStudentById($student_id);

if (!$student) {
    echo "Student not found with ID: " . htmlspecialchars($student_id);
    exit;
}

// Fetch all subjects
$subjects = getSubjects();

// Initialize variables for error message
$errors = [];
$success_message = "";

// Handle form submission for attaching subjects
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];
    
    // Validate that at least one subject is selected
    $errors = validateAttachedSubject($selected_subjects);
    
    if (empty($errors)) {
        // Attach selected subjects to the student
        if (!isset($student['attached_subjects'])) {
            $student['attached_subjects'] = [];
        }

        $student['attached_subjects'] = array_merge($student['attached_subjects'], $selected_subjects);
        $student['attached_subjects'] = array_unique($student['attached_subjects']);

        // Update the student in session
        $students = getStudents();
        foreach ($students as &$s) {
            if ($s['id'] == $student_id) {
                $s = $student;
                break;
            }
        }
        $_SESSION['students'] = $students;

        $success_message = "Subjects attached successfully!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attach Subject to Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attach Subject to Student</li>
        </ol>
    </nav>

    <!-- Display System Errors -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <h5>System Errors</h5>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

   <!-- Selected Student Information -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">Selected Student Information</div>
    <div class="card-body">
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['id'] ?? 'N/A'); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name'] ?? 'Unknown'); ?></p>
    </div>
</div>

    <!-- Form to Attach Subjects -->
    <form method="post">
        <div class="card mb-4">
            <div class="card-header">Select Subjects</div>
            <div class="card-body">
                <?php if (empty($subjects)): ?>
                    <p>No subjects available.</p>
                <?php else: ?>
                    <?php foreach ($subjects as $subject): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   value="<?php echo htmlspecialchars($subject['subject_code']); ?>" 
                                   id="subject_<?php echo htmlspecialchars($subject['subject_code']); ?>" 
                                   name="subjects[]">
                            <label class="form-check-label" for="subject_<?php echo htmlspecialchars($subject['subject_code']); ?>">
                                <?php echo htmlspecialchars($subject['subject_code'] . ' - ' . $subject['subject_name']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

       
    <!-- Attached Subjects List -->
    <h4 class="mt-5">Attached Subjects</h4>
    <div class="card mb-4">
        <div class="card-body">
            <?php if (!empty($student['attached_subjects'])): ?>
                <ul class="list-group">
                    <?php foreach ($student['attached_subjects'] as $attached_subject_code): 
                        $subject_index = getSubject($attached_subject_code);
                        if ($subject_index !== null):
                            $attached_subject = $subjects[$subject_index];
                            ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($attached_subject['subject_code'] . ' - ' . $attached_subject['subject_name']); ?>
                            </li>
                        <?php else: ?>
                            <li class="list-group-item text-danger">Subject not found.</li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No subjects attached yet.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
