<?php
namespace Model;

class ActivityModel {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getActivitiesByType(string $type, int $page = 1, int $limit = 4) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, u.nome_usuario, u.imagem AS usuario_imagem 
                FROM atividades a 
                JOIN usuarios u ON a.usuario_id = u.id 
                WHERE a.tipo_atividade = :type 
                ORDER BY a.createdAt DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':type', $type, \PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countActivitiesByType(string $type): int {
        $sql = "SELECT COUNT(*) FROM atividades WHERE tipo_atividade = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':type', $type, \PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getUserStats(int $userId) {
        $sql = "SELECT COUNT(*) as total_atividades, SUM(quantidade_calorias) as total_calorias 
                FROM atividades 
                WHERE usuario_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return [
            'total_atividades' => $result['total_atividades'] ?? 0,
            'total_calorias' => $result['total_calorias'] ?? 0
        ];
    }
}
