<?php include '../backend.php';

// se não for espacificado o id, retornar
if (!isset($_GET['id'])) {
    redirect('../404.php');
}

$user = (new Model('tbl_candidato'))->get($_GET['id']);

if (!$user) {
    flashMessage('ID de andidato Inválido', "alert");
    redirect('candidatos.php');
}


include '_header.php'; ?>

<div class="container-md px-0 p-md-4 my-4">
    <h1 class="text-center mb-4">Ver Candidato</h1>
    <p class="small text-center text-muted">Os dados dos candidatos não podem ser alterados devido a politica de privacidade</p>
    <div class="card w-75 mx-auto">
        <div class="card-header clearfix">
            <span>Ver dados do Candidato <b><?= $user['nome'] ?></b></span>
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
        <div class="card-footer"><a name="" id="" class="btn btn-secondary" href="#" onclick="history.back()" role="button">Voltar</a>
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