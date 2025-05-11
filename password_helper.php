<?php
// Function to hash a password securely
function hashPassword($password) {
    // Use password_hash() with the default algorithm (bcrypt)
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify a password against a hashed password
function verifyPassword($password, $hashedPassword) {
    // Use password_verify() to check if the password matches the hash
    return password_verify($password, $hashedPassword);
}
?>