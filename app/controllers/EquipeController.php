<?php
require_once __DIR__ . '/../models/Equipe.php';

class EquipeController {
    private $equipe;

    public function __construct($pdo) {
        $this->equipe = new Equipe($pdo);
    } 

    public function getPdo() {
        return $this->pdo;
    }

    public function listarEquipesPorCompeticao($competicao_id) {
        return $this->equipe->listarPorCompeticao($competicao_id);
    }

    public function criarEquipe($postData) {
        if (empty($postData['nome']) || empty($postData['competicao_id'])) {
            echo "Nome da equipe e competição são obrigatórios.";
            return;
        }

        $dados = [
            'competicao_id' => $postData['competicao_id'],
            'nome' => $postData['nome'],
            'categoria' => $postData['categoria'] ?? null,
            'qr_code_url' => null 
        ];

        $this->equipe->criar($dados);

        header("Location: gerenciar_equipes.php?id=" . $postData['competicao_id']);
        exit;
    }

    public function buscarEquipePorId($id) {
        return $this->equipe->buscarPorId($id);
    }

    public function atualizarEquipe($id, $postData) {
        $dados = [
            'nome' => $postData['nome'],
            'categoria' => $postData['categoria']
        ];
        $this->equipe->atualizar($id, $dados);

        $equipe = $this->equipe->buscarPorId($id);
        header("Location: gerenciar_equipes.php?id=" . $equipe['competicao_id']);
        exit;
    }

    public function excluirEquipe($id) {
        $equipe = $this->equipe->buscarPorId($id);
        $competicao_id = $equipe['competicao_id'];
        $this->equipe->excluir($id);

        header("Location: gerenciar_equipes.php?id=" . $competicao_id);
        exit;
    }

    public function listarTodasEquipes() {
        $sql = "SELECT * FROM times ORDER BY nome ASC";
        $stmt = $this->equipe->getPdo()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
