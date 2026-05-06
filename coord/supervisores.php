<?php include '../backend.php';

/**
 **Codigo para eliminar Supervisor e sua foto
 * A chamada é feita com ajax pela funcao eliminar() no _footer.php
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['deleteID'])) {
        $id = $_POST['deleteID'];
        $user = (new Model('tbl_supervisor'))->get($id);
        $deleted = (new Model('tbl_supervisor'))->delete($id);
        if ($deleted) {
            eliminarFotoUsuario($user['foto']);
            flashMessage("Supervisor Eliminado com sucesso!", "Success");
        } else {
            flashMessage("Não foi possivel eliminar", "error");
        }
    }
    exit;
} // fim de delete supervisor


$registros = (new Model('tbl_supervisor'))->getAll();

?>
<?php include '_header.php' ?>

<div class="container-md px-4">
    <h1 class="mt-4">Supervisores</h1>
    <p>
        Tabela de registro dos supervisores
    </p>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Supervisores</span>
            <a class="btn btn-primary float-end " href="create-supervisor.php" role="button">Adicionar Supervisor</a>
        </div>
        <div class="card-body table-responsive align-middle">
            <table class="table align-middle text-start" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Sexo</th>
                        <th>email</th>
                        <th>contacto</th>
                        <th>endereço</th>
                        <th>area de atuação</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $supervisor) {
                        $id = $supervisor['id'];
                        $foto = $supervisor['foto'];
                        $nome = $supervisor['nome'];
                        $email = $supervisor['email'];
                        $sexo = $supervisor['sexo'];
                        $contacto = $supervisor['contacto'];
                        $endereco = $supervisor['endereco'];
                        $area_atuacao = $supervisor['area_atuacao'];

                        ?>
                        <tr>
                            <td>
                                <div class="me-2 d-inline-block" style="width: 32px; height: 32px; object-fit: cover;">
                                    <img class="object-fit-cover rounded-circle border border-1"
                                        style="width: 100%; height: 100%;" src="<?= foto_supervisor($supervisor['foto']) ?>"
                                        alt="" onClick="imagem(this)" />
                                </div>

                                <?= $nome ?>
                            </td>
                            <td><?= $sexo ?></td>
                            <td><?= $email ?></td>
                            <td><?= $contacto ?></td>
                            <td><?= $endereco ?></td>
                            <td><?= $area_atuacao ?></td>
                            <td>
                                <a title="Ver informacoes do supervisor" class=" text-primary me-2"
                                    href="ver-supervisor.php?id=<?= $id ?>"><i class="fas fa-eye"></i></a>
                                <a href="#" onclick="eliminarUtilizador(<?= $id ?>, '<?= $nome ?>', 'Supervisor')"
                                    class=" text-danger p-1"><i class="fas fa-trash"></i></a>
                                <!-- eliminar utilizador esta no _footer do coord, serve para todos -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modals -->
<!-- data-bs-toggle="modal" data-bs-target="#modalId" class="me-2 p-1"> -->
<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" role="dialog"
    aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Supervisor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row " style="min-height: 10rem">
                <div class="w-100 text-center">
                    <img src="s" class="imgthumbnail rounded-circle shadow-sm" alt="Imagem">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" readonly value="nome">
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" href="ver-supervisor.php?id=<?= $id ?>" class="btn btn-sm btn-warning">Editar</a>
            </div>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
<script>
    const myModal = new bootstrap.Modal(
        document.getElementById("modalId"),
        options,
    );
</script>

<?php include '_footer.php' ?>