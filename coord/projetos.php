<?php
include '../backend.php';

// Consulta SQL para obter todos os registros da tabela tbl_projeto, incluindo o nome do supervisor associado a cada projeto
$query = 'SELECT p.id, p.nome_projeto, p.data_inicio, p.data_fim, s.nome AS nome_supervisor, p.status_projeto,p.id_ideia
          FROM tbl_projeto p 
          LEFT JOIN tbl_supervisor s ON p.id_supervisor = s.id';
$registros = Database::prepare($query)->fetchAll();
?>

<?php include '_header.php' ?>

<div class="container-md px-4">
    <h1 class="mt-4">Gestão Geral de Projetos</h1>
    <p>Visão geral de projetos para gestão de coordenadores</p>
    <div class="mb-4">
        <a href="criar_projeto.php" class="btn btn-primary">Criar Projeto</a>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Projetos</span>
        </div>
        <div class="card-body table-responsive align-middle">
            <table class="table align-middle table-striped table-sm" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $projeto): ?>
                    <tr>
                        <td><?= $projeto['id'] ?></td>
                        <td><?= $projeto['nome_projeto'] ?></td>
                        <td><?= $projeto['data_inicio'] ?></td>
                        <td><?= $projeto['data_fim'] ?></td>
                        <td><?= $projeto['nome_supervisor'] ?: 'Não Especificado' ?></td>
                        <td><?= $projeto['status_projeto'] ?></td>
                        <td>
                                <div class="btn-group w-100">
                                    <a type="button" class="btn btn-sm btn-warning" href="ver-projeto.php?id=<?= $projeto['id']?>">Detalhes</a>
                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="gerir-projeto.php?id=<?= $projeto['id'] ?>" ><i class="fas fa-gear me-2"></i>Gerir Projeto</a>
                                        <a class="dropdown-item"href="comprovativoavaliacao.php?id=<?= $projeto['id_ideia'] ?>"><i class="fas fa-file-pdf me-2"></i>Documento do Projeto</a>
                                    </div>
                                    
                                </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '_footer.php' ?>
