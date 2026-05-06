<?php
include '../backend.php';
require_once(__DIR__ . "../../vendor/autoload.php");

$id = (int) ($_GET['id'] ?? 0);
$projeto = (new Model('tbl_projeto'))->get($id);

if (!$projeto) {
    redirect('../404.php', "O Projeto que procura não existe");
}

include '_header.php';

// Obter informações do projeto
$nome_projeto = $projeto['nome_projeto'] ?? 'N/A';
$descricao_projeto = $projeto['descricao'] ?? 'N/A';
$data_inicio_projeto = $projeto['data_inicio'] ?? 'N/A';
$data_fim_projeto = $projeto['data_fim'] ?? 'N/A';
$status_projeto = $projeto['status_projeto'] ?? 'N/A';

// Obter informações da equipe se existir
$equipaModel = new Model('tbl_equipa');
$equipa = $equipaModel->find('id_projetos', $id);

if ($equipa) {
    // Obter membros da equipe
    $candidatoModel = new Model('tbl_candidato');
    $membrosEquipa = $candidatoModel->findAll('id_equipa', $equipa['id']);
} else {
    // Se não houver equipe, obter informações do candidato único que submeteu a candidatura
    $ideia_id = $projeto['id_ideia'];
    $ideia = (new Model('tbl_identificacao_ideia'))->get($ideia_id);
    $candidatura = (new Model('tbl_candidaturas'))->get($ideia['id_candidatura']);
    $candidato = (new Model('tbl_candidato'))->get($candidatura['id_candidato']);

    $nome_candidato = $candidato['nome'] ?? 'N/A';
    $data_nascimento_candidato = $candidato['data_nascimento'] ?? 'N/A';
    $genero_candidato = $candidato['sexo'] ?? 'N/A';
}
?>
<main class="container px-4 m-5">
    <div class="row">
        <?php if ($status_projeto !== 'Cancelado'): ?>
        <div class="col-lg-8">
            <!-- Seção de informações do projeto -->
            <div class="card mb-4">
                <div class="card-header">Informações do Projeto</div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($nome_projeto) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($descricao_projeto) ?></p>
                    <p class="card-text">Data de Início: <?= htmlspecialchars($data_inicio_projeto) ?></p>
                    <p class="card-text">Data de Término: <?= htmlspecialchars($data_fim_projeto) ?></p>
                    <!-- Mais informações do projeto podem ser adicionadas aqui... -->
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <?php if ($equipa): ?>
            <!-- Seção de informações da equipe -->
            <div class="card mb-4">
                <div class="card-header">Informações da Equipe</div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($equipa['nomeequipa'] ?? 'N/A') ?></h5>
                    <?php if (!empty($membrosEquipa)): ?>
                        <p class="card-text">Membros da Equipe:</p>
                        <ul class="list-group">
                            <?php foreach ($membrosEquipa as $membro): ?>
                                <li class="list-group-item"><?= htmlspecialchars($membro['email']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="card-text">Não há membros na equipe deste projeto.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Seção de informações do candidato -->
            <div class="card mb-4">
                <div class="card-header">Informações do Candidato</div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($nome_candidato) ?></h5>
                    <p class="card-text">Data de Nascimento: <?= htmlspecialchars($data_nascimento_candidato) ?></p>
                    <p class="card-text">Gênero: <?= htmlspecialchars($genero_candidato) ?></p>
                    <!-- Mais informações do candidato podem ser adicionadas aqui... -->
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-12">
            <!-- Formulário para criar relatório semanal -->
            <div class="card mb-4">
                <div class="card-header">Relatório Semanal</div>
                <div class="card-body">
                    <form action="relatorio.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_projeto" value="<?= $id ?>">
                        <?php if ($equipa): ?>
                            <input type="hidden" name="id_equipa" value="<?= $equipa['id'] ?>">
                        <?php else: ?>
                            <input type="hidden" name="id_candidatura" value="<?= $candidatura['id'] ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título do Relatório</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="conteudo" class="form-label">Conteúdo do Relatório</label>
                            <textarea class="form-control" id="conteudo" name="conteudo" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="col-lg-12">
            <div class="alert alert-danger" role="alert">
                Este projeto foi cancelado.
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include '_footer.php' ?>
