<?php
include("../backend.php");

// Verifica se o usuário está logado e obtém o perfil do coordenador
verificarAutenticacao();

$perfil = Database::prepare('SELECT * FROM tbl_coordenador WHERE id = ?', [
    $_SESSION['usuario']['id']
])->fetch();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
    $categoria = isset($_POST["categoria"]) ? $_POST["categoria"] : "";
    $descricao = isset($_POST["descricao"]) ? $_POST["descricao"] : "";
    $foto = isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "";
    $id_coordenador = $_SESSION['usuario']['id']; // Usa o ID do coordenador logado
    $datahorario = date('Y-m-d H:i:s'); // a base de dados ja devia/deve ter um default current_timestap no campo data
    $link = isset($_POST["link"]) ? $_POST["link"] : "";

    // Preparar a inserção de dados
    $sentence = $conexion->prepare("INSERT INTO `gestao_ativos` (`titulo`, `categoria`, `descricao`, `foto`, `id_coordenador`, `datahorario`, `link`) VALUES (:titulo, :categoria, :descricao, :foto, :id_coordenador, :datahorario, :link)");
    $sentence->bindParam(":titulo", $titulo);
    $sentence->bindParam(":categoria", $categoria);
    $sentence->bindParam(":descricao", $descricao);
    $sentence->bindParam(":id_coordenador", $id_coordenador, PDO::PARAM_INT);
    $sentence->bindParam(":datahorario", $datahorario);
    $sentence->bindParam(":link", $link);

    $data_ = new DateTime();
    $nomeficheiro_foto = ($foto != '') ? $data_->getTimestamp() . '_' . $foto : "";
    $tmp_foto = $_FILES["foto"]['tmp_name'];
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, PATH_UPLOADS . "/fotos_ativ/$nomeficheiro_foto");
    }
    $sentence->bindParam(":foto", $nomeficheiro_foto);
    $sentence->execute();
    if($sentence->rowCount() > 0){
        flashMessage("Ativo Adicionado Com Sucesso!", 'success');
    } else {
        flashMessage("Erro, Ativo Não foi registrado!", 'error');
    }
    redirect('gestaoativos.php');
}

$coordenadores = (new Model('tbl_coordenador'))->getAll();
?>
<?php include('_header.php') ?>

<div class="container p-4 col-8">
    <div class="card">
        <div class="card-header">
            Dados do Ativo
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId"
                        placeholder="Título" required>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group">
                        <label class="input-group-text form-label mb-0">Categoria</label>
                        <div class="form-control justify-content-around">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="categorian">Notícia</label>
                                <input class="form-check-input" type="radio" name="categoria" id="categorian"
                                    value="Noticia" required />
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="categoriae">Eventos</label>
                                <input class="form-check-input" type="radio" name="categoria" id="categoriae"
                                    value="Eventos" required />
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="categoriac">Candidaturas</label>
                                <input class="form-check-input" type="radio" name="categoria" id="categoriac"
                                    value="Candidaturas" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="coordenador_nome" class="form-label">Coordenador</label>
                    <textarea class="form-control" name="coordenador_nome" id="<?= $perfil['id'] ?>" rows="1"
                        placeholder="Coordenador" readonly><?= htmlspecialchars($perfil['nome'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" rows="3"
                        placeholder="Descrição" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto:</label>
                    <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId"
                        placeholder="Foto">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control" name="link" id="link" aria-describedby="helpId"
                        placeholder="Link">
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-success">Adicionar Ativo</button>
                <a name="" id="" input type="button" class="btn btn-secondary" href="gestaoativos.php"
                    role="button">Cancelar</a>
                <input type="reset" class="btn btn-secondary" role="button" value="Limpar" />
            </div>
        </form>
    </div>
</div>

<script>
// o flash message que estava aqui, foi removido, pois ja existe uma implementacao da funcionalidade
// pronto a ser usado desde que seja incluido o backend e o footer
// e uma fez que for submetido, o flash message nao apareceria aqui, 
// pois a pagina fora redirecionada para gestaoativos.php
</script>

<?php include('_footer.php') ?>
