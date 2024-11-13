<?php
// Start the session and include necessary functions
include(__DIR__ . '/../functions.php');

// Protect this page with guard to check if the user is logged in
guard();

// Initialize variables
$error = '';
$successMessage = '';

// Retrieve the list of students from session
$students = $_SESSION['students'] ?? [];

// Handle form submission to register a new student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form input values
    $studentId = trim($_POST['student_id'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');

    // Validate inputs
    if (empty($studentId) || empty($firstName) || empty($lastName)) {
        $error = "All fields are required.";
    } else {
        // Check if the student ID already exists
        $isDuplicate = false;
        foreach ($students as $student) {
            if ($student['id'] == $studentId) {
                $isDuplicate = true;
                break;
            }
        }

        if ($isDuplicate) {
            $error = "Student ID already exists. Please use a different ID.";
        } else {
            // Add the new student to the session array
            $students[] = [
                'id' => $studentId,
                'first_name' => $firstName,
                'last_name' => $lastName
            ];

            // Save the updated students array back to the session
            $_SESSION['students'] = $students;

            // Success message
            $successMessage = "Student registered successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register a New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Register a New Student</h2>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Register Student</li>
            </ol>
        </nav>

        <!-- Error and success messages -->
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

        <!-- Registration form -->
        <form method="POST" class="mb-5">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID:</label>
                <input type="text" class="form-control" name="student_id" id="student_id" placeholder="Enter Student ID">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name">
            </div>
            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>

        <!-- Student List -->
        <h4>Student List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No student records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-primary btn-sm">Edit</a>

                                <!-- Delete Form -->
                                <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm">Delete</a>

                                <a href="attached_subject.php?student_id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">Attach Subject</a>


                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
