<?php
namespace Model;

class CommentModel {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getCommentsByActivityId(int $activityId) {
        $sql = "SELECT c.*, u.nome_usuario, u.imagem AS usuario_imagem 
                FROM comentarios c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.atividade_id = :activityId
                ORDER BY c.createdAt ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addComment(int $userId, int $activityId, string $commentText) {
        $sql = "INSERT INTO comentarios (usuario_id, atividade_id, comentario) VALUES (:userId, :activityId, :commentText)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
        $stmt->bindParam(':commentText', $commentText, \PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $lastId = $this->db->lastInsertId();
            $newCommentStmt = $this->db->prepare("SELECT c.*, u.nome_usuario, u.imagem AS usuario_imagem FROM comentarios c JOIN usuarios u ON c.usuario_id = u.id WHERE c.id = :id");
            $newCommentStmt->bindParam(':id', $lastId, \PDO::PARAM_INT);
            $newCommentStmt->execute();
            return $newCommentStmt->fetch(\PDO::FETCH_ASSOC);
        }
        return false;
    }
}
