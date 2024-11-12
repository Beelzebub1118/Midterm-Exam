<?php
// Include the necessary functions file from the root directory
include(__DIR__ . '/../functions.php');

// Protect this page with guard to check if the user is logged in
guard();

// Initialize variables
$error = '';
$successMessage = '';

// Handle form submission to add a new subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectCode = trim($_POST['subject_code'] ?? '');
    $subjectName = trim($_POST['subject_name'] ?? '');

    // Check if inputs are empty
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

        // Check if the subject code or subject name already exists
        foreach ($subjects as $subject) {
            if (strcasecmp($subject['subject_code'], $subjectCode) === 0) {
                $codeExists = true;
            }
            if (strcasecmp($subject['subject_name'], $subjectName) === 0) {
                $nameExists = true;
            }
        }

        // Set appropriate error message based on duplicates found
        if ($codeExists && $nameExists) {
            $error = "Subject Code and Subject Name already exist.";
        } elseif ($codeExists) {
            $error = "Subject Code already exists.";
        } elseif ($nameExists) {
            $error = "Subject Name already exists.";
        } else {
            // If no duplicates, add the subject
            addSubject($subjectCode, $subjectName);
            $successMessage = "Subject added successfully!";
        }
    }
}

// Retrieve the list of subjects
$subjects = getSubjects() ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>

    <div class="container mt-5">
        <h2>Add A New Subject</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code:</label>
                <input type="text" class="form-control" name="subject_code" id="subject_code">
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" class="form-control" name="subject_name" id="subject_name">
            </div>
            <button type="submit" class="btn btn-primary">Add Subject</button>
        </form>

        <div class="row mt-5">
            <div class="col-md-12">
                <h4>Subject List</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($subjects)): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                    <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $subject['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete.php?id=<?php echo $subject['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No subjects found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
