<?php
namespace Controller;

use Model\Connection;
use Model\ActivityModel;
use Model\UserModel;

class UserController {
    
    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function showHome() {
        $activityModel = new ActivityModel($this->db);
        $userStats = ['total_atividades' => 0, 'total_calorias' => 0];
        if (isset($_SESSION['user_id'])) {
            $userStats = $activityModel->getUserStats($_SESSION['user_id']);
        }
        $title = "SAEP Saúde - Feed";
        $view_file = 'home_view.php';
        require_once dirname(__DIR__) . '/View/template.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha_digitada = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha_digitada)) {
                header('Location: ' . BASE_URL . '/index.php?error=login_failed');
                exit;
            }

            $userModel = new UserModel($this->db);
            $user = $userModel->findUserByEmail($email);

            if ($user && password_verify($senha_digitada, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome_usuario'];
                $_SESSION['user_avatar'] = $user['imagem'];
                
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/index.php?error=login_failed');
                exit;
            }
        }
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function showRegisterPage() {
        $title = "SAEP Saúde - Cadastro";
        $view_file = 'register_view.php';
        require_once dirname(__DIR__) . '/View/template.php';
    }

    public function registerUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $nome_usuario = $_POST['nome_usuario'] ?? '';
            $senha_pura = $_POST['senha'] ?? '';

            if (empty($nome) || empty($email) || empty($nome_usuario) || empty($senha_pura)) {
                header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Todos os campos são obrigatórios.');
                exit;
            }

            $userModel = new UserModel($this->db);

            if ($userModel->findUserByEmail($email)) {
                header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Este email já está em uso.');
                exit;
            }

            $nome_imagem = 'default.png';
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__) . '/Images/Imagens-perfil/';
                $fileTmpPath = $_FILES['imagem']['tmp_name'];
                $fileName = $_FILES['imagem']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadDir . $newFileName;

                $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $nome_imagem = $newFileName;
                    } else {
                        header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Erro ao mover o arquivo de imagem.');
                        exit;
                    }
                } else {
                    header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Tipo de arquivo inválido.');
                    exit;
                }
            }

            $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

            if ($userModel->createUser($nome, $email, $nome_usuario, $senha_hash, $nome_imagem)) {
                header('Location: ' . BASE_URL . '/index.php?success=registered');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage&error=Ocorreu um erro ao criar sua conta.');
                exit;
            }
        }
        header('Location: ' . BASE_URL . '/index.php?action=showRegisterPage');
        exit;
    }
}
