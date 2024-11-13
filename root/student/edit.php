<?php
// edit.php
include('../header.php');
include('../functions.php');
guard(); // Ensure the user is logged in

// Check if the student ID is provided in the GET request
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // Retrieve the list of students from the session
    $students = $_SESSION['students'] ?? [];

    // Find the student to edit
    $studentToEdit = null;
    foreach ($students as $student) {
        if ($student['id'] == $studentId) {
            $studentToEdit = $student;
            break;
        }
    }

    // If student not found, redirect back to register.php with an error
    if (!$studentToEdit) {
        header('Location: ../student/register.php?error=Student not found.');
        exit();
    }

    // Check if the form is submitted (when the form is posted)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the updated student details
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];

        // Update the student data in the session
        foreach ($students as $key => $student) {
            if ($student['id'] == $studentId) {
                $students[$key]['first_name'] = $firstName;
                $students[$key]['last_name'] = $lastName;
                $students[$key]['email'] = $email;
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
    header('Location: ../student/register.php?error=Student ID missing.');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="../register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
            </ol>
        </nav>

        <h3>Edit the following student record</h3>
        
        <div class="alert alert-info">
            <strong>Student ID:</strong> <?php echo htmlspecialchars($studentToEdit['id']); ?><br>
            <strong>First Name:</strong> <?php echo htmlspecialchars($studentToEdit['first_name']); ?><br>
            <strong>Last Name:</strong> <?php echo htmlspecialchars($studentToEdit['last_name']); ?><br>
        </div>

        <!-- Edit Form -->
        <form action="edit.php?id=<?php echo $studentId; ?>" method="POST">
            <!-- Student ID (Read-Only) -->
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($studentToEdit['id']); ?>" readonly>
            </div>

            <!-- First Name -->
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($studentToEdit['first_name']); ?>" required>
            </div>

            <!-- Last Name -->
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($studentToEdit['last_name']); ?>" required>
            </div>

        
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="#" class="btn btn-secondary" onclick="window.location.href='../student/register.php';">Cancel</a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>