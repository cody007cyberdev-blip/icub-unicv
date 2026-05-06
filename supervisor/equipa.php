<?php
include '../backend.php';

// Verifica se o ID do supervisor está definido na sessão
if (!isset($_SESSION['usuario']['id'])) {
    // Redireciona ou trata o caso em que o ID do supervisor não está definido
    exit("ID do supervisor não encontrado na sessão.");
}

$idSupervisor = $_SESSION['usuario']['id'];

// Consulta para obter os dados das equipes e dos projetos associados ao supervisor atual
$queryEquipes = "
    SELECT e.id, p.id AS id_projeto, e.nomeequipa, e.dataentrada, p.nome_projeto AS tituloprojeto
    FROM tbl_equipa e
    INNER JOIN tbl_projeto p ON e.id_projetos = p.id
    WHERE p.id_supervisor = :id_supervisor
";
$stmt = $conexion->prepare($queryEquipes);
$stmt->bindParam(':id_supervisor', $idSupervisor);
$stmt->execute();
$equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '_header.php' ?>
<div class="container px-4">
    <div class="d-flex justify-content-between my-5 align-items-center">
        <h1>Gestão de Equipes</h1>
        <a href="createequipa.php" class="btn btn-success">Adicionar Nova Equipe</a>
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
                                    <a href="ver-candidato.php?id=<?= $candidato['id'] ?>" class="small link-dark">
                                        <i class="fas fa-user me-2"></i><?= $candidato['email'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="updateequipa.php?id=<?= $equipa['id'] ?>" class="btn btn-primary mt-3">Editar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '_footer.php'; ?>
