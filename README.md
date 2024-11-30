
# `db` Class - Database Singleton Class with SQL Injection Prevention

This class provides a singleton-based MySQL database connection and ensures SQL injection prevention by using prepared statements. It also includes methods for safely querying and sanitizing user inputs via `$_POST` and `$_GET`.

## Features
- Singleton database connection (only one instance).
- Safe query execution with prepared statements.
- Prevents SQL injection by binding parameters.
- Sanitizes global input (`$_POST`, `$_GET`).
- Flexible data retrieval with options to return data as an array, object, or count.
  
## Prerequisites
- PHP 7.x or higher.
- MySQL/MariaDB database server.
- Defined constants for database credentials (`DBUSER`, `DBPWD`, `DBNAME`, `DBHOST`).

## Installation

1. **Download/Clone the Repository**
   - Clone or download the repository containing the `db` class.

2. **Configure Database Credentials**
   - Define the database credentials in your configuration file or directly in your PHP file:
   
   ```php
   define('DBUSER', 'your_db_user');
   define('DBPWD', 'your_db_password');
   define('DBNAME', 'your_db_name');
   define('DBHOST', 'your_db_host');
   ```

---

## Usage

### 1. **Initialize Database Connection (Singleton)**

To use the database class, you must first get an instance of it using the `getInstance()` method. The class follows the **Singleton Pattern**, ensuring only one database connection is made.

```php
// Include the db class
require_once 'db.php';

// Get the database instance
$db = db::getInstance();
```

### 2. **Sanitize Global Input (`$_POST`, `$_GET`)**

It's important to sanitize global variables to avoid potential XSS or other input issues.

```php
// Sanitize $_POST and $_GET global variables
$db->sanitizeGlobals();
```

This will clean all `$_POST` and `$_GET` values using the `sanitize` method.

### 3. **Executing Queries**

#### 3.1 **Insert/Update/Delete Queries (`query_set`)**

Use `query_set` for executing queries that do not return data (e.g., `INSERT`, `UPDATE`, `DELETE`). Prepared statements will be used to ensure the queries are safe.

```php
// Example: Insert a new user
$query = "INSERT INTO users (username, email) VALUES (?, ?)";
$params = ['john_doe', 'john@example.com'];

if ($db->query_set($query, $params)) {
    echo "User added successfully!";
} else {
    echo "Failed to add user.";
}
```

#### 3.2 **Select Queries (`query_get`)**

Use `query_get` to fetch data from the database. The result can be returned as an associative array or an object depending on the query result.

```php
// Example: Fetch single user
$query = "SELECT * FROM users WHERE username = ?";
$params = ['john_doe'];

$user = $db->query_get($query, $params);

if ($user) {
    echo "User name: " . $user['name']; // Array format
} else {
    echo "User not found.";
}
```

#### 3.3 **Flexible Query Results (`query_get_with_options`)**

Use `query_get_with_options` to retrieve data with additional options like returning a count, a list of results, or a single result as an object.

```php
// Example 1: Get number of rows (count)
$query = "SELECT * FROM users WHERE status = ?";
$params = ['active'];
$options = ['count' => true];
$count = $db->query_get_with_options($query, $params, $options);
echo "Active users count: $count";

// Example 2: Get list of users as an array
$query = "SELECT * FROM users WHERE status = ?";
$params = ['active'];
$options = ['list' => true];
$users = $db->query_get_with_options($query, $params, $options);

foreach ($users as $user) {
    echo $user['name'] . "<br>"; // Array format
}

// Example 3: Get a single user as an object
$query = "SELECT * FROM users WHERE id = ?";
$params = [1];
$user = $db->query_get_with_options($query, $params);

if ($user) {
    echo "User name: " . $user->name; // Object format
} else {
    echo "User not found.";
}
```

---

## **Sanitize Functionality**

The `sanitize` method can be called on any string to sanitize user input, removing potentially dangerous characters.

```php
$unsafe_input = $_POST['username'];
$safe_input = $db->sanitize($unsafe_input);
```

This method will:
- Trim extra spaces.
- Escape special characters for SQL queries.
- Prevent XSS and other vulnerabilities.

---

## **Handling Errors**

The class includes basic error handling by returning `false` if a query fails. You can expand on this by logging errors or triggering exceptions for more robust error management.

```php
if ($db->query_set($query, $params) === false) {
    error_log("Query failed: $query");
    // You can throw exceptions here as well
}
```

---

## **Security Considerations**

### 1. **SQL Injection Prevention**
   - **Prepared Statements**: All queries are executed using prepared statements with bound parameters to prevent SQL injection.
   - **Input Sanitization**: The `sanitize` method ensures that all user inputs are clean before usage.

### 2. **Database Permissions**
   - Use a MySQL user with the **minimum required privileges** (e.g., `SELECT`, `INSERT`, `UPDATE`) to limit access to your database.

### 3. **SSL/TLS for Database Connection**
   - Ensure your database connection is encrypted with SSL/TLS to prevent interception of sensitive data.

---

## **Additional Methods**

- **`getInstance()`**: Returns the singleton instance of the database connection.
- **`query_set($query, $params)`**: Executes an insert, update, or delete query with parameters.
- **`query_get($query, $params)`**: Retrieves data from the database as an associative array.
- **`query_get_with_options($query, $params, $options)`**: Executes queries with additional options (`count`, `list`, etc.) and returns results as an array or object.
- **`sanitize($input)`**: Sanitizes user input to avoid XSS and other vulnerabilities.
- **`sanitizeGlobals()`**: Sanitizes global `$_POST` and `$_GET` variables automatically.

---

## **Example Workflow**

```php
<?php
// Include the db class
require_once 'db.php';

// Initialize the database instance
$db = db::getInstance();

// Sanitize global inputs
$db->sanitizeGlobals();

// Insert a new user
$query = "INSERT INTO users (username, email) VALUES (?, ?)";
$params = ['john_doe', 'john@example.com'];
if ($db->query_set($query, $params)) {
    echo "User added successfully!";
} else {
    echo "Failed to add user.";
}

// Fetch user data
$query = "SELECT * FROM users WHERE username = ?";
$params = ['john_doe'];
$user = $db->query_get($query, $params);

if (!empty($user)) {
    echo "User details:";
    print_r($user);
} else {
    echo "User not found.";
}
?>
```

---

## **Conclusion**

The `db` class simplifies database interactions while providing built-in security mechanisms to prevent SQL injection. By following best practices, such as input sanitization, prepared statements, and minimal database privileges, you ensure that your application is secure and efficient.

For any questions or issues, feel free to open an issue or contribute to the project!

