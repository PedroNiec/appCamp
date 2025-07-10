<?php

class Jogador {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarPorEquipe($equipe_id) {
        $sql = "SELECT * FROM jogadores WHERE time_id = :time_id ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':time_id' => $equipe_id]);
        return $stmt->fetchAll();
    }

    public function criar($dados) {
        $sql = "INSERT INTO jogadores (time_id, nome, cpf, data_nascimento, contato, rg, posicao)
                VALUES (:time_id, :nome, :cpf, :data_nascimento, :contato, :rg, :posicao)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':time_id' => $dados['time_id'],
            ':nome' => $dados['nome'],
            ':posicao' => $dados['posicao'],
            ':cpf' => $dados['cpf'],
            ':rg' => $dados['rg'],
            ':data_nascimento' => $dados['data_nascimento'],
            ':contato' => $dados['contato']
        ]);
        return true;
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM jogadores WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function atualizar($id, $dados) {
        $sql = "UPDATE jogadores
                SET nome = :nome, cpf = :cpf, data_nascimento = :data_nascimento, contato = :contato
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':cpf' => $dados['cpf'],
            ':data_nascimento' => $dados['data_nascimento'],
            ':contato' => $dados['contato'],
            ':id' => $id
        ]);
        return true;
    }

    public function excluir($id) {
        $sql = "DELETE FROM jogadores WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return true;
    }
}
