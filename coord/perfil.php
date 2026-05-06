<?php include '../backend.php';

// para obter as informacoes do perfil do coordenador
$perfil = Database::prepare('SELECT * FROM tbl_coordenador WHERE id = ?', [
    $_SESSION['usuario']['id']
])->fetch();

/**
 * Codigo para atualizar a foto de perfil
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $data_ = new DateTime();
    $nomeficheiro_foto = $data_->getTimestamp() . '_' . $_FILES['foto']['name'];
    $tmp_foto = $_FILES["foto"]['tmp_name'];
    // valida a foto, se foi upload
    if ($tmp_foto != '') {
        // update para a pasta e verifica ser foi updated
        if (move_uploaded_file($tmp_foto, PATH_UPLOADS . "/fotos_coord/$nomeficheiro_foto")) {
            // elimina old foto
            $old_foto = PATH_UPLOADS . "/fotos_coord/" . $perfil['foto'];
            if (file_exists($old_foto)) {
                unlink($old_foto);
            }
            // adiciona a nova foto na base de dados
            $model = new Model('tbl_coordenador', 'id');
            $model->update($perfil['id'], [
                'foto' => $nomeficheiro_foto
            ]);
            // troca a foto na session
            $_SESSION['usuario']['foto'] = $nomeficheiro_foto;
            flashMessage('Sua Foto de Perfil foi Atualizado');
            flashStatus('success');
        }
    } else {
        flashMessage('Tente novamente, parece que nenhuma foto foi selecionada');
        flashStatus('info');

    }
    // redireciona para a mesma pagina (post redirect get)
    redirect('perfil.php');
}

/**
 * Codigo para atualizar a dados pessoais
 */
if (isset($_POST['submitPersonal'])) {
    // sanetizar as entradas para previnir sql Injection
    $unome = htmlspecialchars($_POST['nome']);
    $usexo = htmlspecialchars($_POST['sexo']);
    $ucontacto = htmlspecialchars($_POST['contacto']);
    $uendereco = htmlspecialchars($_POST['endereco']);
    $udatNasc = htmlspecialchars($_POST['dataNasc']);
    $uorganizacao = htmlspecialchars($_POST['organizacao']);

    $ok = (new Model('tbl_coordenador'))->update($perfil['id'], [
        'nome' => $unome,
        'sexo' => $usexo,
        'contacto' => $ucontacto,
        'endereco' => $uendereco
    ]);

    if ($ok) {
        flashMessage('Seus Dados foi Atualizado com sucesso');
        flashStatus('success');
        $_SESSION['usuario']['nome'] = $unome;
    } else {
        flashMessage('Erro ao Atualizar dados');
        flashStatus('error');
    }


    // redireciona para a mesma pagina (post redirect get)
    redirect('perfil.php');
}

// codigo para atualizar password
if (isset($_POST['submitPassword'])) {
    $id = $perfil['id'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    if($newPassword == $confirmPassword){
        // palavrapasse confirmado
        if (password_verify($currentPassword, $perfil['password'])) {
            // palavra passe antiga OK
            $password = password_hash($newPassword, PASSWORD_DEFAULT);
            $atualizado = (new Model('tbl_coordenador'))->update($id, ['password' => $password]);
            if($atualizado){
                flashMessage("Palavra-passe Alterado com sucesso!",'success');
            }
        } else {
            flashMessage("Palavra-passe actual Incorreta!", 'warning');
        }
    }
    redirect('perfil.php');
}

if (isset($_POST['submitdeleteAccount'])) {

}
?>
<?php include '_header.php' ?>

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

<div class="container p-5">

    <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Imagem de Perfil</div>
                <div class="card-body text-center">
                    <!-- Profile picture image-->
                    <div id="pp-div">
                        <img class="img-account-profile img-thumbnail rounded-circle mb-2 object-fit-cover user-select-none"
                            src="<?= foto_coordenador($perfil['foto']) ?>" alt="image profile" width="196px"
                            height="196px" id="myProfilePicture" onClick="imagem(this)" />
                    </div>
                    <!-- Profile picture help block-->
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    <!-- Profile picture upload button-->
                    <form action="perfil.php" method="post" enctype="multipart/form-data">
                        <label class="btn btn-primary rounded">
                            <input name="foto" id="imgInput" type="file" accept="image/*" onchange="loadFile(event)"
                                class="btn btn-primary d-none" type="button">
                            Atualizar Foto
                        </label>
                        <input type="submit" id="fotoOK" name="submitFoto" class="btn btn-success d-none"
                            value="Salvar">
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
                            <small class="small text-danger align-middle">O email não pode ser alterado</small>
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
                                <label class="small mb-1" for="inputLocation">Location</label>
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
                                    placeholder="Enter your birthday" value="<?= $perfil['dataNascimento'] ?? '' ?>" />
                            </div>
                            <!-- Form Group (organization name)-->
                            <div class="col mb-3">
                                <label class="small mb-1" for="inputEmailAddress">Organização</label>
                                <input name="organizacao" class="form-control" type="text"
                                    placeholder="Enter your email address"
                                    value="<?= $perfil['organizacao'] ?? 'UniCV' ?>" />
                            </div>
                        </div>
                        <!-- Save changes button-->
                        <input class="btn btn-primary" name="submitPersonal" type="submit" value="Atualizar" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4"></div>
        <div class="col-lg-8">
            <!-- Change password card-->
            <div class="card mb-4">
                <div class="card-header">Mudar Palavra-passe</div>
                <div class="card-body">
                    <form id="formPassword" action="" method="POST">
                        <!-- Form Group (current password)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="currentPassword">Palavra-passe Actual</label>
                            <input class="form-control" id="currentPassword" type="password" name="currentPassword"
                                placeholder="Digite a sua Palavra-passe Actual" required />
                        </div>
                        <!-- Form Group (new password)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="newPassword">Nova Palavra-passe</label>
                            <input class="form-control" id="newPassword" type="password" name="newPassword"
                                placeholder="Digite a sua Nova Palavra-passe" required />
                        </div>
                        <!-- Form Group (confirm password)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="confirmPassword">Confirmar Palavra-passe</label>
                            <input class="form-control" id="confirmPassword" type="password" name="confirmPassword"
                                placeholder="Corfirme a Palavra-passe" required />
                        </div>
                        <button name="submitPassword" id="submitPassword" class="btn btn-primary"
                            type="submit">Guardar</button>
                    </form>
                </div>
            </div>
            <!-- Delete account card: Não é possivel ao codeernador eliminar sua conta. 
            <div class="col-auto">
                
                <div class="card mb-4">
                    <div class="card-header">Eliminar Conta</div>
                    <div class="card-body">
                        <form id="formDeleteAccount" action="" method="POST">
                            <p>Excluir sua conta é uma ação permanente e não pode ser desfeita. Se tiver certeza de que
                                quer
                                excluir sua conta, selecione o botão abaixo.</p>

                            <button id="submitDelete" class="btn btn-danger-soft text-danger" type="submit">
                                Entendi. Excluir minha
                                conta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            -->
        </div>
    </div>



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

    <!-- Validar compos de Mudar Password -->
    <script>

        $(document).ready(function () {

            $("#formPassword").validate({
                rules: {
                    newPassword: {
                        minlength: 4
                    },
                    confirmPassword: {
                        equalTo: '#newPassword'
                    }
                },
                messages: {
                    currentPassword: {
                        required: 'Informe a sua palavra-passe actual'
                    },
                    newPassword: {
                        required: 'Digite a nova palavra-passe'
                    },
                    confirmPassword: {
                        required: 'por favor, confirme a sua nova palavra-passe',
                        equalTo: 'deve ser igual a nova plavra-passe'
                    }
                }
            });


        });
    </script>

    <!-- Validar compos de deleteAccount -->
    <script>
        $('#submitDelete').click(function (e) {
            e.preventDefault();
            swal.fire({
                title: `Eliminar Sua conta`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Sim"
            }).then((result) => {
                if (result.isConfirmed) {
                    // acao assincrona para eliiminar, chamndo 
                    $.ajax({
                        type: 'POST',
                        data: {
                            delete: id
                        },
                        success: function (res) {
                            Swal.fire({
                                title: "Eliminado!",
                                text: res,
                                icon: "success"
                            });
                            window.location.href = <?= BASE_URL ?>;
                        }
                    });
                }
            });
        });

    </script>


</div>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<?php include '_footer.php' ?>