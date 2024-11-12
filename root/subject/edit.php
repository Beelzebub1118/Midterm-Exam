<?php
// Include the necessary functions file from the root directory
include(__DIR__ . '/../functions.php');

// Protect this page with guard to check if the user is logged in
guard();

// Initialize variables
$error = '';
$successMessage = '';
$subject = null;

// Check if an ID is provided in the query string
$id = $_GET['id'] ?? null;

// Retrieve the subject details if ID is provided
if ($id) {
    $subject = getSubjectById($id); // Function to get subject by ID (you need to define this in functions.php)
    if (!$subject) {
        header('Location: add.php?error=Subject not found.');
        exit;
    }
} else {
    header('Location: add.php?error=Invalid subject ID.');
    exit;
}

// Handle form submission to update the subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectCode = trim($_POST['subject_code'] ?? '');
    $subjectName = trim($_POST['subject_name'] ?? '');

    // Check for empty fields
    if (empty($subjectCode) && empty($subjectName)) {
        $error = "Subject Code and Subject Name are required.";
    } elseif (empty($subjectCode)) {
        $error = "Subject Code is required.";
    } elseif (empty($subjectName)) {
        $error = "Subject Name is required.";
    } else {
        // Check for duplicates using getSubjects()
        $subjects = getSubjects();

        // Initialize duplicate flags
        $codeExists = false;
        $nameExists = false;

        foreach ($subjects as $sub) {
            if ($sub['id'] != $id) { // Exclude current subject ID from the check
                if (strcasecmp($sub['subject_code'], $subjectCode) === 0) {
                    $codeExists = true;
                }
                if (strcasecmp($sub['subject_name'], $subjectName) === 0) {
                    $nameExists = true;
                }
            }
        }

        if ($codeExists && $nameExists) {
            $error = "Subject Code and Subject Name already exist.";
        } elseif ($codeExists) {
            $error = "Subject Code already exists.";
        } elseif ($nameExists) {
            $error = "Subject Name already exists.";
        } else {
            // Update the subject if no duplicates
            if (updateSubject($id, $subjectCode, $subjectName)) {
                $successMessage = "Subject updated successfully!";
                // Refresh the page to display updated data
                header("Location: edit.php?id=$id&success=1");
                exit;
            } else {
                $error = "Failed to update the subject.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Subject</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Subject updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code:</label>
                <input type="text" class="form-control" name="subject_code" id="subject_code" value="<?php echo htmlspecialchars($subject['subject_code'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" class="form-control" name="subject_name" id="subject_name" value="<?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Subject</button>
        </form>

        <a href="add.php" class="btn btn-secondary mt-3">Back to Add Subject</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
