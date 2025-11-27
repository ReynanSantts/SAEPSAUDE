<?php
namespace Model;

class UserModel {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function findUserByEmail(string $email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createUser(string $nome, string $email, string $nome_usuario, string $senha_hash, string $nome_imagem) {
        $sql = "INSERT INTO usuarios (nome, email, nome_usuario, senha, imagem, createdAt, updatedAt) 
                VALUES (:nome, :email, :nome_usuario, :senha, :imagem, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':nome', $nome, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':nome_usuario', $nome_usuario, \PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha_hash, \PDO::PARAM_STR);
        $stmt->bindParam(':imagem', $nome_imagem, \PDO::PARAM_STR);

        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
