<?php
require_once __DIR__ . '/../models/Competicao.php';

class CompeticaoController {
    public $pdo;
    private $competicao;

    public function __construct($pdo) {
        $this->competicao = new Competicao($pdo);
    }

    public function criarCompeticao($postData, $fileData) {
        $regulamentoPath = null;

        if (isset($fileData['regulamento']) && $fileData['regulamento']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../storage/uploads/regulamentos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($fileData['regulamento']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($fileData['regulamento']['tmp_name'], $filePath)) {
                $regulamentoPath = 'storage/uploads/regulamentos/' . $fileName;
            }
        }

        $this->competicao->criar($postData, $regulamentoPath);

        header("Location: dashboard.php");
        exit;
    }

    public function listarCompeticoesDoUsuario($usuarioId) {
    $sql = "SELECT c.*, m.nome AS modalidade_nome
            FROM competicoes c
            JOIN modalidades m ON c.modalidade_id = m.id
            WHERE c.criado_por = :usuario_id
            ORDER BY c.data_inicio DESC";

    $stmt = $this->competicao->getPdo()->prepare($sql);
    $stmt->execute([':usuario_id' => $usuarioId]);
    return $stmt->fetchAll();
    }

    public function buscarCompeticaoPorId($competicao_id, $usuario_id) {
    $sql = "SELECT c.*, m.nome AS modalidade_nome
            FROM competicoes c
            JOIN modalidades m ON c.modalidade_id = m.id
            WHERE c.id = :competicao_id AND c.criado_por = :usuario_id
            LIMIT 1";

    $stmt = $this->competicao->getPdo()->prepare($sql);
    $stmt->execute([
        ':competicao_id' => $competicao_id,
        ':usuario_id' => $usuario_id
    ]);

    return $stmt->fetch();
}


}
