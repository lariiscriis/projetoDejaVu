<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: paginLoginCadastro.php");
    exit;
}

if (isset($_GET['vinilId'])) {
    $vinilId = $_GET['vinilId'];

    try {
        $pdo->beginTransaction();

        $sql = "DELETE FROM vinil_artista WHERE cd_vinil = :vinilId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':vinilId', $vinilId);
        $stmt->execute();

        $sql = "DELETE FROM vinil WHERE cd_vinil = :vinilId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':vinilId', $vinilId);
        $stmt->execute();

        $pdo->commit();

        header("Location: adm.php?msg=Vinil excluído com sucesso");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao excluir o vinil: " . $e->getMessage();
    }
} else {
    echo "ID do vinil não encontrado.";
    exit;
}
?>
