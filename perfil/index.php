<?php require '../backend.php';



// o script contem os codigos php a realizar quando submeter o fromulario.
// Razao pra isso é manter o codigo mais limpo, mas nada impede de fazer aqui
include '_script.php';

include '_header.php'; ?>

<style>
    #myProfilePicture {
        position: relative;
        height: 100%;
        max-width: 196px;
    }

    #pp-div {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        width: 196px;
        height: 196px;
        margin: auto;
    }
</style>

<div class="row">
    <div class="col-xl-4">
        <!-- Profile picture card-->
        <div class="card mb-4 mb-xl-0">
            <div class="card-header">Imagem de Perfil</div>
            <div class="card-body text-center">
                <!-- Profile picture image-->
                <div id="pp-div">
                    <img class="img-account-profile img-thumbnail rounded-circle mb-2 object-fit-cover user-select-none"
                        src="<?= foto_candidato($perfil['foto']) ?>" alt="image profile" width="196px" height="196px"
                        id="myProfilePicture" onClick="imagem(this)" />
                </div>
                <!-- Profile picture help block-->
                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                <!-- Profile picture upload button-->
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <label class="btn btn-primary rounded">
                        <input name="foto" id="imgInput" type="file" accept="image/*" onchange="loadFile(event)"
                            class="btn btn-primary d-none" type="button">
                        Atualizar Foto
                    </label>
                    <input type="submit" id="fotoOK" name="submitFoto" class="btn btn-success d-none" value="Salvar">
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Detalhes do Utilizador</div>
                <div class="card-body">
                    <form action="" method="post" class="form">
                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input class="form-control disabled" id="inputEmailAddress" type="email"
                                placeholder="Enter your email address" value="<?= $perfil['email'] ?>" disabled />
                            <small class="small text-danger align-middle">O email nao pode ser alterado</small>
                        </div>
                        <!-- Form Group (username)-->
                        <div class="row form-group gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small form-label form-label-sm mb-1 text-muted" for="inputUsername">
                                    Nome Completo
                                </label>
                                <input name="nome" class="form-control" id="inputUsername" type="text"
                                    placeholder="Nome Sobrenome" value="<?= $perfil['nome'] ?? '' ?>" />
                            </div>
                            <div class="col-md-6">
                                <label class="small form-label form-label-sm mb-1 text-muted">Sexo</label>
                                <select class="form-select" name="sexo">
                                    <option value='M' <?= $perfil['sexo'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                                    <option value="F" <?= $perfil['sexo'] == 'F' ? 'selected' : '' ?>>Feminino</option>
                                </select>
                            </div>
                        </div>
                        <!-- Form Row -->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (phone number)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputPhone">Contacto Telefone</label>
                                <input name="contacto" class="form-control" id="inputPhone" type="tel"
                                    placeholder="Enter your phone number" value="<?= $perfil['contacto'] ?? '' ?>" />
                            </div>
                            <!-- Form Group (location)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLocation">Morada</label>
                                <input name="endereco" class="form-control" id="inputLocation" type="text"
                                    placeholder="Enter your location" value="<?= $perfil['endereco'] ?? '' ?>" />
                            </div>
                        </div>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">

                            <!-- Form Group (birthday)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputBirthday">Data Nascimento</label>
                                <input class="form-control" id="inputBirthday" type="date" name="dataNasc"
                                    placeholder="Enter your birthday" value="<?= $perfil['data_nascimento'] ?? '' ?>" />
                            </div>
                            <!-- Form Group (organization name)-->
                            <div class="col mb-3">
                                <label class="small mb-1" for="inputEmailAddress">Nacionalidade</label>
                                <input name="nacionalidade" class="form-control" type="text"
                                    placeholder="Enter your email address"
                                    value="<?= $perfil['nacionalidade'] ?? 'desconhecida' ?>" />
                            </div>
                        </div>
                        <!-- Save changes button-->
                        <input class="btn btn-primary" name="submitPersonal" type="submit" value="Atualizar" />
                    </form>
                </div>
            </div>
        </div>
</div>

</div>
</main>

<script>
    var loadFile = function (event) {
        var output = document.getElementById('myProfilePicture');
        var ok = document.getElementById('fotoOK');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function () {
            URL.revokeObjectURL(output.src) // free memory
        }
        ok.classList.remove('d-none');
    };
</script>


<?php include '_footer.php' ?>