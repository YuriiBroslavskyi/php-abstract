<?php
namespace App\Core;

abstract class AbstractUser {
    protected $name;
    protected $email;
    protected $password;
    protected $db;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->db = Database::connect(); // Get DB connection
    }

    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }

    abstract public function userRole();

    // New: Save user to Database
    public function save() {
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$this->email]);
        
        if ($stmt->fetch()) {
            echo "Error: Email '{$this->email}' already exists.<br>";
            return;
        }

        // Insert new user
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$this->name, $this->email, $this->password, $this->userRole()])) {
            echo "User '{$this->name}' registered successfully in Database.<br>";
        } else {
            echo "Error saving user.<br>";
        }
    }
}
?>