<?php
session_start();

function getUsers() {
    return [
        ['email' => 'user1@email.com', 'password' => 'password1'],
        ['email' => 'user2@email.com', 'password' => 'password2'],
    ];
}
function deleteStudent($id) {
    $students = getStudents();

    // Filter out the student with the specified ID
    $students = array_filter($students, function($student) use ($id) {
        return $student['id'] != $id;
    });

    // Reindex the array and save it back to the session
    $_SESSION['students'] = array_values($students); // Reindex the array
}

function getStudents() {
    if (!isset($_SESSION['students'])) {
        $_SESSION['students'] = []; // Initialize as an empty array if not set
    }
    return $_SESSION['students'];
}
function getStudentById($id) {
    $students = getStudents();
    foreach ($students as $student) {
        if ($student['id'] == $id) {
            return $student;
        }
    }
    return null; // Return null if the student is not found
}
function updateStudent($id, $name, $email) {
    $students = getStudents();

    // Find the student by ID and update their information
    foreach ($students as &$student) {
        if ($student['id'] == $id) {
            $student['name'] = $name;
            $student['email'] = $email;
            break;
        }
    }

    // Save the updated students array back to the session
    $_SESSION['students'] = $students;
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
    // Fetch the existing subjects
    $subjects = getSubjects();

    // Generate a unique ID for the new subject
    $newId = count($subjects) + 1;

    // Add the new subject to the subjects array
    $subjects[] = [
        'id' => $newId,
        'subject_code' => $subjectCode,
        'subject_name' => $subjectName
    ];

    // Save the updated subjects array back to the session
    $_SESSION['subjects'] = $subjects;
}

function getSubjects() {
    // Initialize the subjects array if not already set
    if (!isset($_SESSION['subjects'])) {
        $_SESSION['subjects'] = [];  // Initialize as an empty array if not set
    }
    return $_SESSION['subjects']; // Return the subjects from the session
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
