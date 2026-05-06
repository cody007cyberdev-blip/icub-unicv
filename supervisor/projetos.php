<?php
include '../backend.php';

// Registros de todos os projetos do supervisor logado
$registros = (new Model('tbl_projeto'))->findAll('id_supervisor', $_SESSION['usuario']['id']);
include '_header.php';
?>

<div class="container-md px-4">
    <h1 class="mt-4">Meus Projetos</h1>
    <p>
        Lista geral dos Projetos a supervisionar
    </p>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Projetos</span>
        </div>
        <div class="card-body table-responsive align-middle">
            <table class="table align-middle table-striped" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Sector</th>
                        <th>Líder</th>
                        <th>Estado</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $projeto) {
                        // Pegar os dados do projeto
                        $id = $projeto['id'];
                        $titulo = $projeto['nome_projeto'];
                        $descricao = $projeto['descricao'];
                        $data_inicio = $projeto['data_inicio'];
                        $data_fim = $projeto['data_fim'];
                        $estado = $projeto['status_projeto'];  // Certifique-se de que a coluna 'fase' existe na tabela
                        $ideia_id = $projeto['id_ideia'];  // Suposição de que 'id_ideia' é o líder do projeto
                        $ideia = (new Model('tbl_identificacao_ideia'))->get($ideia_id);
                        $sector=$ideia['sector'];
                        if ($ideia) {
                            $candidatura = (new Model('tbl_candidaturas'))->get($ideia['id_candidatura']);
                            if ($candidatura) {
                                $candidato = (new Model('tbl_candidato'))->get($candidatura['id_candidato']);
                                $nome_candidato = $candidato ? $candidato['nome'] : 'N/A';
                            } else {
                                $nome_candidato = 'N/A';
                            }
                        } else {
                            $nome_candidato = 'N/A';
                        }

                        // Preencher tabela
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($id) ?></td>
                            <td><?= htmlspecialchars($titulo) ?></td>
                            <td><?= htmlspecialchars($sector ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($nome_candidato) ?></td>
                            <td><?= htmlspecialchars($estado) ?></td>
                            <td>
                                <a href="ver-projeto.php?id=<?= htmlspecialchars($id) ?>"
                                   class='btn btn-outline-primary btn-sm me-2'>Inspecionar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '_footer.php'; ?>
