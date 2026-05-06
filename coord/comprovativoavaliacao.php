<?php
include '../backend.php';
require_once(__DIR__ . "../../vendor/autoload.php");

use Dompdf\Dompdf;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obter os dados da ideia
    $sentence = $conexion->prepare("SELECT * FROM tbl_identificacao_ideia WHERE id = :id");
    $sentence->bindParam(":id", $id);
    $sentence->execute();
    $ideia = $sentence->fetch(PDO::FETCH_ASSOC);

    if (!$ideia) {
        die('ID de Ideia não existe.');
    }

    $titulo_ideia = $ideia["titulo_ideia"];
    $sector = $ideia["sector"];
    $descri_conceito = $ideia["descri_conceito"];
    $data_submissao = $ideia["data_submissao"];

    // Obter as avaliações
    $sentence = $conexion->prepare("
    SELECT a.*, c.nome as nome, c.email as email
    FROM tbl_avaliacao a
    LEFT JOIN tbl_coordenador c ON a.id_avaliador = c.id
    WHERE a.id_ideia = :id_ideia
    ");
    $sentence->bindParam(":id_ideia", $id);
    $sentence->execute();
    $avaliacoes = $sentence->fetchAll(PDO::FETCH_ASSOC);
}

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @font-face {
            font-family: 'Times New Roman';
            src: url('caminho_para_a_fonte/Times New Roman.ttf');
        }

        body {
            font-family: 'Times New Roman', Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
            height: auto;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            margin-bottom: 10px;
        }

        .section p {
            margin: 5px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .img-responsive {
            max-width: 100%;
            height: 500px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= assets ?>/img/unicv1.png" alt="Logo" class="img-responsive">
            <h5>UNIVERSIDADE DE CABO VERDE</h5>
            <h5>CUBO DE INOVAÇÃO TECNOLÓGICA</h5>
            <h5>CONSELHO DA UNIVERSIDADE</h5>
            <h1>Comprovativo da Avaliação</h1>
            <p>Data: <strong><?php echo date('d M Y'); ?></strong></p>
        </div>

        <div class="section">
            <h2>Informações da Ideia</h2>
            <p><strong>Título:</strong> <?php echo $titulo_ideia; ?></p>
            <p><strong>Sector:</strong> <?php echo $sector; ?></p>
            <p><strong>Descrição:</strong></p>
            <p style="text-align: justify;"><?php echo $descri_conceito; ?></p>
            <p><strong>Data de Submissão:</strong> <?php echo $data_submissao; ?></p>
        </div>

        <div class="section">
            <h2>Avaliações</h2>
            <?php foreach ($avaliacoes as $avaliacao) : ?>
                <div class="avaliacao">
                    <p><strong>Avaliador:</strong> <?php echo $avaliacao['nome']; ?></p>
                    <p><strong>Email do Avaliador:</strong> <?php echo $avaliacao['email']; ?></p>
                    <p><strong>Tipo de Avaliador:</strong> <?php echo $avaliacao['tipo_avaliador']; ?></p>
                    <p><strong>Nota:</strong> <?php echo $avaliacao['nota']; ?></p>
                    <p><strong>Comentário:</strong> <?php echo $avaliacao['comentario']; ?></p>
                    <p><strong>Status:</strong> <span style="font-weight: bold;"><?php echo $avaliacao['status']; ?></span></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>

<?php
$HTML = ob_get_clean();

$dompdf = new Dompdf();
$opcao = $dompdf->getOptions();
$opcao->set(array("isRemoteEnabled" => true));
$dompdf->setOptions($opcao);
$dompdf->loadHtml($HTML);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("comprovativo_avaliacao_submissão_ideia.pdf", array("Attachment" => false));
?>