<?php
// Incluir arquivo de configuração do banco de dados e autoload do Dompdf
include '../backend.php';
require_once(__DIR__ . "../../vendor/autoload.php");

use Dompdf\Dompdf;

// Verificar se foi passado um ID válido via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obter os dados da ideia
    $queryIdeia = "SELECT * FROM tbl_identificacao_ideia WHERE id = :id";
    $stmt = $conexion->prepare($queryIdeia);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $ideia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ideia) {
        die('ID de Ideia não existe.');
    }

    // Consulta para obter os dados do candidato associado à ideia
    $queryCandidato = "SELECT c.nome, c.data_nascimento, c.sexo, ca.area_formacao, ca.ano_curso, 
                              ca.num_estudante, ca.endereco, ca.nif, ca.telefone, ca.telemovel, c.email
                       FROM tbl_candidato c
                       INNER JOIN tbl_candidaturas ca ON c.id = ca.id_candidato
                       WHERE ca.id = :id_candidatura";
    $stmt = $conexion->prepare($queryCandidato);
    $stmt->bindParam(":id_candidatura", $ideia['id_candidatura']);
    $stmt->execute();
    $candidato = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$candidato) {
        die('Candidato associado não encontrado.');
    }

    // Configuração do documento PDF com Dompdf
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="pt">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Comprovativo de Submissão de Ideia</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
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
        </style>
    </head>

    <body>
        <div class="header">
            <img src="<?= assets ?>/img/unicv1.png" alt="Logo Universidade de Cabo Verde">
            <h5>UNIVERSIDADE DE CABO VERDE</h5>
            <h5>CUBO DE INOVAÇÃO TECNOLÓGICA</h5>
            <h5>CONSELHO DA UNIVERSIDADE</h5>
        </div>
        <div class="section">
            <h1>Comprovativo de Submissão de Ideia</h1>
        </div>
        <div class="section">
            <h2>Dados da Ideia</h2>
            <p><strong>Título da Ideia:</strong> <?php echo $ideia['titulo_ideia']; ?></p>
            <p><strong>Setor:</strong> <?php echo $ideia['sector']; ?></p>
            <p><strong>Descrição do Conceito:</strong></p>
            <p><?php echo $ideia['descri_conceito']; ?></p>
            <p><strong>Estado da Ideia:</strong> <?php echo $ideia['estado']; ?></p>
            <p><strong>Informações Complementares:</strong></p>
            <p><?php echo $ideia['info_complementar']; ?></p>
            <p><strong>Data de Submissão:</strong> <?php echo date('d/m/Y H:i:s', strtotime($ideia['data_submissao'])); ?></p>
        </div>
        <div class="section">
            <h2>Dados do Candidato</h2>
            <p><strong>Nome:</strong> <?php echo $candidato['nome']; ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo date('d/m/Y', strtotime($candidato['data_nascimento'])); ?></p>
            <p><strong>Gênero:</strong> <?php echo $candidato['sexo'] == 'M' ? "Masculino" : "Feminino"; ?></p>
            <p><strong>Área de Formação:</strong> <?php echo $candidato['area_formacao']; ?></p>
            <p><strong>Ano do Curso:</strong> <?php echo $candidato['ano_curso']; ?></p>
            <p><strong>Número de Estudante:</strong> <?php echo $candidato['num_estudante']; ?></p>
            <p><strong>Endereço:</strong> <?php echo $candidato['endereco']; ?></p>
            <p><strong>NIF:</strong> <?php echo $candidato['nif']; ?></p>
            <p><strong>Telefone:</strong> <?php echo $candidato['telefone']; ?></p>
            <p><strong>Telemóvel:</strong> <?php echo $candidato['telemovel']; ?></p>
            <p><strong>E-mail:</strong> <?php echo $candidato['email']; ?></p>
        </div>
    </body>

    </html>
<?php
    $html = ob_get_clean();

    // Inicializar Dompdf para gerar o PDF
    $dompdf = new Dompdf();
    $opcao = $dompdf->getOptions();
    $opcao->set(array("isRemoteEnabled" => true));
    $dompdf->setOptions($opcao);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Definir orientação e tamanho do papel
    $dompdf->render();

    // Gerar o nome do arquivo
    $filename = "comprovativo_submissão_ideia.pdf";

    // Forçar o download do PDF gerado
    $dompdf->stream($filename, array('Attachment' => false));
} else {
    die('ID não foi fornecido.');
}
?>