<?php
namespace Controller;

use Model\Connection;
use Model\LikeModel; // Vamos criar este Model a seguir

class LikeController {

    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function toggleLike() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $activityId = $data['activityId'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$activityId) {
            echo json_encode(['success' => false, 'error' => 'ID da atividade não fornecido.']);
            exit;
        }

        $likeModel = new LikeModel($this->db);

        // A função toggleLike vai adicionar se não existir, ou remover se já existir
        $wasLiked = $likeModel->toggleLike($userId, $activityId);

        // Pega a nova contagem de likes
        $newLikeCount = $likeModel->getLikeCount($activityId);

        echo json_encode([
            'success' => true,
            'wasLiked' => $wasLiked, // Informa se o usuário agora está curtindo ou não
            'newLikeCount' => $newLikeCount
        ]);
        exit;
    }
}
