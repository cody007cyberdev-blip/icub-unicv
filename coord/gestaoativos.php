<?php 
include '../backend.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $ativo = (new Model('gestao_ativos'))->get($id);
        $deleted = (new Model('gestao_ativos'))->delete($id);
        if ($deleted) {
            eliminarFotoUsuario($ativo['foto']);
            echo json_encode(["message" => "Ativo eliminado com sucesso", "status" => "success"]);
        } else {
            echo json_encode(["message" => "Falha ao eliminar Ativo", "status" => "error"]);
        }
        exit;
    }
}

$registros = (new Model('gestao_ativos'))->getAll();
$coordenadores = (new Model('tbl_coordenador'))->getAll();
$coordenadores_assoc = [];
foreach ($coordenadores as $coordenador) {
    $coordenadores_assoc[$coordenador['id']] = $coordenador['nome'];
}

include '_header.php'; 
?>

<div class="container px-4">
    <h1 class="mt-4">Ativos</h1>
    <p>Tabela de registro dos ativos</p>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Ativos</span>
            <a class="btn btn-primary float-end" href="create-ativos.php" role="button">Adicionar Ativo</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table  align-middle" id="datatablesSimple">
                <thead >
                    <tr >
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Foto</th>
                        <th>Coordenador</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($registros)) {
                        foreach ($registros as $ativo) {
                            // Verifique se a chave existe no array
                            $nomeCoord = isset($coordenadores_assoc[$ativo['id_coordenador']]) ? $coordenadores_assoc[$ativo['id_coordenador']] : 'Coordenador desconhecido';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($ativo['titulo'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($ativo['descricao'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><button onclick="imagemLink('<?= foto_ativo($ativo['foto']) ?>')" class="btn btn-outline-primary btn-sm w-75 mx-auto">ver Imagem</button></td>
                                <td><?= htmlspecialchars($nomeCoord, ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="update-ativos.php?id=<?= htmlspecialchars($ativo['id'], ENT_QUOTES, 'UTF-8') ?>" class="mx-2"><i class="fas fa-pen"></i></a>
                                    <a href="#" onclick="eliminarAtivo(<?= htmlspecialchars($ativo['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($ativo['titulo'], ENT_QUOTES, 'UTF-8') ?>')" class="btn p-0 text-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5'>Nenhum ativo encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function eliminarAtivo(id, titulo) {
    Swal.fire({
        title: 'Desejas mesmo eliminar este Ativo?',
        text: 'Ativo: ' + titulo,
        showCancelButton: true,
        confirmButtonText: 'Sim, Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: 'gestaoativos.php',
                data: { delete: id },
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        icon: response.status,
                        text: response.message
                    }).then(() => {
                        if (response.status === "success") {
                            location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Não foi possível eliminar o Ativo.'
                    });
                }
            });
        }
    });
}
</script>

<?php include '_footer.php'; ?>
