<?php include '../backend.php';


/**
 **Codigo para eliminar Coordenador e sua foto
 * A chamada é feita com ajax pela funcao eliminar() no _footer.php
 */
if (isset($_POST['deleteID'])) {
    $id = $_POST['deleteID'];
    $user = (new Model('tbl_coordenador'))->get($id);
    $eliminado = (new Model('tbl_coordenador'))->delete($id);
    if ($eliminado) {
        eliminarFotoUsuario($user['foto']);
        flashMessage("Coordenador Eliminado com sucesso!", "success");
    } else {
        flashMessage("Não foi possivel eliminar", "error");
    }
    exit;
}

$registros = (new Model('tbl_coordenador'))->getAll();

?>
<?php include '_header.php' ?>

<div class="container px-4">
    <h1 class="mt-4">Coordenadores</h1>
    <p>
        Tabela de registro dos coordenadores
    </p>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Coordenadores</span>
            <a class="btn btn-primary float-end " href="create-coord.php" role="button">Adicionar Coordenador</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table  align-middle" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Sexo</th>
                        <th>email</th>
                        <th>contacto</th>
                        <th>endereço</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $coord) { ?>
                        <tr>
                            <td>
                                <img class="img-fuild rounded me-2" style="max-height: 36px" width="32px"
                                    src="<?= foto_coordenador($coord['foto']) ?>" alt="" onClick="imagem(this)">
                                <?= $coord['nome'] ?>
                            </td>
                            <td><?= $coord['sexo'] ?></td>
                            <td><?= $coord['email'] ?></td>
                            <td><?= $coord['contacto'] ?></td>
                            <td><?= $coord['endereco'] ?></td>
                            <td>
                                <a href="ver-coordenador.php?id=<?= $coord['id'] ?>"
                                    class="p-1 ms-2"><i class="fas fa-eye"></i></a>
                                <a href="#" onclick="eliminarUtilizador(<?= $coord['id'] ?>, '<?= $coord['nome'] ?>', 'Coordenador')"
                                    class="btn text-danger  p-1 ms-2"><i class="fas fa-trash"></i></a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

</script>

<?php include '_footer.php' ?>