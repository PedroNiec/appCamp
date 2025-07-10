<?php
require_once __DIR__ . '/../models/Jogador.php';

class JogadorController {
    private $jogador;

    public function __construct($pdo) {
        $this->jogador = new Jogador($pdo);
    }

    public function listarJogadoresPorEquipe($equipe_id) {
        return $this->jogador->listarPorEquipe($equipe_id);
    }

    public function criarJogador($postData) {
        if (empty($postData['nome']) || empty($postData['equipe_id'])) {
            echo "Nome e equipe são obrigatórios.";
            return;
        }

        $dados = [
            'time_id' => $postData['equipe_id'],
            'nome' => $postData['nome'],
            'posicao' => $postData['posicao'],
            'cpf' => $postData['cpf'] ?? null,
            'rg' => $postData['rg'] ?? null,
            'data_nascimento' => $postData['data_nascimento'] ?? null,
            'contato' => $postData['contato'] ?? null
        ];

        $this->jogador->criar($dados);

        header("Location: gerenciar_jogadores.php?id=" . $postData['equipe_id']);
        exit;
    }

    public function buscarJogadorPorId($id) {
        return $this->jogador->buscarPorId($id);
    }

    public function atualizarJogador($id, $postData) {
        $dados = [
            'nome' => $postData['nome'],
            'cpf' => $postData['cpf'],
            'rg' => $postData['rg'],
            'data_nascimento' => $postData['data_nascimento'],
            'contato' => $postData['contato']
        ];

        $this->jogador->atualizar($id, $dados);

        $jogador = $this->jogador->buscarPorId($id);
        header("Location: gerenciar_jogadores.php?id=" . $jogador['equipe_id']);
        exit;
    }

    public function excluirJogador($id) {
        $jogador = $this->jogador->buscarPorId($id);
        $equipe_id = $jogador['equipe_id'];

        $this->jogador->excluir($id);

        header("Location: gerenciar_jogadores.php?id=" . $equipe_id);
        exit;
    }
}
