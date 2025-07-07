<?php

class Competicao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getPdo() {
        return $this->pdo;
    }


    public function criar($dados, $regulamentoPath) {

        $dados = $this->calculoTimesPorGrupo($dados['qtd_times'], $dados['qtd_grupos'], $dados);

        $sql = "INSERT INTO competicoes 
            (nome, modalidade_id, local, data_inicio, data_fim, categoria, regras, regulamento_url, criado_por, qtd_times, qtd_grupos, qtd_times_grupo) 
            VALUES (:nome, :modalidade_id, :local, :data_inicio, :data_fim, :categoria, :regras, :regulamento_url, :criado_por, :qtd_times, :qtd_grupos, :qtd_times_grupo)";



        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':modalidade_id' => $dados['modalidade_id'],
            ':local' => $dados['local'],
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim' => $dados['data_fim'],
            ':categoria' => $dados['categoria'],
            ':regras' => $dados['regras'],
            ':regulamento_url' => $regulamentoPath,
            ':criado_por' => $_SESSION['user_id'],
            ':qtd_times' => $dados ['qtd_times'],
            ':qtd_grupos'=> $dados['qtd_grupos'],
            ':qtd_times_grupo' => $dados ['qtd_times_grupo']
        ]);

        return true;
    }

    public function calculoTimesPorGrupo ($qtd_times, $qtd_grupos, $dados) {

        $dados['qtd_times_grupo'] =  $qtd_times / $qtd_grupos;

        return $dados;
    }
}
