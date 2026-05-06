<?php

require '../backend.php';

// Verifica se o ID do candidato está definido na sessão
if (!isset($_SESSION['usuario']['id'])) {
    // Redireciona ou trata o caso em que o ID do candidato não está definido
    exit("ID do candidato não encontrado na sessão.");
}

$candidatoId = $_SESSION['usuario']['id'];

// Obtenha os IDs da candidatura e da ideia
$id_candidatura = (int) ($_GET['id_candidatura'] ?? 0);
$id_ideia = (int) ($_GET['id_ideia'] ?? 0);

// Verifique se os IDs são válidos
if ($id_ideia <= 0 || $id_candidatura <= 0) {
    redirect('ideias.php');
    exit;
}

// Obtenha os dados do projeto
$projeto = (new Model('tbl_projeto'))->find('id_ideia', $id_ideia);

// Verifique se a ideia e o projeto existem
if (!$projeto) {
    redirect('../404.php', "Parece que projeto ainda não foi criado");
    exit;
}

// Obtenha os dados da ideia
$ideia = (new Model('tbl_identificacao_ideia'))->get($id_ideia);

// Obtenha os dados da candidatura
$candidatura = (new Model('tbl_candidaturas'))->get($id_candidatura);

// Verifique se a ideia e a candidatura existem
if (!$ideia || !$candidatura) {
    flashMessage("Aguarde para criação do seu projeto", "info");
    redirect('ideias.php');
    exit;
}

// Defina as variáveis da ideia
$nome_projeto = $ideia['titulo_ideia'] ?? 'N/A';
$descricao_projeto = $ideia['descri_conceito'] ?? 'N/A';
$data_inicio_projeto = $projeto['data_inicio'] ?? 'N/A';
$data_fim_projeto = $projeto['data_fim'] ?? 'N/A';
$status_projeto = $projeto['status_projeto'] ?? 'N/A';

// Defina as variáveis do candidato
$candidato = (new Model('tbl_candidato'))->get($candidatura['id_candidato']);
$nome_candidato = $candidato['nome'] ?? 'N/A';
$data_nascimento_candidato = $candidato['data_nascimento'] ?? 'N/A';
$genero_candidato = $candidato['sexo'] ?? 'N/A';

// Obtenha os dados da equipe
$equipa = (new Model('tbl_equipa'))->find('id_projetos', $projeto['id']);
if ($equipa) {
    $teammates = (new Model('tbl_candidato'))->findAll('id_equipa', $equipa['id']) ?? [];
}

include '_header.php';


?>

<main class="container px-4">
    <div class="row">
        <?php if ($status_projeto !== 'Cancelado'): ?>
        <!-- Se o projeto não estiver cancelado, exiba as informações -->
        <div class="col-lg-8">
            <!-- Seção de informações do projeto -->
            <div class="card mb-4">
                <div class="card-header">Informações do Projeto</div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($nome_projeto) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($descricao_projeto) ?></p>
                    <p class="card-text">Data de Início: <?= htmlspecialchars($data_inicio_projeto) ?></p>
                    <p class="card-text">Data de Término: <?= htmlspecialchars($data_fim_projeto) ?></p>
                    <!-- Mais informações do projeto podem ser adicionadas aqui... -->
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Seção de informações da candidatura -->
            <div class="card mb-4">
                <div class="card-header">Informações da Candidatura</div>
                <div class="card-body">
                    <p class="card-text"><b>Nome Candidato:</b> <?= htmlspecialchars($nome_candidato) ?></p>
                    <p class="card-text"><b>Gênero:</b> <?= $genero_candidato == 'M' ? 'Masculino' : 'Feminino'; ?></p>
                    <p class="card-text"><b>Nome Equipa:</b> <?= htmlspecialchars($equipa['nomeequipa'] ?? 'N/A') ?></p>
                    <p class="card-text"><strong>Supervisor:</strong> <?= (new Model('tbl_supervisor'))->get($projeto['id_supervisor'])['nome'] ?></p>
                    <p class="card-text"><strong>Membros de Equipe:</strong></p>
                    <ul class="">
                        <?php foreach ($teammates as $teammate) : ?>
                        <li class="mb-1">
                            <a href="#" class="small link-dark">
                                <i class="fas fa-user me-2"></i><?= htmlspecialchars($teammate['email']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Se o projeto estiver cancelado, exiba uma mensagem -->
        <div class="col-lg-12">
            <div class="alert alert-danger" role="alert">
                Este projeto foi cancelado.
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include '_footer.php'; ?>
