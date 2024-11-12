<?php
session_start();

function getUsers() {
    return [
        ['email' => 'user1@email.com', 'password' => 'password1'],
        ['email' => 'user2@email.com', 'password' => 'password2'],
    ];
}

function validateLoginCredentials($email, $password) {
    $errors = [];
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    return $errors;
}
function getSubjectById($id) {
    // Fetch subject by ID from the database or data source
}

function updateSubject($id, $subjectCode, $subjectName) {
    // Update subject in the database or data source
}

function deleteSubjectById($id) {
    // Delete subject from the database or data source
}

function addSubject($subjectCode, $subjectName) {
    // Initialize the subjects array if it's not set
    if (!isset($_SESSION['subjects'])) {
        $_SESSION['subjects'] = [];
    }

    // Add the new subject
    $_SESSION['subjects'][] = [
        'subject_code' => $subjectCode,
        'subject_name' => $subjectName
    ];
}
function getSubjects() {
    // Assuming you're fetching subjects from a database
    $subjects = []; // Replace this with your actual data fetch logic

    // Example return structure
  
}
function checkLoginCredentials($email, $password, $users) {
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return true;
        }
    }
    return false;
}

function checkUserSessionIsActive() {
    return isset($_SESSION['user']);
}

// functions.php

function guard() {
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: index.php");
        exit;
    }
}


function displayErrors($errors) {
    if (!empty($errors)) {
        echo "<ul style='color: red;'>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>
