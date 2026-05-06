<?php
include '../backend.php';

// Certifique-se de que a variável $perfil está sendo corretamente inicializada
$id = $_GET['id'] ?? null;
if ($id) {
    $ativo = (new Model('gestao_ativos'))->get($id);
    $id_coordenador = $ativo['id_coordenador'] ?? null;
    if ($id_coordenador) {
        $perfil = (new Model('tbl_coordenador'))->get($id_coordenador);
    }
}

$perfil = Database::prepare('SELECT * FROM tbl_coordenador WHERE id = ?', [
    $_SESSION['usuario']['id']
])->fetch();

if ($_POST) {
    $id = $_POST['id'] ?? null;
    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
    $categoria = isset($_POST["categoria"]) ? $_POST["categoria"] : "";
    $descricao = isset($_POST["descricao"]) ? $_POST["descricao"] : "";
    $id_coordenador = isset($_POST["id_coordenador"]) ? $_POST["id_coordenador"] : "";
    $datahorario = isset($_POST["datahorario"]) ? $_POST["datahorario"] : "";
    $link = isset($_POST["link"]) ? $_POST["link"] : "";
    $foto = isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "";

    // Verifica se uma nova foto foi enviada
    $nomeficheiro_foto = $ativo['foto']; // Nome da foto atual
    if ($foto != '') {
        $data_ = new DateTime();
        $nomeficheiro_foto = $data_->getTimestamp() . "_" . $foto;
        $tmp_foto = $_FILES["foto"]['tmp_name'];
        if ($tmp_foto != '') {
            move_uploaded_file($tmp_foto, PATH_UPLOADS . "/fotos_ativ/$nomeficheiro_foto");
        }
    }

    $update = Database::prepare("UPDATE `gestao_ativos` SET titulo=:titulo, categoria=:categoria, descricao=:descricao, foto=:foto, id_coordenador=:id_coordenador, datahorario=:datahorario, link=:link WHERE id=:id", [
        ':titulo' => $titulo,
        ':categoria' => $categoria,
        ':descricao' => $descricao,
        ':foto' => $nomeficheiro_foto,
        ':id_coordenador' => $id_coordenador,
        ':datahorario' => $datahorario,
        ':link' => $link,
        ':id' => $id
    ]);

    if ($update->rowCount() > 0) {
        // sucesso
        flashMessage("Atualização feita com Sucesso!", "success");
    } else {
        // erro
        flashMessage("Não foi possível editar o Ativo!", "alert");
    }

    redirect('gestaoativos.php');
}

// se não for especificado o id, retornar
if (!isset($_GET['id'])) {
    redirect('/../404.php');
}

$ativo = (new Model('gestao_ativos'))->get($_GET['id']);
if (!$ativo) {
    flashMessage('ID de Ativo Inválido', "alert");
    redirect('gestaoativos.php');
}

$coordenadores = (new Model('tbl_coordenador'))->getAll();
include '_header.php';
?>

<div class="container-md px-0 p-md-4 my-4">
    <h1 class="text-center mb-4">Editar Ativo</h1>
    <div class="card w-75 mx-auto">
        <div class="card-header clearfix">
            <span>Edite os dados do Ativo</span>
            <span class="disabled text-muted float-end">ID: <?= $ativo['id'] ?></span>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $ativo['id'] ?>">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId"
                        placeholder="<?= $ativo['titulo'] ?>" value="<?= $ativo['titulo'] ?>" required>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group">
                        <label class="input-group-text form-label mb-0">Categoria</label>
                        <div class="form-control">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="categorian">Noticia</label>
                                <input class="form-check-input" type="radio" name="categoria" id="categorian"
                                    value="Noticia" <?= $ativo['categoria'] == 'Noticia' ? 'checked' : '' ?>  />
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="categoriae">Eventos</label>
                                <input class="form-check-input" type="radio" name="categoria" id="categoriae"
                                    value="Eventos" <?= $ativo['categoria'] == 'Eventos' ? 'checked' : '' ?>  />
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="categoriac">Candidaturas</label>
                                <input class="form-check-input" type="radio" name="categoria" id="categoriac"
                                    value="Candidaturas" <?= $ativo['categoria'] == 'Candidaturas' ? 'checked' : '' ?>
                                    required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" rows="3"
                        required><?= $ativo['descricao'] ?></textarea>
                </div>
                <div class="col-4 col-md-4 col-lg-6 mx-auto">
                    <label for="foto"
                        class="rounded-4 text-center overflow-hidden object-fit-cover shadow-sm d-block w-100 mx-auto position-relative"
                        style="height: 12rem; cursor: pointer;">
                        <img class="img-thumbnails  rounded-4" style="min-width: 100%; height: 100%;" id="showFoto"
                            src="<?= foto_ativo($ativo['foto']) ?>" alt="">
                        <span id="editadoText" class="position-absolute translate-middle badge rounded-pill bg-warning"
                            style="display: none; top: 10%; left: 10%">Editado</span>
                    </label>
                    <input type="file" class="form-control d-none" name="foto" id="foto" aria-describedby="helpId"
                        placeholder="Foto" onchange="loadFile(event)" accept="Image/*" />
                </div>
                <div class="mb-3">
                    <label for="id_coordenador" class="form-label form-label-sm small mb-0">Coordenador</label>
                    <select class="form-control" name="id_coordenador" id="id_coordenador" required>
                        <?php foreach ($coordenadores as $coordenador): ?>
                            <option value="<?= $coordenador['id'] ?>" <?= $ativo['id_coordenador'] == $coordenador['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($coordenador['nome'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="datahorario" class="form-label">Data e Hora</label>
                    <input type="datetime" class="form-control" name="datahorario" id="datahorario"
                        placeholder="<?= $ativo['datahorario'] ?>" value="<?= $ativo['datahorario'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control" name="link" id="link" aria-describedby="helpId"
                        placeholder="<?= $ativo['link'] ?>" value="<?= $ativo['link'] ?>" >
                </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success me-3">Atualizar</button>
            <a name="" id="" input type="button" class="btn btn-secondary" href="gestaoativos.php"
                role="button">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
var loadFile = function(event) {
    var output = document.getElementById('showFoto');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src); // free memory
    }
    document.getElementById('editadoText').style.display = 'block';
};
</script>

<?php include '_footer.php'?>
