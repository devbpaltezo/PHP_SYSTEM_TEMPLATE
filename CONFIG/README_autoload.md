
# Autoload PHP Functions

This guide explains how to use an autoload system in PHP to automatically include function files from multiple directories, so you don't need to manually include them in each page.

## Problem

You have function files spread across multiple directories, and you need to manually include each one in every page. This leads to redundant `include` or `require` statements on each page.

## Solution

Create a single `autoload.php` file that will include all function files automatically. Then, on each page, you only need to call one function (`autoloadFunctions()`) to load all necessary files.

---

## Directory Structure

Your project may have a structure like the following:

```
/admin
    - index.php
    - admin.css
    - admin.js
/student
    - index.php
    - student.css
    - student.js
/live
    - index.php
    - live.css
    - live.js
/utilities
    - helper.php
/db
    - db_functions.php
/providers
    - data_provider.php
```

---

## Steps

### 1. **Create the `autoload.php` File**

The `autoload.php` file will include all function files from the `/utilities`, `/db`, and `/providers` directories.

```php
// autoload.php
function autoloadFunctions() {
    // Define directories containing your function files
    $directories = [
        __DIR__ . '/utilities',
        __DIR__ . '/db',
        __DIR__ . '/providers'
    ];

    // Loop through each directory and include all PHP files
    foreach ($directories as $directory) {
        // Check if the directory exists
        if (is_dir($directory)) {
            // Get all PHP files in the directory
            $files = glob($directory . '/*.php');
            foreach ($files as $file) {
                include_once $file; // Include the function file
            }
        }
    }
}
```

### 2. **Include the `autoload.php` in Your Pages**

In every page file where you need to use the functions, you only need to include the `autoload.php` and call `autoloadFunctions()`.

```php
// /admin/index.php
include_once '/path/to/autoload.php';

// Call the autoload function to include all function files
autoloadFunctions();

// You can now use any function from /utilities, /db, or /providers
someUtilityFunction();
someDbFunction();
someProviderFunction();
```

### 3. **Optional: Centralize the Include**

You can include the `autoload.php` and call the `autoloadFunctions()` function in a central configuration file, such as `config.php`, which is then included in each page.

```php
// config.php
include_once '/path/to/autoload.php';
autoloadFunctions();
```

Then, include the `config.php` in each page:

```php
// /admin/index.php
include_once '/path/to/config.php';

// Now you can use all functions without individually including them
```

---

## Benefits

- **Reduced redundancy**: You only need to call `autoloadFunctions()` once per page.
- **Centralized management**: If you add new function files in the future, just place them in the respective directory, and they will automatically be included.
- **Cleaner code**: Your page files will no longer have multiple `include` or `require` statements.

---

## Conclusion

This solution simplifies the process of including function files in your PHP project. With the autoload system, you only need to maintain one function for including all necessary files, making your code cleaner and more maintainable.

