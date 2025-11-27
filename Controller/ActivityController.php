<?php
namespace Controller;

use Model\Connection;
use Model\ActivityModel;

class ActivityController {

    private $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function showCreatePage() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        $activityModel = new ActivityModel($this->db);
        $userActivities = $activityModel->getActivitiesByUserId($_SESSION['user_id']);
        $userStats = $activityModel->getUserStats($_SESSION['user_id']);
        $title = "SAEP Saúde - Criar Atividade";
        $view_file = 'activity_view.php';
        require_once dirname(__DIR__) . '/View/template.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['user_id'];
            $tipo = $_POST['tipo_atividade'] ?? '';
            $distancia = (int)($_POST['distancia'] ?? 0);
            $duracao = (int)($_POST['duracao'] ?? 0);
            $calorias = (int)($_POST['calorias'] ?? 0);

            if (empty($tipo) || $distancia <= 0 || $duracao <= 0 || $calorias <= 0) {
                header('Location: ' . BASE_URL . '/index.php?controller=activity&action=showCreatePage&error=Todos os campos são obrigatórios e devem ser valores positivos.');
                exit;
            }

            $activityModel = new ActivityModel($this->db);
            if ($activityModel->createActivity($usuario_id, $tipo, $distancia, $duracao, $calorias)) {
                header('Location: ' . BASE_URL . '/index.php?controller=activity&action=showCreatePage&success=true');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/index.php?controller=activity&action=showCreatePage&error=Ocorreu um erro ao registrar a atividade.');
                exit;
            }
        }
        
        header('Location: ' . BASE_URL . '/index.php?controller=activity&action=showCreatePage');
        exit;
    }

    public function filter() {
        header('Content-Type: application/json');
        
        $type = $_GET['type'] ?? 'corrida';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 4;

        if (!in_array($type, ['corrida', 'caminhada', 'trilha'])) {
            echo json_encode(['error' => 'Tipo de atividade inválido.']);
            exit;
        }

        $activityModel = new ActivityModel($this->db);
        
        $atividades = $activityModel->getActivitiesByType($type, $page, $limit);
        
        foreach ($atividades as $key => $atividade) {
            $atividades[$key]['like_count'] = $activityModel->getLikeCount($atividade['id']);
            $atividades[$key]['comment_count'] = $activityModel->getCommentCount($atividade['id']);
        }

        $totalActivities = $activityModel->countActivitiesByType($type);
        $totalPages = ceil($totalActivities / $limit);

        echo json_encode([
            'activities' => $atividades,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]
        ]);
        exit;
    }
}
