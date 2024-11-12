<?php
// Include necessary files
include(__DIR__ . '/../functions.php');
guard();  // Ensure the user is logged in

// Check if the student ID is provided in the GET request
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // Retrieve the list of students from the session
    $students = $_SESSION['students'] ?? [];

    // Find the student to delete
    $studentToDelete = null;
    foreach ($students as $student) {
        if ($student['id'] == $studentId) {
            $studentToDelete = $student;
            break;
        }
    }

    // If student not found, redirect back to register.php with an error
    if (!$studentToDelete) {
        header('Location: ..student/register.php?error=Student not found.');
        exit();
    }

    // Check if the form is submitted (when "Yes, Delete" is pressed)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Remove the student from the array
        foreach ($students as $key => $student) {
            if ($student['id'] == $studentId) {
                unset($students[$key]);
                break;
            }
        }

        // Re-index the array and save the updated list back to the session
        $_SESSION['students'] = array_values($students);

        // Redirect back to register.php with a success message
        header('Location: register.php');
        exit();
    }
} else {
    // If no student ID is provided, redirect back with an error
    header('Location: ..student/register.php?error=Student ID missing.');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="../register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>

        <h3>Are you sure you want to delete the following student record?</h3>
        
        <div class="alert alert-warning">
            <strong>Student ID:</strong> <?php echo htmlspecialchars($studentToDelete['id']); ?><br>
            <strong>First Name:</strong> <?php echo htmlspecialchars($studentToDelete['first_name']); ?><br>
            <strong>Last Name:</strong> <?php echo htmlspecialchars($studentToDelete['last_name']); ?><br>
        </div>

        <!-- Confirmation form -->
        <form action="delete.php?id=<?php echo $studentId; ?>" method="POST">
            <button type="submit" class="btn btn-danger">Yes, Delete Student</button>
            <a href="register.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
