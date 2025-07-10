<?php

class Equipe {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function listarPorCompeticao($competicao_id) {
        $sql = "SELECT * FROM equipes WHERE competicao_id = :competicao_id ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':competicao_id' => $competicao_id]);
        return $stmt->fetchAll();
    }

    public function criar($dados) {
        $sql = "INSERT INTO equipes (competicao_id, nome, categoria, qr_code_url, criado_em)
                VALUES (:competicao_id, :nome, :categoria, :qr_code_url, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':competicao_id' => $dados['competicao_id'],
            ':nome' => $dados['nome'],
            ':categoria' => $dados['categoria'],
            ':qr_code_url' => $dados['qr_code_url'] ?? null
        ]);
        return true;
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM times WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function atualizar($id, $dados) {
        $sql = "UPDATE equipes SET nome = :nome, categoria = :categoria WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':categoria' => $dados['categoria'],
            ':id' => $id
        ]);
        return true;
    }

    public function excluir($id) {
        $sql = "DELETE FROM equipes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return true;
    }
}
