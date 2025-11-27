<?php
namespace Controller;

use Model\Connection;
use Model\CommentModel; // Vamos criar este Model a seguir

class CommentController {

    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    // Função para buscar e retornar os comentários de uma atividade em formato JSON
    public function getComments() {
        header('Content-Type: application/json');
        $activityId = $_GET['activityId'] ?? null;

        if (!$activityId) {
            echo json_encode(['success' => false, 'error' => 'ID da atividade não fornecido.']);
            exit;
        }

        $commentModel = new CommentModel($this->db);
        $comments = $commentModel->getCommentsByActivityId((int)$activityId);

        echo json_encode(['success' => true, 'comments' => $comments]);
        exit;
    }

    // Função para adicionar um novo comentário
    public function addComment() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $activityId = $data['activityId'] ?? null;
        $commentText = trim($data['commentText'] ?? '');
        $userId = $_SESSION['user_id'];

        if (!$activityId || empty($commentText)) {
            echo json_encode(['success' => false, 'error' => 'Dados inválidos.']);
            exit;
        }

        $commentModel = new CommentModel($this->db);
        $newComment = $commentModel->addComment($userId, (int)$activityId, $commentText);

        if ($newComment) {
            echo json_encode(['success' => true, 'comment' => $newComment]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Falha ao adicionar comentário.']);
        }
        exit;
    }
}
