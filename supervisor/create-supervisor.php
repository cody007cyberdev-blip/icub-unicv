<?php
include("../backend.php");

if ($_POST) {
    $nome = (isset($_POST["nome"]) ? $_POST["nome"] : "");
    $sexo = (isset($_POST["sexo"]) ? $_POST["sexo"] : "");
    $contacto = (isset($_POST["contacto"]) ? $_POST["contacto"] : "");
    $endereco = (isset($_POST["endereco"]) ? $_POST["endereco"] : "");
    $email = (isset($_POST["email"]) ? $_POST["email"] : "");
    $password = (isset($_POST["password"]) ? $_POST["password"] : "");
    $nacionalidade = (isset($_POST["nacionalidade"]) ? $_POST["nacionalidade"] : "");
    $data_nascimento = (isset($_POST["data_nascimento"]) ? $_POST["data_nascimento"] : "");
    $foto = (isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "");

    $sentence = $conexion->prepare("INSERT INTO 
    `tbl_supervisor` (`id`, `nome`, `sexo`, `contacto`, `endereco`, `email`, `password`, `nacionalidade`, `data_nascimento`,`foto`) 
    VALUES (NULL, :nome, :sexo, :contacto, :endereco, :email, :password, :nacionalidade, :data_nascimento, :foto);");

    $sentence->bindParam(":nome", $nome);
    $sentence->bindParam(":sexo", $sexo);
    $sentence->bindParam(":contacto", $contacto);
    $sentence->bindParam(":endereco", $endereco);
    $sentence->bindParam(":email", $email);
    $sentence->bindParam(":password", $password);
    $sentence->bindParam(":nacionalidade", $nacionalidade);
    $sentence->bindParam(":data_nascimento", $data_nascimento);

    $data_ = new DateTime();
    $nomeficheiro_foto = ($foto != '') ? $data_->getTimestamp() . "_" . $_FILES["foto"]['name'] : "";
    $tmp_foto = $_FILES["foto"]['tmp_name'];
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, "../../fotos_candidatos/" . $nomeficheiro_foto);
    }
    $sentence->bindParam(":foto", $nomeficheiro_foto);

    $sentence->execute();

    $mensagem = "Candidato Adicionado Com Sucesso!";
    header("Location:index.php?mensagem=" . $mensagem);
}

?>


<br />
<div class="card">
    <div class="card-header">
        Dados do Candidato
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" aria-describedby="helpId" placeholder="Nome">
            </div>

            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" name="sexo" id="sexo">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
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
            <button type="submit" class="btn btn-success">Adicionar registo</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>