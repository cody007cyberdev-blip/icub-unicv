<?php
include("../backend.php");


// Defina a função flashMessageAdicionado para aceitar uma mensagem como parâmetro
function flashMessageAdicionado($msg = null)
{
    if ($msg) {
        $_SESSION['flash_message_adicionado'] = $msg;
    }
    return isset($_SESSION['flash_message_adicionado']) ? $_SESSION['flash_message_adicionado'] : null;
}


if ($_POST) {
    $nome = (isset($_POST["nome"]) ? $_POST["nome"] : "");
    $sexo = (isset($_POST["sexo"]) ? $_POST["sexo"] : "");
    $contacto = (isset($_POST["contacto"]) ? $_POST["contacto"] : "");
    $endereco = (isset($_POST["endereco"]) ? $_POST["endereco"] : "");
    $email = (isset($_POST["email"]) ? $_POST["email"] : "");
    $password = (isset($_POST["password"]) ? $_POST["password"] : "");

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $nacionalidade = (isset($_POST["nacionalidade"]) ? $_POST["nacionalidade"] : "");
    $data_nascimento = (isset($_POST["data_nascimento"]) ? $_POST["data_nascimento"] : "");
    $foto = (isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "");
    $datahorario = date('Y-m-d H:i:s');


    // Preparar a inserção de dados 
    $sentence = $conexion->prepare("INSERT INTO 
    `tbl_candidato` (`id`, `nome`, `sexo`, `contacto`, `endereco`, `email`, `password`, `nacionalidade`, `data_nascimento`,`foto`, `data_entrada`) 
    VALUES (NULL, :nome, :sexo, :contacto, :endereco, :email, :password, :nacionalidade, :data_nascimento, :foto, :data_entrada);");

    $sentence->bindParam(":nome", $nome);
    $sentence->bindParam(":sexo", $sexo);
    $sentence->bindParam(":contacto", $contacto);
    $sentence->bindParam(":endereco", $endereco);
    $sentence->bindParam(":email", $email);
    $sentence->bindParam(":password", $hash);
    $sentence->bindParam(":nacionalidade", $nacionalidade);
    $sentence->bindParam(":data_nascimento", $data_nascimento);
    $sentence->bindParam(':data_entrada', $datahorario);

    $data_ = new DateTime();
    $nomeficheiro_foto = ($foto != '') ? $data_->getTimestamp() . 'U' . uniqid() : "";
    $tmp_foto = $_FILES["foto"]['tmp_name'];
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, PATH_UPLOADS . "/fotos_cand/$nomeficheiro_foto");
    }
    $sentence->bindParam(":foto", $nomeficheiro_foto);

    $sentence->execute();

    flashMessageAdicionado("Candidato Adicionado Com Sucesso!");
    redirect('create-cand.php');
}

?>
<?php include('_header.php') ?>


<div class="container p-4 col-8">
    <div class="card">
        <div class="card-header">
            Dados do Candidato
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" aria-describedby="helpId" placeholder="Nome">
                </div>

                <div class="mb-3">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select class="form-select" name="sexo" id="sexo">
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="contacto" class="form-label">Contato</label>
                    <input type="text" class="form-control" name="contacto" id="contacto" aria-describedby="helpId" placeholder="Contato">
                </div>
                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" name="endereco" id="endereco" aria-describedby="helpId" placeholder="Endereço">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="helpId" placeholder="Email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Password">
                </div>
                <div class="mb-3">
                    <label for="nacionalidade" class="form-label">Nacionalidade</label>
                    <input type="text" class="form-control" name="nacionalidade" id="nacionalidade" aria-describedby="helpId" placeholder="Nacionalidade">
                </div>
                <div class="mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" aria-describedby="helpId" placeholder="Data de Nascimento">
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto:</label>
                    <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-success">Adicionar Candidato</button>
                <a name="" id="" input type="button" class="btn btn-secondary" href="candidatos.php" role="button">Cancelar</a>
                <input type="reset" class="btn btn-secondary" role="button" value="Limpar" />
            </div>
        </form>
    </div>
</div>


<script>
    // Verifica se existe uma mensagem flash para adicionado e exibe-a usando SweetAlert
    <?php if ($msg = flashMessageAdicionado()) : ?>
        Swal.fire({
            icon: 'success',
            text: '<?= $msg ?>'
        });
        // Limpa a mensagem flash após a exibição
        <?php unset($_SESSION['flash_message_adicionado']); ?>
    <?php endif; ?>
</script>


<?php include('_footer.php') ?>