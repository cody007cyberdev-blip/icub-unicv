<?php
include '../backend.php';
require_once(__DIR__ . "../../vendor/autoload.php");

use Dompdf\Dompdf;

if (isset($_POST['id_projeto'], $_POST['titulo'], $_POST['conteudo'])) {
    $id_projeto = (int) $_POST['id_projeto'];
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];

    // Obter informações do projeto, equipe ou candidato conforme as modificações anteriores
    $projeto = (new Model('tbl_projeto'))->get($id_projeto);
    $candidatura = null;
    $candidato = null;

    // Verificar se foi submetido o id_equipa ou id_candidatura
    if (isset($_POST['id_equipa'])) {
        $id_equipa = (int) $_POST['id_equipa'];
        $equipa = (new Model('tbl_equipa'))->get($id_equipa);
        if ($equipa) {
            $candidatoModel = new Model('tbl_candidato');
            $membrosEquipa = $candidatoModel->findAll('id_equipa', $id_equipa);
        }
    } elseif (isset($_POST['id_candidatura'])) {
        $id_candidatura = (int) $_POST['id_candidatura'];
        $candidatura = (new Model('tbl_candidaturas'))->get($id_candidatura);
        if ($candidatura) {
            $candidato = (new Model('tbl_candidato'))->get($candidatura['id_candidato']);
        }
    }

    // Verificar se as informações necessárias foram encontradas
    if (!$projeto || (!$equipa && !$candidato)) {
        die('Dados do projeto, equipe ou candidato não encontrados.');
    }

    // Atribuir variáveis com fallback para valores padrão
    $nome_projeto = $projeto['nome_projeto'] ?? 'N/A';
    $descricao_projeto = $projeto['descricao'] ?? 'N/A';
    $data_inicio_projeto = $projeto['data_inicio'] ?? 'N/A';
    $data_fim_projeto = $projeto['data_fim'] ?? 'N/A';

    if ($equipa) {
        $nome_equipa = $equipa['nomeequipa'] ?? 'N/A';
    } elseif ($candidato) {
        $nome_candidato = $candidato['nome'] ?? 'N/A';
        $data_nascimento_candidato = $candidato['data_nascimento'] ?? 'N/A';
        $genero_candidato = $candidato['sexo'] ?? 'N/A';
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
        height: auto;
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
            <h1>Relatório Semanal</h1>
            <p>Data: <strong><?= date('d M Y'); ?></strong></p>
        </div>

        <div class="section">
            <h2>Informações do Projeto</h2>
            <p><strong>Nome:</strong> <?= htmlspecialchars($nome_projeto); ?></p>
           
            <p><strong>Descrição:</strong></p>
            <p style="text-align: justify;"><?= htmlspecialchars($descricao_projeto); ?></p>
            <p><strong>Data de Início:</strong> <?= htmlspecialchars($data_inicio_projeto); ?></p>
            <p><strong>Data de Término:</strong> <?= htmlspecialchars($data_fim_projeto); ?></p>
        </div>

        <?php if ($equipa): ?>
        <div class="section">
            <h2>Informações da Equipe</h2>
            <p><strong>Nome da Equipe:</strong> <?= htmlspecialchars($nome_equipa); ?></p>
            <?php if (!empty($membrosEquipa)): ?>
                <p><strong>Membros da Equipe:</strong></p>
                <ul>
                    <?php foreach ($membrosEquipa as $membro): ?>
                    <li><?= htmlspecialchars($membro['nome']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há membros nesta equipe.</p>
            <?php endif; ?>
        </div>
        <?php elseif ($candidato): ?>
        <div class="section">
            <h2>Informações do Candidato</h2>
            <p><strong>Nome:</strong> <?= htmlspecialchars($nome_candidato); ?></p>
            <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($data_nascimento_candidato); ?></p>
            <p><strong>Gênero:</strong> <?= htmlspecialchars($genero_candidato); ?></p>
        </div>
        <?php endif; ?>

        <div class="section">
            <h2>Conteúdo do Relatório</h2>
            <p><?= htmlspecialchars($conteudo); ?></p>
        </div>

    </div>
</body>

</html>

<?php
    $HTML = ob_get_clean();

    // Configuração do Dompdf para gerar o PDF
    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    $options->set(['isRemoteEnabled' => true]);
    $dompdf->setOptions($options);

    $dompdf->loadHtml($HTML);
    $dompdf->setPaper('A4', 'portrait'); // Pode ajustar para landscape se necessário
    $dompdf->render();

    // Definindo o nome do arquivo de saída e enviando para o navegador
    $dompdf->stream("relatorio_semanal.pdf", array("Attachment" => false));
}
?>
