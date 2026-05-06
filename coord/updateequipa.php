<?php
include '../backend.php';

if (!isset($_GET['id'])) {
    // Redireciona para a página de visualização de equipes se o ID da equipe não estiver definido
    flashMessage("Falha em obter id", 'error');
    redirect('equipa.php');
    exit;
}

$idEquipa = $_GET['id'];

// Consulta para obter os dados da equipe
$queryEquipa = "
    SELECT e.id, e.nomeequipa, e.id_projetos, p.nome_projeto
    FROM tbl_equipa e
    INNER JOIN tbl_projeto p ON e.id_projetos = p.id
    WHERE e.id = :id_equipa
";
$stmtEquipa = $conexion->prepare($queryEquipa);
$stmtEquipa->bindParam(':id_equipa', $idEquipa);
$stmtEquipa->execute();
$equipa = $stmtEquipa->fetch(PDO::FETCH_ASSOC);

if (!$equipa) {
    // Redireciona para a página de visualização de equipes se a equipe não for encontrada
    flashMessage("Equipa não encontrada", 'error');
    redirect('equipa.php');
    exit;
}

// Consulta para obter todos os projetos disponíveis
$queryProjetos = "SELECT id, nome_projeto FROM tbl_projeto";
$stmtProjetos = $conexion->prepare($queryProjetos);
$stmtProjetos->execute();
$projetos = $stmtProjetos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter todos os candidatos disponíveis que não estão em nenhuma equipe e que estão na equipe atual
$queryCandidatos = "
    SELECT id, nome, email, id_equipa
    FROM tbl_candidato
    WHERE (id_equipa IS NULL OR id_equipa = :id_equipa)
";
$stmtCandidatos = $conexion->prepare($queryCandidatos);
$stmtCandidatos->bindParam(':id_equipa', $idEquipa);
$stmtCandidatos->execute();
$candidatos = $stmtCandidatos->fetchAll(PDO::FETCH_ASSOC);

// Exclusão da equipe se o formulário for submetido via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_team') {
        // Executa a exclusão da equipe e seus candidatos associados
        $queryDeleteEquipe = "DELETE FROM tbl_equipa WHERE id = :id_equipa";
        $stmtDeleteEquipe = $conexion->prepare($queryDeleteEquipe);
        $stmtDeleteEquipe->bindParam(':id_equipa', $idEquipa);
        $stmtDeleteEquipe->execute();

        // Atualiza o campo id_equipa dos candidatos associados à equipe
        $queryUpdateCandidato = "UPDATE tbl_candidato SET id_equipa = NULL WHERE id_equipa = :id_equipa";
        $stmtUpdateCandidato = $conexion->prepare($queryUpdateCandidato);
        $stmtUpdateCandidato->bindParam(':id_equipa', $idEquipa);
        $stmtUpdateCandidato->execute();

        // Exibe mensagem de sucesso usando flashMessage
        flashMessage("Equipe excluída com sucesso", 'success');
        exit; // Saída direta após exclusão para evitar redirecionamentos duplos
    } elseif ($_POST['action'] === 'update_team') {
        // Captura os dados do formulário de edição
        $nomeequipa = $_POST['nomeequipa'];
        $id_projetos = $_POST['id_projetos'];
        $id_candidatos = isset($_POST['id_candidatos']) ? $_POST['id_candidatos'] : [];

        // Atualiza os dados da equipe na tabela tbl_equipa
        $queryUpdateEquipe = "UPDATE tbl_equipa SET id_projetos = :id_projetos, nomeequipa = :nomeequipa WHERE id = :id_equipa";
        $stmtUpdateEquipe = $conexion->prepare($queryUpdateEquipe);
        $stmtUpdateEquipe->bindParam(':id_projetos', $id_projetos);
        $stmtUpdateEquipe->bindParam(':nomeequipa', $nomeequipa);
        $stmtUpdateEquipe->bindParam(':id_equipa', $idEquipa);
        $stmtUpdateEquipe->execute();

        // Atualiza os candidatos associados à equipe
        foreach ($candidatos as $candidato) {
            if (in_array($candidato['id'], $id_candidatos)) {
                // Associar candidato à equipe
                if ($candidato['id_equipa'] != $idEquipa) {
                    $queryUpdateCandidato = "UPDATE tbl_candidato SET id_equipa = :id_equipa WHERE id = :id_candidato";
                    $stmtUpdateCandidato = $conexion->prepare($queryUpdateCandidato);
                    $stmtUpdateCandidato->bindParam(':id_equipa', $idEquipa);
                    $stmtUpdateCandidato->bindParam(':id_candidato', $candidato['id']);
                    $stmtUpdateCandidato->execute();
                }
            } else {
                // Desassociar candidato da equipe se já estava associado a esta equipe
                if ($candidato['id_equipa'] == $idEquipa) {
                    $queryUpdateCandidato = "UPDATE tbl_candidato SET id_equipa = NULL WHERE id = :id_candidato";
                    $stmtUpdateCandidato = $conexion->prepare($queryUpdateCandidato);
                    $stmtUpdateCandidato->bindParam(':id_candidato', $candidato['id']);
                    $stmtUpdateCandidato->execute();
                }
            }
        }

        // Exibe mensagem de sucesso usando flashMessage
         // Exibe mensagem de sucesso usando flashMessage
         flashMessage("Equipe atualizada com sucesso", 'success');
         redirect('equipa.php');
         exit;
    }
}

// Inclui o cabeçalho da página
include '_header.php';
?>

<div class="container col-10 p-4">
    <h1>Editar Equipe</h1>
    <!-- Verifica se há mensagens a serem exibidas e as exibe -->
    
    <form id="editTeamForm" method="post" action="">
        <div class="form-group mb-2">
            <label for="nomeequipa">Nome da Equipe</label>
            <input type="text" class="form-control" id="nomeequipa" name="nomeequipa" value="<?= htmlspecialchars($equipa['nomeequipa']) ?>" required>
        </div>
        <div class="form-group mb-2">
            <label for="id_projetos">Selecione o Projeto</label>
            <select class="form-select" id="id_projetos" name="id_projetos" required>
                <?php foreach ($projetos as $projeto) : ?>
                    <option value="<?= htmlspecialchars($projeto['id']) ?>" <?= $projeto['id'] == $equipa['id_projetos'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($projeto['nome_projeto']) ?>
                    </option>
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
                        <input class="form-check-input" type="checkbox" name="id_candidatos[]" value="<?= htmlspecialchars($candidato['id']) ?>" id="member<?= $key ?>"
                            <?= isset($candidato['id_equipa']) && $candidato['id_equipa'] == $idEquipa ? 'checked' : (isset($candidato['id_equipa']) && $candidato['id_equipa'] ? 'disabled' : '') ?>>
                        <label for="member<?= $key ?>"><?= $candidato['id'] . ' - <b>' . $candidato['nome'] . '</b> <i class=\'text-muted small\'>( ' . $candidato['email'] . ' )</i>' ?></label>
                        <?php if (isset($candidato['id_equipa']) && $candidato['id_equipa'] && $candidato['id_equipa'] != $idEquipa) : ?>
                            <small class="text-muted"> (Pertence a outra equipe)</small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="button" class="btn btn-danger mt-3" id="deleteTeamBtn">Excluir Equipe</button>
        <button type="submit" class="btn btn-primary mt-3" name="action" value="update_team">Salvar Alterações</button>
    </form>
</div>

<script>
    // Script para confirmar a exclusão da equipe usando SweetAlert e AJAX
    document.addEventListener('DOMContentLoaded', function () {
        const deleteTeamBtn = document.getElementById('deleteTeamBtn');

        deleteTeamBtn.addEventListener('click', function () {
            Swal.fire({
                title: 'Desejas mesmo eliminar a equipa?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envia o formulário de exclusão via AJAX
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'updateequipa.php?id=<?= $idEquipa ?>', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            Swal.fire(
                                'Eliminado!',
                                'A equipe foi eliminada com sucesso.',
                                'success'
                            ).then(() => {
                                window.location.href = 'equipa.php';
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                'Ocorreu um problema ao eliminar a equipe.',
                                'error'
                            );
                        }
                    };
                    xhr.send('action=delete_team');
                }
            });
        });
    });

    // Script para filtrar candidatos
    document.getElementById('pesquisa_candidatos').addEventListener('input', function () {
        let filter = this.value.toUpperCase();
        let div = document.getElementById('lista_candidatos');
        let items = div.querySelectorAll('div.list-group-item');

        items.forEach(function (item) {
            let label = item.getElementsByTagName('label')[0];
            let textValue = label.textContent || label.innerText;
            if (textValue.toUpperCase().indexOf(filter) > -1) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

<?php include '_footer.php' ?>
