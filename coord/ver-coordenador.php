<?php include '../backend.php';

if ($_POST) {
    $id = $_POST['id'] ?? null;
    $nome = (isset($_POST["nome"]) ? $_POST["nome"] : "");
    $sexo = (isset($_POST["sexo"]) ? $_POST["sexo"] : "");
    $contacto = (isset($_POST["contacto"]) ? $_POST["contacto"] : "");
    $endereco = (isset($_POST["endereco"]) ? $_POST["endereco"] : "");
    $email = (isset($_POST["email"]) ? $_POST["email"] : "");
    $nacionalidade = (isset($_POST["nacionalidade"]) ? $_POST["nacionalidade"] : "");
    $data_nascimento = (isset($_POST["data_nascimento"]) ? $_POST["data_nascimento"] : "");
    $foto = (isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "");

    // foto
    $data_ = new DateTime();
    $nomeficheiro_foto = ($foto != '') ? $data_->getTimestamp() . "_" . $_FILES["foto"]['name'] : "";
    $tmp_foto = $_FILES["foto"]['tmp_name'];
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, PATH_UPLOADS . "/fotos_coord/$nomeficheiro_foto");
    }

    $update = Database::prepare("UPDATE `tbl_coordenador` SET nome=:nome, sexo=:sexo, contacto=:contacto,
        endereco=:endereco, email=:email WHERE id=:id",
        [
            ':nome' => $nome,
            ':sexo' => $sexo,
            ':contacto' => $contacto,
            ':endereco' => $endereco,
            ':email' => $email,
            ':id' => $id
        ]
    );

    if (isset($_POST["password"])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $psw = (new Model('tbl_coordenador'))->update($id, ['password' => $password]);
    }

    if ($foto != '') {
        $ft = (new Model('tbl_coordenador'))->update($id, ['foto' => $nomeficheiro_foto]);
        flashMessage("Atualuzação foto feita com Sucesso!", "success");
    }

    if ($update->rowCount() > 0 || ($psw ?? false) || ($ft ?? false)) {
        // sucesso
        flashMessage("Atualuzação feita com Sucesso!", "success");
    } else {
        // erro
        flashMessage("Não foi possivel editar o  Coordenador!", "alert");
    }
    redirect('coordenadores.php');
}
// se não for espacificado o id, retornar
if (!isset($_GET['id'])) {
    redirect('/../404.php');
}

$user = (new Model('tbl_coordenador'))->get($_GET['id']);

if (!$user) {
    flashMessage('ID de Coordenador Inválido', "alert");
    redirect('coordenadores.php');
}


include '_header.php'; ?>

<div class="container-md px-0 p-md-4 my-4">
    <h1 class="text-center mb-4">Ver Coordenador</h1>
    <div class="card w-75 mx-auto">
        <div class="card-header clearfix">
            <span>Edite os dados do Coordenador <b><?= $user['nome'] ?></b></span>
            <span class="disabled text-muted float-end">ID: <?= $user['id'] ?></span>
        </div>
        <div class="card-body ">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <div class="mb-3 row align-items-center">
                    <div class="col-4 col-md-4 col-lg-3 mx-auto">
                        <label for="foto"
                            class="rounded-circle img-thumbnail d-block w-100 mx-auto border object-fit-cover"
                            style="height: 10rem;">
                            <img class="rounded-circle" width="100%" height="100%" id="showFoto"
                                src="<?= foto_utilizador($user['foto']) ?>" alt="">
                        </label>
                        <input type="file" class="form-control d-none" name="foto" id="foto" aria-describedby="helpId"
                            placeholder="Foto" onchange="loadFile(event)" disabled />
                    </div>
                    <div class="col-12 col-md">
                        <div class="mb-3">
                            <label for="nome" class="form-label form-label-sm">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" aria-describedby="helpId"
                                placeholder="<?= $user['nome'] ?>" value="<?= $user['nome'] ?>" disabled required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label form-label-sm">Email</label>
                            <input type="email" class="form-control" name="email" id="email" aria-describedby="helpId"
                                placeholder="<?= $user['email'] ?>" value="<?= $user['email'] ?>" disabled required>
                        </div>
                    </div>
                </div>

                <div class="mb-3 form-group  row">
                    <div class="col-12 col-md">
                        <label for="sexo" class="form-label">Sexo</label>
                        <select class="form-select" name="sexo" id="sexo" disabled>
                            <option value="Masculino" <?= $user['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Feminino" <?= $user['sexo'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                        </select>
                    </div>
                    <div class="col-12 col-md">
                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" name="data_nascimento" id="data_nascimento"
                            aria-describedby="helpId" placeholder="Data de Nascimento"
                            value="<?= $user['data_nascimento'] ?? '' ?>" disabled>
                    </div>
                </div>
                <div class="mb-3 form-group row">
                    <div class="col-12 col-md">
                        <label for="contacto" class="form-label">Contato</label>
                        <input type="text" class="form-control" name="contacto" id="contacto" aria-describedby="helpId"
                            placeholder="Contato" value="<?= $user['contacto'] ?? '' ?>" disabled>
                    </div>
                    <div class="col-12 col-md">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco" id="endereco" aria-describedby="helpId"
                            placeholder="Endereço" value="<?= $user['endereco'] ?? '' ?>" disabled>
                    </div>
                </div>
                <!-- <div class="mb-3">
                    <label for="nacionalidade" class="form-label">Nacionalidade (País)</label>
                    <select class="form-select" name="nacionalidade" id="nacionalidade" aria-describedby="helpId"
                        placeholder="Nacionalidade">
                        <option value="Cabo Verde">Cabo Verde</option>
                        <option value="Estrangeiro">Outro</option>
                    </select>
                </div> -->
                <!-- <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-muted small">(Esteja atento a
                            case-sensivite)</span></label>
                    <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId"
                        placeholder="Password">
                </div> -->

        </div>
        <div class="card-footer">
            <a class="btn btn-warning me-3" onclick="enableFormFields(this)">Editar</a>
            <button type="submit" class="btn btn-primary me-3 d-none" id="btn-update">Atualizar</button>
            <a name="" id="" class="btn btn-light float-ends" onclick="history.back()" role="button">Voltar</a>

            </form>
        </div>
    </div>
</div>

<script>
    var loadFile = function (event) {
        var output = document.getElementById('showFoto');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function () {
            URL.revokeObjectURL(output.src) // free memory
        }
    };


</script>


<?php include '_footer.php' ?>