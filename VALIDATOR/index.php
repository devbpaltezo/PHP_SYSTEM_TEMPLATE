<?php
class FormValidator {

    // Validate email format
    public static function validateEmail($email) {
        // Check if email is in valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return "Invalid email format.";
    }

    // Validate password strength with an optional minimum length
    public static function validatePassword($password, $minLength = 6) {
        // Check if password length is greater than or equal to the minimum length
        if (strlen($password) < $minLength) {
            return "Password must be at least $minLength characters long.";
        }

        // Check for at least one letter and one number
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return "Password must contain both letters and numbers.";
        }

        return true;
    }
}
