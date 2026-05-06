<?php
include '../backend.php';

// Verifica se o usuário está logado e obtém o perfil do coordenador
if (!isset($_SESSION['usuario']['id'])) {
    die('Acesso negado. Por favor, faça login.');
}

$perfil = (new Model('tbl_coordenador'))->get($_SESSION['usuario']['id']);

// Inicializa a variável $nota com o valor padrão 0
$nota = 0;

// Recebe o ID da ideia
$id = $_GET['id'] ?? 0;

// Obtém os detalhes da ideia
$ideia = (new Model('tbl_identificacao_ideia'))->get($id);

// Verifica se a ideia existe
if (!$ideia) {
    redirect('../404.php', "ID de Ideia que procura não existe");
}

// Obtém detalhes da candidatura e do candidato
$candidatura = (new Model('tbl_candidaturas'))->get($ideia['id_candidatura']);
$candidato_id = $candidatura['id_candidato'];

// Verifica se a ideia já foi avaliada
$avaliacao = (new Model('tbl_avaliacao'))->find('id_ideia', $id);

// Define os critérios de avaliação
$criterios = [
    'viabilidade_retorno' => 'Viabilidade e Retorno',
    'ajuste_estrategico' => 'Ajuste Estratégico',
    'requisitos_recursos' => 'Requisitos de Recursos',
    'desejabilidade' => 'Desejabilidade',
    'viabilidade' => 'Viabilidade',
    'adaptabilidade' => 'Adaptabilidade',
    'inovacao' => 'Inovação',
    'risco' => 'Risco',
    'escalabilidade' => 'Escalabilidade',
    'sustentabilidade' => 'Sustentabilidade'
];

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ideia = $id;
    $descricao = "Coordenador"; // Tipo de avaliador, ajuste conforme necessário
    $id_avaliador = $_SESSION['usuario']['id']; // ID do avaliador
    $status = ''; // Será definido após o cálculo da nota

    // Calcula a nota média com base nos critérios de avaliação
    $nota = array_sum(array_map(fn($c) => $_POST[$c], array_keys($criterios))) / count($criterios);
    $status = ($nota >= 5) ? 'Aprovado' : 'Recusado';

    // Salva a avaliação no banco de dados
    $avaliacao_data = [
        'id_ideia' => $id_ideia,
        'id_avaliador' => $id_avaliador,
        'tipo_avaliador' => $descricao,
        'nota' => $nota,
        'comentario' => $_POST['comentario'],
        'status' => $status
    ];

    $avaliacao_model = new Model('tbl_avaliacao');
    if ($avaliacao) {
        // Atualiza a avaliação existente
        $avaliacao_model->update($avaliacao["id"], $avaliacao_data);
    } else {
        // Insere uma nova avaliação
        $avaliacao_model->insert($avaliacao_data);
    }

    // Atualiza o atributo "avaliacao" na tabela "candidatura"
    $candidatura_data = ['avaliacao' => $status];
    (new Model('tbl_candidaturas'))->update($ideia['id_candidatura'], $candidatura_data);

    // Redireciona para a página da ideia com uma mensagem de sucesso
    flashMessage("Avaliação salva com sucesso!", "success");
    redirect('avaliar.php?id=' . $id); 
}

// Inclui o cabeçalho da página
include '_header.php';
?>

<main class="container px-4">
    <section class="py-5">
        <div class="container px-5 my-5">
            <div class="row gx-5 r">
                <div class="col-12 col-lg-10 mx-auto">
                    <article>
                        <header class="mb-4">
                            <h1 class="fw-bolder mb-1"><?= htmlspecialchars($ideia['titulo_ideia']) ?></h1>
                            <div class="text-muted fst-italic mb-2">
                                <?= (new DateTime($ideia['data_submissao']))->format("l, d F Y") ?>
                            </div>
                            <a
                                class="badge bg-secondary text-decoration-none link-light"><?= htmlspecialchars($ideia['sector']) ?></a>
                            <a
                                class="badge bg-warning text-decoration-none link-light"><?= htmlspecialchars($ideia['estado']) ?></a>
                        </header>
                        <section class="mb-5">
                            <h6 class="lead fw-bold">Descrição</h6>
                            <p class="fs-5 mb-4"><?= htmlspecialchars($ideia['descri_conceito']) ?></p>
                        </section>
                    </article>
                    <section>
                        <?php if ($avaliacao && $avaliacao['status'] == 'Aprovado'): ?>
                        <div class="alert alert-success text-center fw-bold" role="alert">
                            Esta ideia foi aprovada e não pode ser reavaliada.
                        </div>
                        <a href="comprovativoavaliacao.php?id=<?= $id ?>" class="btn btn-secondary mt-3"
                            onclick="return verificarAvaliacao(true);">Gerar Comprovativo</a>
                        <?php elseif ($avaliacao && $avaliacao['status'] == 'Recusado'): ?>
                        <div class="alert alert-warning  text-center fw-bold" role="alert">
                            Esta ideia foi recusada. Você pode reavaliá-la.
                        </div>
                        <form id="reavaliacaoForm" action="avaliar.php?id=<?= $id ?>" method="post">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Critério</th>
                                        <th scope="col">Nota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($criterios as $key => $label): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($label) ?></td>
                                        <td>
                                            <select id="<?= htmlspecialchars($key) ?>"
                                                name="<?= htmlspecialchars($key) ?>" class="form-select criterio"
                                                required>
                                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                                <option value="<?= $i ?>"
                                                    <?= isset($_POST[$key]) && $_POST[$key] == $i ? 'selected' : '' ?>>
                                                    <?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td>Média</td>
                                        <td id="mediaNota"><?= $nota ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mb-3">
                                <label for="comentario" class="form-label">Comentário</label>
                                <textarea id="comentario" name="comentario" class="form-control" rows="4"
                                    required><?= isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : '' ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar Avaliação</button>
                        </form>
                        <a href="comprovativoavaliacao.php?id=<?= $ideia['id'] ?>" class="btn btn-secondary mt-3"
                            onclick="return verificarAvaliacao(true);">Gerar Comprovativo</a>
                        <?php elseif (!$avaliacao): ?>
                        <form id="avaliacaoForm" action="avaliar.php?id=<?= $id ?? 'null' ?>" method="post">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Critério</th>
                                        <th scope="col">Nota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($criterios as $key => $label): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($label) ?></td>
                                        <td>
                                            <select id="<?= htmlspecialchars($key) ?>"
                                                name="<?= htmlspecialchars($key) ?>" class="form-select criterio"
                                                required>
                                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td>Média</td>
                                        <td id="mediaNota"><?= $nota ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mb-3">
                                <label for="comentario" class="form-label">Comentário</label>
                                <textarea id="comentario" name="comentario" class="form-control" rows="4"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar Avaliação</button>
                        </form>
                        <?php endif; ?>
                    </section>
                </div>
            </div>

        </div>
        </div>
        </div>
    </section>
</main>

<?php include '_footer.php' ?>

<script>
// Script para calcular a média das notas dos critérios
document.addEventListener('DOMContentLoaded', function() {
    const criterios = document.querySelectorAll('.criterio');
    const mediaNota = document.getElementById('mediaNota');

    // Adiciona um evento de mudança a cada critério
    criterios.forEach(function(criterio) {
        criterio.addEventListener('change', function() {
            let total = 0;
            criterios.forEach(function(criterio) {
                total += parseInt(criterio.value);
            });

            mediaNota.textContent = (total / criterios.length).toFixed(2);
        });
    });

    // Adiciona evento ao botão de gerar comprovativo
    const gerarComprovativoBtn = document.getElementById('gerarComprovativoBtn');
    if (gerarComprovativoBtn) {
        gerarComprovativoBtn.addEventListener('click', function() {
            if (!verificarAvaliacao(<?= $avaliacao ? 'true' : 'false' ?>)) {
                flashMessage("Avalie primeiro a ideia antes de gerar o documento.", "alert");
                return false;
            }
            window.location.href = "comprovativoavaliacao.php?txtID=<?= $id ?>";
        });
    }
});

function verificarAvaliacao(aval) {
    if (!aval) {
        flashMessage("Avalie primeiro a ideia antes de gerar o documento.", "alert");
        return false;
    }
    return true;
}
</script>