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
    // Initialize the subjects array if not already set
    if (!isset($_SESSION['subjects'])) {
        $_SESSION['subjects'] = [];  // Initialize as an empty array if not set
    }
    return $_SESSION['subjects']; // Return the subjects from session
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
