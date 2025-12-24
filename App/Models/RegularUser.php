<?php
namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;

class RegularUser extends AbstractUser implements AuthInterface {
    
    public function userRole() {
        return "Regular User";
    }

    public function login($email, $password) {
        // 1. Fetch user from DB
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND role = 'Regular User'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 2. Verify Password
        if ($user && password_verify($password, $user['password'])) {
            return "User logged in successfully (Verified via DB).";
        }

        return "Invalid credentials.";
    }

    public function logout() {
        return "User logged out.";
    }
}
?>