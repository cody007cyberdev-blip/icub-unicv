<?php
include("../backend.php");

// Verifica se o usuário está logado e obtém o perfil do coordenador
verificarAutenticacao();

// Verifica se o ID da ideia foi recebido via GET
if (isset($_GET['id'])) {
    $id_ideia = (int)$_GET['id'];

    // Busca os dados da ideia na tabela tbl_identificacao_ideia
    $ideia = (new Model('tbl_identificacao_ideia'))->get($id_ideia);

    // Verifica se a ideia foi encontrada
    if ($ideia) {
        $titulo = $ideia['titulo_ideia'];
        $descricao = $ideia['descri_conceito'];

        // Obter todos os supervisores
        $supervisores = (new Model('tbl_supervisor'))->getAll();
    } else {
        flashMessage("Ideia não encontrada!", 'error');
        redirect('candidaturas.php');
    }
} else {
    flashMessage("ID da ideia não fornecido!", 'error');
    redirect('candidaturas.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
    $descricao = isset($_POST["descricao"]) ? $_POST["descricao"] : "";
    $data_inicio = isset($_POST["data_inicio"]) ? $_POST["data_inicio"] : "";
    $data_fim = isset($_POST["data_fim"]) ? $_POST["data_fim"] : "";
    $id_supervisor = (int)$_POST["supervisor"];
    $status_projeto = 'Em andamento'; // Valor padrão definido

    // Verifica se os campos obrigatórios não estão vazios
    if (empty($titulo) || empty($descricao) || empty($data_inicio) || empty($data_fim) || empty($id_supervisor)) {
        flashMessage("Todos os campos são obrigatórios!", 'error');
    } else {
        // Preparar a inserção de dados
        $query = "INSERT INTO `tbl_projeto` (`nome_projeto`, `descricao`, `data_inicio`, `data_fim`, `id_supervisor`, `id_ideia`, `status_projeto`) VALUES (:titulo, :descricao, :data_inicio, :data_fim, :id_supervisor, :id_ideia, :status_projeto)";
        
        try {
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":titulo", $titulo);
            $stmt->bindParam(":descricao", $descricao);
            $stmt->bindParam(":data_inicio", $data_inicio);
            $stmt->bindParam(":data_fim", $data_fim);
            $stmt->bindParam(":id_supervisor", $id_supervisor, PDO::PARAM_INT);
            $stmt->bindParam(":id_ideia", $id_ideia, PDO::PARAM_INT);
            $stmt->bindParam(":status_projeto", $status_projeto);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                flashMessage("Projeto Adicionado Com Sucesso!", 'success');
            } else {
                flashMessage("Erro, Projeto Não foi registrado!", 'error');
            }
        } catch (Exception $e) {
            flashMessage("Erro ao inserir projeto: " . $e->getMessage(), 'error');
        }
        redirect('candidaturas.php');
    }
}

$coordenadores = (new Model('tbl_coordenador'))->getAll();
?>
<?php include('_header.php') ?>

<div class="container p-4 col-8">
    <div class="card">
        <div class="card-header">
            Dados do Projeto
        </div>
        <form action="" method="post">
            <div class="card-body">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId"
                        placeholder="Título" value="<?= htmlspecialchars($titulo) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" rows="3" placeholder="Descrição"
                        required><?= htmlspecialchars($descricao) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="data_inicio" class="form-label">Data de Início</label>
                    <input type="date" class="form-control" name="data_inicio" id="data_inicio"
                        aria-describedby="helpId" required>
                </div>
                <div class="mb-3">
                    <label for="data_fim" class="form-label">Data de Término</label>
                    <input type="date" class="form-control" name="data_fim" id="data_fim" aria-describedby="helpId"
                        required>
                </div>
                <div class="mb-3">
                    <label for="supervisor" class="form-label">Supervisor</label>
                    <select class="form-select" name="supervisor" id="supervisor" aria-describedby="helpId" required>
                        <?php foreach ($supervisores as $supervisor): ?>
                        <option value="<?= htmlspecialchars($supervisor['id']) ?>">
                            <?= htmlspecialchars($supervisor['nome']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-success">Adicionar Projeto</button>
                <a name="" id="" input type="button" class="btn btn-secondary" href="projetos.php"
                    role="button">Cancelar</a>
                <input type="reset" class="btn btn-secondary" role="button" value="Limpar" />
            </div>
        </form>
    </div>
</div>

<?php include('_footer.php') ?>
