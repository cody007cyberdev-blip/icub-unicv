<?php require '../backend.php';
include '_script.php';
include '_header.php' ?>

<div class="row">
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
    </div>
    <div class="col-lg-4">
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

    </div>
</div>
</div>
</main>



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

<?php include '_footer.php' ?>