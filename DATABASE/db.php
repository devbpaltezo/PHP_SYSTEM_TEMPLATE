<?php

class SecureDB extends mysqli {
    private static $instance = null;
    private $user = DBUSER;
    private $pass = DBPWD;
    private $dbName = DBNAME;
    private $dbHost = DBHOST;

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
        parent::set_charset('utf8');
    }

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    public function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = $this->sanitize($value);
            }
            return $input;
        }
        $input = trim($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $this->real_escape_string($input);
    }

    public function sanitizeGlobals() {
        $_POST = $this->sanitize($_POST);
        $_GET = $this->sanitize($_GET);
    }

    public function query_set($query, $params = []) {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return false;
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function query_get($query, $params = []) {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return [];
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return [];
        } elseif ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    public function query_get_with_options($query, $params = [], $options = []) {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return []; // Handle query preparation failure
        }
    
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Adjust type binding if needed
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Handle 'count' option
        if (isset($options['count']) && $options['count'] === true) {
            return $result->num_rows;
        }
    
        // Handle 'list' option: return data as an array
        if (isset($options['list']) && $options['list'] === true) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    
        // Default behavior: return single row as an object
        if ($result->num_rows === 1) {
            return $result->fetch_object(); // Return single row as an object
        }
    
        // If multiple rows exist and no specific option is provided, return array of rows
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

}

// Initialize the database instance
$db = SecureDB::getInstance();

// Sanitize global inputs
$db->sanitizeGlobals();

?>