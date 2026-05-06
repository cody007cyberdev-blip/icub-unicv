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
            flashMessage("Candidato Eliminado com sucesso!", "Success");
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
                                    <img class="object-fit-cover rounded-circle border border-1" style="width: 100%; height: 100%;" src="<?= foto_supervisor($supervisor['foto']) ?>" alt="" onClick="imagem(this)" />
                                </div>

                                <?= $nome ?>
                            </td>
                            <td><?= $sexo ?></td>
                            <td><?= $email ?></td>
                            <td><?= $contacto ?></td>
                            <td><?= $endereco ?></td>
                            <td><?= $area_atuacao ?></td>
                            <td>
                                <a title="Ver informacoes do supervisor" href="ver-supervisor.php?id=<?= $id ?>" class="me-2 p-1"><i class="fas fa-eye"></i></a>
                                <a href="#" onclick="eliminarUtilizador(<?= $id ?>, '<?= $nome ?>', 'Supervisor')" class=" text-danger p-1" ><i class="fas fa-trash"></i></a>
                                <!-- eliminar utilizador esta no _footer do coord, serve para todos -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include '_footer.php' ?>