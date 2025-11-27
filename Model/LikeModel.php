<?php
namespace Model;

class LikeModel {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function hasLiked(int $userId, int $activityId): bool {
        $stmt = $this->db->prepare("SELECT id FROM likes WHERE usuario_id = :userId AND atividade_id = :activityId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    public function addLike(int $userId, int $activityId): bool {
        $stmt = $this->db->prepare("INSERT INTO likes (usuario_id, atividade_id) VALUES (:userId, :activityId)");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removeLike(int $userId, int $activityId): bool {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE usuario_id = :userId AND atividade_id = :activityId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function toggleLike(int $userId, int $activityId): bool {
        if ($this->hasLiked($userId, $activityId)) {
            $this->removeLike($userId, $activityId);
            return false; // O usuário não está mais curtindo
        } else {
            $this->addLike($userId, $activityId);
            return true; // O usuário agora está curtindo
        }
    }

    public function getLikeCount(int $activityId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE atividade_id = :activityId");
        $stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
