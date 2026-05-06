<?php
require '../backend.php';
include '_header.php';

// Verifica se o ID do candidato está definido na sessão
if (!isset($_SESSION['usuario']['id'])) {
    // Redireciona ou trata o caso em que o ID do candidato não está definido
    exit("ID do candidato não encontrado na sessão.");
}

$candidatoId = $_SESSION['usuario']['id'];

// Consulta para obter as equipes associadas ao candidato atual
$queryEquipes = "
    SELECT e.id, e.nomeequipa, e.dataentrada, p.id AS id_projeto, p.nome_projeto AS tituloprojeto
    FROM tbl_equipa e
    INNER JOIN tbl_projeto p ON e.id_projetos = p.id
    WHERE e.id IN (SELECT id_equipa FROM tbl_candidato WHERE id = :id_candidato)
";
$stmt = $conexion->prepare($queryEquipes);
$stmt->bindParam(':id_candidato', $candidatoId);
$stmt->execute();
$equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container px-4">
    <div class="d-flex justify-content-between my-5 align-items-center">
        <h1>Minhas Equipes</h1>
       
    </div>
    <div class="row">
        <?php foreach ($equipes as $equipa) : 
            // Consulta para obter os candidatos da equipe atual
            $candidatos = (new Model('tbl_candidato'))->findAll('id_equipa', $equipa['id']);
            $projeto = (new Model('tbl_projeto'))->get($equipa['id_projeto']);
        ?>
            <div class="col-12 col-md-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?= htmlspecialchars($equipa['nomeequipa']) ?></h4>
                        <p class="card-text"><strong>Projeto:</strong> <?= htmlspecialchars($equipa['tituloprojeto']) ?></p>
                        <p class="card-text"><strong>Supervisor:</strong> <?= (new Model('tbl_supervisor'))->get($projeto['id_supervisor'])['nome'] ?></p>
                        <p class="card-text"><strong>Data de Entrada:</strong> <?= date('d/m/Y', strtotime($equipa['dataentrada'])) ?></p>
                        <p class="card-text"><strong>Membros de Equipe:</strong></p>
                        <ul class="">
                            <?php foreach ($candidatos as $candidato) : ?>
                                <li class="mb-1">
                                    <a href="#" class="small link-dark">
                                        <i class="fas fa-user me-2"></i><?= $candidato['email'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                       
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '_footer.php'; ?>
