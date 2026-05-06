<?php 
include '../backend.php';

// Obter o ID do projeto a partir da URL
$id = (int) ($_GET['id'] ?? 0);

// Obter os detalhes do projeto e do supervisor a partir do banco de dados
$projetoModel = new Model('tbl_projeto');
$projeto = $projetoModel->find('id', $id);

if (!$projeto) {
    redirect('../404.php', "O Projeto que procura não existe");
    exit;
}

$supervisorModel = new Model('tbl_supervisor');
$supervisor = $supervisorModel->get($projeto['id_supervisor']);

$ideiaModel = new Model('tbl_identificacao_ideia');
$ideia = $ideiaModel->get($projeto['id_ideia']);

// Obter os dados da equipe do projeto
$equipaModel = new Model('tbl_equipa');
$equipa = $equipaModel->find('id_projetos', $id);

$membrosEquipa = [];
if ($equipa) {
    $candidatoModel = new Model('tbl_candidato');
    $membrosEquipa = $candidatoModel->findAll('id_equipa', $equipa['id']);
}
    
// Incluir o cabeçalho da página
include '_header.php';
?>

<!-- Página -->
<main class="container px-4">
    <section class="py-5">
        <div class="container px-5 my-5">
            <div class="row gx-5 align-items-center">
                <div class="col-lg-3 top">
                    <div class="d-flex align-items-center">
                        <img class="img-fluid rounded-circle me-3" src="<?= foto_supervisor($supervisor['foto']) ?>"
                            alt="Supervisor" style="max-width: 100px; height: auto;" />
                        <div>
                            <div class="fw-bold">Supervisor:</div>
                            <div class="text-muted"><?= htmlspecialchars($supervisor['nome'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Conteúdo do post -->
                    <article>
                        <!-- Cabeçalho do post -->
                        <header class="mb-4">
                            <h1 class="fw-bolder mb-1"><?= htmlspecialchars($projeto['nome_projeto']) ?></h1>
                            <div class="text-muted fst-italic mb-2">Data de Submissão:
                                <?= date('d/m/Y', strtotime($projeto['data_inicio'])) ?></div>
                            <a class="badge bg-secondary text-decoration-none link-light"
                                href="#!"><?= htmlspecialchars($ideia['sector']) ?></a>
                            <a class="badge bg-secondary text-decoration-none link-light"
                                href="#!"><?= htmlspecialchars($projeto['status_projeto']) ?></a>
                        </header>
                        <!-- Imagem de pré-visualização -->
                        <figure class="mb-4"><img class="img-fluid rounded" src="<?= assets ?>/img/gsss.jpg"
                                alt="..." /></figure>
                        <!-- Conteúdo do post -->
                        <section class="mb-5">
                            <p class="fs-5 mb-4"><?= nl2br(htmlspecialchars($projeto['descricao'])) ?></p>
                            <h2 class="fw-bolder mb-4 mt-5">Detalhes Adicionais</h2>
                            <p class="fs-5 mb-4">
                                <?= nl2br(htmlspecialchars($ideia['info_complementar'] ?? 'Informações adicionais não disponíveis.')) ?>
                            </p>
                        </section>
                    </article>
                    <!-- Seção da equipe do projeto -->
                    <section>
                        <h2 class="fw-bolder mb-4 mt-5">Equipe do Projeto</h2>
                        <?php if (!empty($membrosEquipa)): ?>
                        <ul class="list-group">
                            <?php foreach ($membrosEquipa as $membro): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($membro['email'] ?? 'N/A') ?></strong><br>
                                Data de Entrada: <?= date('d/m/Y', strtotime($membro['data_entrada'])) ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p>Não há membros na equipe deste projeto.</p>
                        <?php endif; ?>
                    </section>
                    <!-- Seção de avaliação -->
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Fim da página -->

<?php 
// Incluir o rodapé da página
include '_footer.php'; 
?>
