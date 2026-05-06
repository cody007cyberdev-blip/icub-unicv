<?php
require '../backend.php';
include '_header.php';

// Obtenha o perfil do candidato atual
$perfil = (new Model('tbl_candidato'))->get($_SESSION['usuario']['id']);
$candidatoId = $perfil['id'];

// Obtenha as candidaturas do candidato atual
$candidaturas = (new Model('tbl_candidaturas'))->findAll('id_candidato', $candidatoId);
?>

<div class="row">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Submisao Ideias</h4>
            <p class="card-text fw-thin">Lista de Ideias que submeteste. <span class="small text-muted">Clique
                    para mais informações</span></p>
        </div>

        <?php if (!empty($candidaturas)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Título (Ideia)</th>
                        <th>Nota</th>
                        <th>Avaliação</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidaturas as $cand): ?>
                        <?php
                        // Obtenha os dados da ideia associada à candidatura
                        $ideia = (new Model('tbl_identificacao_ideia'))->find('id_candidatura', $cand['id']);

                        // Verifica se a ideia foi encontrada
                        if ($ideia) {
                            // Obtenha a avaliação da ideia
                            $avaliacao = (new Model('tbl_avaliacao'))->find('id_ideia', $ideia['id']);
                        }
                        ?>
                        <tr>
                            <td><?= $cand['id'] ?></td>
                            <td><?= $cand['email'] ?></td>
                            <td><?= $ideia['titulo_ideia'] ?? 'N/A' ?></td>
                            <td><?= $avaliacao['nota'] ?? 'Sem Avaliação' ?></td>
                            <td><?= $cand['avaliacao'] ?></td>
                            <td>
                                <?php if ($cand['avaliacao'] === 'Aprovado' || $cand['avaliacao'] === 'Recusado'): ?>
                                    <a href="projects.php?id_candidatura=<?= $cand['id'] ?>&id_ideia=<?= $ideia['id'] ?>"
                                        class="btn btn-success btn-sm">Projeto</a>
                                    <div class="btn-group ">
                                    <?php endif; ?>



                                    <div class="dropdown open">
                                        <button class="btn btn-sm me-1 btn-primary dropdown-toggle" type="button" id="triggerId"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Comprovativos
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="triggerId">
                                            <a href="<?= coordenador ?>/comprovativosubmissao.php?id=<?= $ideia['id'] ?>"
                                                class="dropdown-item">Comprovativo de Submissao</a>
                                            <a href="<?= coordenador ?>/comprovativoavaliacao.php?id=<?= $ideia['id'] ?>"
                                                class="dropdown-item">Comprovativo de Aprovaçao</a>
                                        </div>
                                    </div>

                                </div>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="m-5 text-center">
                <a name="" id="" class="btn btn-danger" href="<?= BASE_URL ?>/candidatura.php" role="button">Submeter
                    Candidatura</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</main>

<?php include '_footer.php' ?>