<?php 
include '../backend.php';

// Verificar se o usuário está logado como supervisor
$perfil = Database::prepare('SELECT * FROM tbl_supervisor WHERE id = ?', [
    $_SESSION['usuario']['id']
])->fetch();

if (!$perfil) {
    // Redirecionar ou mostrar mensagem de erro se o usuário não for um supervisor
    exit('Acesso não autorizado.');
}

// Consulta para obter todos os projetos disponíveis do supervisor logado
$query = 'SELECT p.id, p.nome_projeto 
          FROM tbl_projeto p
          WHERE NOT EXISTS (
              SELECT 1 FROM tbl_equipa e WHERE e.id_projetos = p.id
          )
          AND p.id_supervisor = :id_supervisor';

$params = [
    ':id_supervisor' => $_SESSION['usuario']['id']
];
$projetos = Database::prepare($query, $params)->fetchAll();

// Consulta para obter todos os candidatos disponíveis que não estão em nenhuma equipe
$queryCandidatos = "SELECT id, nome, email FROM tbl_candidato WHERE id_equipa IS NULL";
$stmtCandidatos = $conexion->prepare($queryCandidatos);
$stmtCandidatos->execute();
$candidatos = $stmtCandidatos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $nomeequipa = $_POST['nomeequipa'];
    $id_projetos = $_POST['id_projetos'];
    $id_candidatos = $_POST['id_candidatos'];

    // Verificar se o projeto selecionado já tem equipe
    $queryCheckEquipe = "SELECT 1 FROM tbl_equipa WHERE id_projetos = :id_projetos LIMIT 1";
    $stmtCheckEquipe = $conexion->prepare($queryCheckEquipe);
    $stmtCheckEquipe->bindParam(':id_projetos', $id_projetos);
    $stmtCheckEquipe->execute();
    $equipeExistente = $stmtCheckEquipe->fetchColumn();

    if ($equipeExistente) {
        flashMessage("O projeto selecionado já possui uma equipe.", 'danger');
        redirect('createequipa.php');
        exit;
    }

    // Inserir nova equipe na tabela tbl_equipa
    $queryInsertEquipe = "INSERT INTO tbl_equipa (id_projetos, nomeequipa) VALUES (:id_projetos, :nomeequipa)";
    $stmtInsertEquipe = $conexion->prepare($queryInsertEquipe);
    $stmtInsertEquipe->bindParam(':id_projetos', $id_projetos);
    $stmtInsertEquipe->bindParam(':nomeequipa', $nomeequipa);
    $stmtInsertEquipe->execute();
    $idEquipe = $conexion->lastInsertId();

    // Atualizar os candidatos selecionados com o id da nova equipe
    foreach ($id_candidatos as $id_candidato) {
        $queryUpdateCandidato = "UPDATE tbl_candidato SET id_equipa = :id_equipa WHERE id = :id_candidato";
        $stmtUpdateCandidato = $conexion->prepare($queryUpdateCandidato);
        $stmtUpdateCandidato->bindParam(':id_equipa', $idEquipe);
        $stmtUpdateCandidato->bindParam(':id_candidato', $id_candidato);
        $stmtUpdateCandidato->execute();
    }

    // Redirecionar para a página de visualização de equipes ou exibir uma mensagem de sucesso
    flashMessage("Equipa criada com sucesso.", 'success');
    redirect('equipa.php');
    exit;
}
?>
<?php include '_header.php' ?>
<div class="container col-10 p-4">
    <h1>Criar Nova Equipe</h1>
    <form method="post" action="createequipa.php">
        <div class="form-group mb-2">
            <label for="nomeequipa">Nome da Equipe</label>
            <input type="text" class="form-control" id="nomeequipa" name="nomeequipa" required>
        </div>
        <div class="form-group mb-2">
            <label for="id_projetos">Selecione o Projeto</label>
            <select class="form-select" id="id_projetos" name="id_projetos" required>
                <option value="" selected disabled>Selecione o projeto disponível</option>
                <?php foreach ($projetos as $projeto) : ?>
                    <option value="<?= htmlspecialchars($projeto['id']) ?>"><?= htmlspecialchars($projeto['nome_projeto']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label for="pesquisa_candidatos">Pesquisar Candidatos</label>
            <input type="text" class="form-control" id="pesquisa_candidatos" placeholder="Digite o nome do candidato">
        </div>
        <div class="form-group">
            <label for="id_candidatos">Selecione os Candidatos</label>
            <div id="lista_candidatos" class="list-group">
                <?php foreach ($candidatos as $key => $candidato) : ?>
                    <div class="list-group-item">
                        <input class="form-check-input" type="checkbox" name="id_candidatos[]" value="<?= htmlspecialchars($candidato['id']) ?>" id="member<?=$key?>">
                        <label for="member<?=$key?>"><?= $candidato['id'] . ' - <b>'. $candidato['nome'] . '</b> <i class=\'text-muted small\'>('. $candidato['email'] .')</i>' ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Criar Equipe</button>
    </form>
</div>

<script>
document.getElementById('pesquisa_candidatos').addEventListener('keyup', function() {
    var filtro = this.value.toLowerCase();
    var candidatos = document.querySelectorAll('#lista_candidatos .list-group-item');

    candidatos.forEach(function(candidato) {
        var nome = candidato.textContent.toLowerCase();
        if (nome.includes(filtro)) {
            candidato.style.display = '';
        } else {
            candidato.style.display = 'none';
        }
    });
});
</script>

<?php include '_footer.php'; ?>
