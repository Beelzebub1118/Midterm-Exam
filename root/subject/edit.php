<?php
// Start the session and include necessary functions
session_start();
include(__DIR__ . '/../functions.php');

// Protect this page with guard to check if the user is logged in
guard();

// Initialize variables
$error = '';
$successMessage = '';
$subject = null;

// Get the subject ID from the URL
$subjectId = $_GET['id'] ?? null;

// Retrieve the list of subjects
$subjects = getSubjects();

// Find the subject to edit
foreach ($subjects as $key => $subj) {
    if ($subj['id'] == $subjectId) {
        $subject = $subj;
        $subjectIndex = $key;
        break;
    }
}

// If subject not found, redirect to the add page
if (!$subject) {
    header('Location: add.php');
    exit;
}

// Handle form submission for editing the subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSubjectCode = trim($_POST['subject_code'] ?? '');
    $newSubjectName = trim($_POST['subject_name'] ?? '');

    // Validate inputs
    if (empty($newSubjectCode) && empty($newSubjectName)) {
        $error = "Subject Code and Subject Name are required.";
    } elseif (empty($newSubjectCode)) {
        $error = "Subject Code is required.";
    } elseif (empty($newSubjectName)) {
        $error = "Subject Name is required.";
    } else {
        // Check for duplicates
        $codeExists = false;
        $nameExists = false;

        foreach ($subjects as $key => $subj) {
            // Skip the current subject being edited
            if ($key != $subjectIndex) {
                if (strcasecmp($subj['subject_code'], $newSubjectCode) === 0) {
                    $codeExists = true;
                }
                if (strcasecmp($subj['subject_name'], $newSubjectName) === 0) {
                    $nameExists = true;
                }
            }
        }

        // Set error messages if duplicates are found
        if ($codeExists && $nameExists) {
            $error = "Both Subject Code and Subject Name already exist.";
        } elseif ($codeExists) {
            $error = "Subject Code already exists.";
        } elseif ($nameExists) {
            $error = "Subject Name already exists.";
        } else {
            // No duplicates, so update the subject in the session array
            $subjects[$subjectIndex]['subject_code'] = $newSubjectCode;
            $subjects[$subjectIndex]['subject_name'] = $newSubjectName;

            // Save the updated subjects array back to the session
            $_SESSION['subjects'] = $subjects;

            $successMessage = "Subject updated successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Subject</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code:</label>
                <input type="text" class="form-control" name="subject_code" id="subject_code" value="<?php echo htmlspecialchars($subject['subject_code']); ?>">
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" class="form-control" name="subject_name" id="subject_name" value="<?php echo htmlspecialchars($subject['subject_name']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Subject</button>
            <a href="add.php" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html>
