<?php
include 'functions.php';

$errors = [];



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate email and password input
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Check credentials if there are no validation errors
    if (empty($errors) && !checkLoginCredentials($email, $password, getUsers())) {
        $errors[] = "Invalid login credentials.";
    }

    // Redirect if login is successful
    if (empty($errors)) {
        // Store the email in session with the key 'email'
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php include 'header.php'; ?>

    <h2 class="mb-4">Login</h2>

    <!-- Display Errors (if any) -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php foreach ($errors as $error): ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="text" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>">
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <?php include 'footer.php'; ?>
</div>

<!-- Bootstrap JS for dismissible alerts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
