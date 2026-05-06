<?php include '../backend.php';

// Eliminar candidato
if (isset($_POST['deleteID'])) {
    $id = $_POST['deleteID'];
    $user = (new Model('tbl_candidato'))->get($id);
    $eliminado = (new Model('tbl_candidato'))->delete($id);
    if ($eliminado) {
        eliminarFotoUsuario($user['foto']);
        flashMessage("Candidato Eliminado com sucesso!", "success");
    } else {
        flashMessage("Não foi possivel eliminar", "error");
    }
    exit;
}


$candidatos = (new Model('tbl_candidato'))->getAll();
?>
<?php include '_header.php' ?>

<div class="container px-4">
    <h1 class="mt-4">Getstão de Candidatos</h1>
    <p>
        Tabela de registro dos candidatos
    </p>
    <?php // estatisticas
    $total = (int) Database::prepare('SELECT COUNT(*) FROM tbl_candidato')->fetchColumn() ?? 0;
    $mulheres = (int) Database::prepare('SELECT COUNT(*) FROM tbl_candidato WHERE sexo=?', ['F'])->fetchColumn() ?? 0;
    $homens = (int) Database::prepare('SELECT COUNT(*) FROM tbl_candidato WHERE sexo=?', ['M'])->fetchColumn() ?? 0;
    $idade_media = (int) Database::prepare('SELECT AVG(YEAR(CURRENT_DATE) - YEAR(data_nascimento)) FROM tbl_candidato;')->fetchColumn() ?? 0;
    ?>
    <div class="row mb-2">
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card bg-primary bg-gradient text-white h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Total</div>
                            <div class="text-lg fw-bold"><?= $total ?></div>
                        </div>
                        <i class="bi bi-person-badge fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card  bg-info bg-gradient text-white  h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small"> Homens</div>
                            <p class="text-lg fw-bold"><?= $homens ?></p>
                        </div>
                        <i class="bi bi-gender-male fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card  text-white  h-75" style="background: linear-gradient(#ffcacb 5%, pink 90%)">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small"> Mulheres</div>
                            <p class="text-lg fw-bold"><?= $mulheres ?></p>
                        </div>
                        <i class="bi bi-gender-female fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-0">
            <div class="card bg-warning bg-gradient text-white h-75">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Idade Média</div>
                            <div class="text-lg fw-bold"><?= $idade_media ?> anos</div>
                        </div>
                        <i class="fas fa-cake-candles fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i><span>Candidatos</span>
            <a class="btn btn-primary float-end " href="create-cand.php" role="button">Adicionar Candidato</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table  align-middle" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Sexo</th>
                        <th>Idade</th>
                        <th>Morada</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidatos as $candidato) {
                        $dob = new DateTime($candidato['data_nascimento'] ?? '');
                        $idade = (new DateTime('now'))->diff($dob)->y;
                        ?>
                        <tr>
                            <td>
                                <div class="me-2 d-inline-block" style="width: 32px; height: 32px; object-fit: cover;">
                                    <img class="object-fit-cover rounded-circle border border-1"
                                        style="width: 100%; height: 100%;" src="<?= foto_candidato($candidato['foto']) ?>"
                                        alt="" onClick="imagem(this)" />
                                </div>

                                <?= $candidato['nome'] ?>
                            </td>
                            <td><?= $candidato['email'] ?></td>
                            <td><?= $candidato['sexo'] ?></td>
                            <td><?= $idade ?></td>
                            <td><?= $candidato['endereco'] ?></td>
                            <td>
                                <form action="" method="post" class="text-center">
                                    <a href="ver-candidato.php?id=<?= $candidato['id'] ?>" class="me-2"><i
                                            class="fas fa-eye"></i></a>
                                    <a class="btn p-0 text-danger ms-2" href="#"
                                        onclick="eliminarUtilizador(<?= $candidato['id'] ?>, '<?= $candidato['nome'] ?>', 'Candidato');"
                                        data-role="Candidato"><i class="fas fa-trash"></i></a>
                                </form>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '_footer.php' ?>