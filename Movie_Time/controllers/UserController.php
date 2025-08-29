<?php
require_once __DIR__ . '/../config/Database.php'; 
require_once __DIR__ . '/../config/ConnectionParam.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class UserController {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // User registration
    public function register($name, $surname, $email, $password) {
        if (empty($name) || empty($surname) || empty($email) || empty($password)) {
            return "⚠️ Tous les champs sont obligatoires.";
        }

        $stmt = $this->db->prepare("SELECT * FROM mt_users WHERE MAIL_USER = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "🚨 Cet email est déjà utilisé.";
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user
        $stmt = $this->db->prepare("INSERT INTO mt_users (NAME_USER, SURNAME_USER, MAIL_USER, PASSWORD_USER) VALUES (:name, :surname, :email, :password)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);

        if ($stmt->execute()) {
            return "✅ Inscription réussie. Vous pouvez maintenant vous connecter.";
        } else {
            return "❌ Erreur lors de l'inscription.";
        }
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return "⚠️ Email et mot de passe requis.";
        }
    
        $stmt = $this->db->prepare("SELECT * FROM mt_users WHERE MAIL_USER = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['PASSWORD_USER'])) {
            $_SESSION['user'] = [
                'id' => $user['ID_USER'],
                'name' => $user['NAME_USER'],
                'surname' => $user['SURNAME_USER'],
                'email' => $user['MAIL_USER']
            ];
            header("Location: ../views/index.php");
            openPopup("Succès","Vous êtes connectez", 3000);
            exit();
        } else {
            return "❌ Email ou mot de passe incorrect.";
        }
    }
    

    // Update user information
    public function updateUser($id, $name, $surname, $email) {
        if (empty($id) || empty($name) || empty($surname) || empty($email)) {
            return "⚠️ Tous les champs sont obligatoires pour la mise à jour.";
        }
        
        $stmt = $this->db->prepare("UPDATE mt_users SET NAME_USER = :name, SURNAME_USER = :surname, MAIL_USER = :email WHERE ID_USER = :id");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['surname'] = $surname;
            $_SESSION['user']['email'] = $email;
            return "✅ Vos informations ont été mises à jour.";
        } else {
            return "❌ Erreur lors de la mise à jour.";
        }
    }

    // Log off
    public function logout() {
        session_destroy();
        header("Location: ../views/index.php");
        exit();
    }
}
?>
