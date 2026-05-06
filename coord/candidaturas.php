<?php
include '../backend.php';

$registros = (new Model('tbl_candidaturas'))->getAll();

// Obtém o total de candidaturas
$total = count($registros);

// Consulta ao banco de dados para obter o número de candidaturas aprovadas
$aprovado = (int) Database::prepare('SELECT COUNT(*) FROM tbl_candidaturas WHERE avaliacao = ?', ['Aprovado'])->fetchColumn() ?? 0;

// Consulta ao banco de dados para obter o número de candidaturas rejeitadas
$rejeitado = (int) Database::prepare('SELECT COUNT(*) FROM tbl_candidaturas WHERE avaliacao = ?', ['Recusado'])->fetchColumn() ?? 0;

// O número de candidaturas pendentes pode ser calculado subtraindo o total do número de candidaturas aprovadas e rejeitadas
$pendente = $total - $aprovado - $rejeitado;
?>
<?php include '_header.php' ?>

<div class="container-md px-4">
    <h1 class="mt-4">Submissão de Ideias</h1>
    <p>
        Tabela de registro de ideias submetidas pelos candidatos
    </p>

    <div class="row mb-2">
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card bg-primary bg-gradient text-white h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Total</div>
                            <div class="text-lg fw-bold"><?= $total ?></div>
                        </div>
                        <i class="fas fa-user-tag fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card  bg-success bg-gradient text-white  h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small"> Aprovados</div>
                            <p class="text-lg fw-bold"><?= $aprovado ?></p>
                        </div>
                        <i class="fas fa-thumbs-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card  text-white bg-danger bg-gradient  h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small"> Rejeitados</div>
                            <p class="text-lg fw-bold"><?= $rejeitado ?></p>
                        </div>
                        <i class="fas fa-thumbs-down fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card bg-warning bg-gradient text-white h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Pendentes</div>
                            <div class="text-lg fw-bold"><?= $pendente ?></div>
                        </div>
                        <i class="fas fa-hand fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Ideias Submetidas</span>
            <!-- <div class="btn btn-warning float-end ms-3">Gerir Relatorio</div> -->
        </div>
        <div class="card-body table-responsive align-middle">
            <table class="table align-middle table-striped" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Candidato</th>
                        <th>Setor</th>
                        <th>Fase</th>
                        <th>Estado</th>
                        <th>Data Submissão</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $candidatura) {
                        $id = $candidatura['id'];
                        $candidato_id = $candidatura['id_candidato'];
                        $candidato = (new Model('tbl_candidato'))->get($candidato_id);
                        $ideia = (new Model('tbl_identificacao_ideia'))->find('id_candidatura', $id);

                        $titulo = $ideia['titulo_ideia'];
                        $status = $candidatura['avaliacao'] ?? 'Pendente';
                        $data = (new DateTime($ideia['data_submissao']))->format("d F Y");

                        ?>
                        <tr>
                            <td><?= $id ?> </td>
                            <td><span class=""><?= $titulo ?></span></td>
                            <td>
                                <a href="ver-candidato.php?id=<?= $candidato_id ?>"
                                    class='btn btn-link link-dark text-nowrap'><?= $candidato['nome'] ?></a>
                            </td>
                            <td><?= $ideia['sector'] ?></td>
                            <td><?= $ideia['estado'] ?></td>
                            <td><?= $status ?></td>
                            <td><?= $data ?></td>
                            <td>
                                <div class="btn-group w-100">
                                    <a type="button" class="btn btn-sm btn-primary" href="ver-candidatura.php?id=<?= $id ?>">Ver</a>
                                    <?php if ($status === 'Aprovado'): ?>
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="criar_projeto.php?id=<?= $id ?>"><i class="fas fa-add me-2"></i>Criar Projeto</a>
                                        <a class="dropdown-item" href="comprovativoavaliacao.php?id=<?= $id ?>"><i class="fas fa-file-pdf me-2"></i>Doc. Avaliação</a>
                                        <a class="dropdown-item" href="comprovativosubmissao.php?id=<?= $id ?>"><i class="fas fa-file-pdf me-2"></i>Doc. Submissão</a>
                                    </div>
                                    
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '_footer.php' ?>