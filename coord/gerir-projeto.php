<?php
include '../backend.php';

$id = (int) ($_GET['id'] ?? 0);

// Verifique se o ID do projeto está especificado
if (!$id) {
    flashMessage('Erro: ID do projeto não especificado.', 'error'); 
    // Redirecione para a página de projeto
    redirect("projeto.php");
}

// Obtenha os dados do projeto com o ID especificado
$projeto = (new Model('tbl_projeto'))->get($id);

// Verifique se o projeto existe
if (!$projeto) {
    flashMessage('Erro: Projeto não identificado.', 'error'); 
    // Redirecione para a página de projeto
    redirect("projeto.php");
}

// Obtém todos os supervisores disponíveis
$supervisores = (new Model('tbl_supervisor'))->getAll();

// Verifica se o formulário foi enviado
if ($_POST) {
    // Obter os dados do formulário
    $data_fim = $_POST['data_fim'] ?? '';
    $id_supervisor = $_POST['id_supervisor'] ?? '';
    $status_projeto = $_POST['status_projeto'] ?? '';
    
    // Atualizar os dados do projeto na base de dados
    $update = Database::prepare("UPDATE tbl_projeto SET data_fim = :data_fim, id_supervisor = :id_supervisor, status_projeto = :status_projeto WHERE id = :id", [
        ':data_fim' => $data_fim,
        ':id_supervisor' => $id_supervisor,
        ':status_projeto' => $status_projeto,
        ':id' => $id
    ]);

    // Verificar se a atualização foi bem-sucedida
    if ($update->rowCount() > 0) {
        flashMessage("Atualização feita com sucesso!", "success");
    } else {
        flashMessage("Não foi possível atualizar o projeto.", "error");
    }

    // Redirecionar de volta para a página do projeto
    redirect("projetos.php");
}

// Incluir o cabeçalho da página
include '_header.php';
?>

<main class="container px-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card mt-4">
                <div class="card-header">
                    <h2 class="card-title">Gerenciar Projeto - <?= $projeto['nome_projeto'] ?></h2>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="data_fim" class="form-label">Data de Término</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= $projeto['data_fim'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_supervisor" class="form-label">Supervisor</label>
                            <select class="form-select" id="id_supervisor" name="id_supervisor" required>
                                <?php foreach ($supervisores as $supervisor): ?>
                                    <option value="<?= $supervisor['id'] ?>" <?= $supervisor['id'] == $projeto['id_supervisor'] ? 'selected' : '' ?>>
                                        <?= $supervisor['nome'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_projeto" class="form-label">Status do Projeto</label>
                            <select class="form-select" id="status_projeto" name="status_projeto" required>
                                <option value="Em andamento" <?= $projeto['status_projeto'] === 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                                <option value="Concluído" <?= $projeto['status_projeto'] === 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                                <option value="Cancelado" <?= $projeto['status_projeto'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                        <input type="hidden" name="id_projeto" value="<?= $projeto['id'] ?>">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Atualizar Projeto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '_footer.php' ?>
