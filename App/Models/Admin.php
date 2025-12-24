<?php
namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;
use App\Core\LoggerTrait;
use PDO;

class Admin extends AbstractUser implements AuthInterface {
    use LoggerTrait;

    public function userRole() {
        return "Admin";
    }

    public function login($email, $password) {
        // 1. Fetch user from DB by Email
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND role = 'Admin'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 2. Verify Password
        if ($user && password_verify($password, $user['password'])) {
            $this->logActivity("Admin {$user['name']} logged in (Verified via DB).");
            return "Admin logged in successfully.";
        }

        return "Invalid Admin credentials.";
    }

    public function logout() {
        $this->logActivity("Admin logged out.");
        return "Admin logged out.";
    }
}
?>