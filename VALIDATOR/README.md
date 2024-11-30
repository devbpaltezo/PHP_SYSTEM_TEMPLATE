# Usage Example

```php

$email = "user@example.com";
$password = "Password123";
$minPasswordLength = 8;

// Validate email
$emailValidation = FormValidator::validateEmail($email);
if ($emailValidation === true) {
    echo "Email is valid.<br>";
} else {
    echo $emailValidation . "<br>";
}

// Validate password
$passwordValidation = FormValidator::validatePassword($password, $minPasswordLength);
if ($passwordValidation === true) {
    echo "Password is valid.<br>";
} else {
    echo $passwordValidation . "<br>";
}

```

# Output Example:

`Email is valid.`
`Password must be at least 8 characters long.`

