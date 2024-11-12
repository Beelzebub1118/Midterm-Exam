<?php
// Start the session and include necessary functions
session_start();
include(__DIR__ . '/../functions.php');

// Protect this page with guard to check if the user is logged in
guard();

// Get the subject ID from the URL
$subjectId = $_GET['id'] ?? null;

// Retrieve the list of subjects
$subjects = getSubjects();

// Initialize a flag to check if deletion was successful
$deleted = false;

// Find and delete the subject with the specified ID
foreach ($subjects as $key => $subject) {
    if ($subject['id'] == $subjectId) {
        unset($subjects[$key]);
        $deleted = true;
        break;
    }
}

// If deletion was successful, update the session
if ($deleted) {
    $_SESSION['subjects'] = array_values($subjects); // Reindex the array
    header('Location: add.php?message=Subject Deleted Successfully');
    exit;
} else {
    header('Location: add.php?error=Subject Not Found');
    exit;
}
