<?php
// Include the necessary functions file
include('functions.php');

// Protect this page with guard to check if the user is logged in
guard();

// Get the logged-in user's email from the session
$userEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link to Bootstrap CSS (Use a CDN for simplicity) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <!-- Container for dashboard content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Welcome message -->
                <div class="alert alert-success" role="alert">
                    Welcome, <?php echo htmlspecialchars($userEmail); ?>!
                </div>

                <!-- Logout Link -->
                <p><a href="index.php" class="btn btn-danger">Logout</a></p>

                <!-- Add Subject and Register Student Description -->
                <div class="row mt-4">
                    <!-- Add Subject Description -->
                    <div class="col-md-6 mb-4">
                        <div class="alert alert-info" role="alert">
                            <h4>Add a New Subject</h4>
                            <p>This section allows you to add a new subject in the system. Click the button below to proceed with the adding process.</p>
                            <a href="subject/add.php" class="btn btn-primary">Add Subject</a>
                        </div>
                    </div>

                    <!-- Register Student Description -->
                    <div class="col-md-6 mb-4">
                        <div class="alert alert-info" role="alert">
                            <h4>Register a New Student</h4>
                            <p>This section allows you to register a new student in the system. Click the button below to proceed with the registration process.</p>
                            <a href="student/register.php" class="btn btn-primary">Register Student</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional for the nav toggle to work) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
