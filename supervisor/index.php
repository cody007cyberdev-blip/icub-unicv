<?php
include '../backend.php';

// Contagem de supervisores e coordenadores
$count_supervisores = (int) Database::prepare('SELECT COUNT(*) FROM `tbl_supervisor`')->fetchColumn();
$count_coordenadores = (int) Database::prepare('SELECT COUNT(*) FROM `tbl_coordenador`')->fetchColumn();
$count_admin = $count_supervisores;

// Contagem de projetos concluídos e em andamento
$registros = (new Model('tbl_projeto'))->findAll('id_supervisor', $_SESSION['usuario']['id']);
$concluidos = array_filter($registros, function($registro) {
    return $registro['status_projeto'] === "Concluído";
});
$andamento = array_filter($registros, function($registro) {
    return $registro['status_projeto'] === "Em andamento";
});
$count_concluidos = count($concluidos);
$count_andamento = count($andamento);

$lista_supervisores = Database::prepare('SELECT * FROM `tbl_supervisor`')->fetchAll();
include '_header.php';
?>

<style>
    .background-image {
        background-image: url("<?= assets ?>/img/gsss.jpg");
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        color: white;
    }

    .content-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        font-family: 'Montserrat';
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 1.2em;
    }

    .button-container .btn {
        margin: 10px;
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 2em 1em;
    }

    .focus-bg {
        position: absolute;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
    }

    #gotoDash:after {
        pointer-events: none;
        content: 'dashboard';
        position: absolute;
        text-align: center;
        left: 0;
        top: 150%;
        margin-left: -0.5em;
        width: 100%;
        transform: translateX(-50%);
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0);
        transition: color ease-out 1s;
    }

    #gotoDash:hover:after {
        color: rgba(255, 255, 255, 0.5);
    }

    #gotoDash {
        position: relative;
        animation: breath 2s ease-in-out alternate-reverse infinite;
    }

    @keyframes breath {
        from {
            transform: translateY(-20%);
        }

        to {
            transform: translateY(0);
        }
    }
</style>

<div class="background-image">
    <div class="content-overlay">
        <h1 class="display-5 fw-bold text-light mb-4">Seja Bem-vindo ao Sistema de Gestão do Website <span style="color: #a90e2a; font-variant: small-caps">iCUB</span></h1>
        <a href="#tableSupervisores" class="btn-circle btn-outline-light" id="gotoDash"><i class="bi bi-arrow-down"></i></a>
    </div>
</div>

<div class="container-fluid px-4 mt-5">
<h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row dashboard-cards">
        <!-- Cards de estatísticas -->
        <div class="col-lg-6 col-xl-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Membros Internos (Supervisores)</div>
                            <div class="text-lg fw-bold"><?= $count_admin ?></div>
                        </div>
                        <i class="fas fa-users fs-4"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#tableSupervisores" class="text-white stretched-link">Detalhes <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xl-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Equipas</div>
                            <div class="text-lg fw-bold"><a href="equipa.php" class="text-white">Ver Equipas</a></div>
                        </div>
                        <i class="fas fa-users fs-4"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="equipa.php" class="text-white stretched-link">Detalhes <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xl-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Projetos Concluídos</div>
                            <div class="text-lg fw-bold"><a href="projetos.php" class="text-white"><?= $count_concluidos ?></a></div>
                        </div>
                        <i class="fas fa-calendar-check fs-4"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="projetos.php" class="text-white stretched-link">Detalhes <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xl-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 small">Projetos em Andamento</div>
                            <div class="text-lg fw-bold"><a href="projetos.php" class="text-white"><?= $count_andamento ?></a></div>
                        </div>
                        <i class="fas fa-tasks fs-4"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#!" class="text-white stretched-link">Detalhes <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de supervisores -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabela de Supervisores
        </div>
        <div class="card-body">
            <table id="tableSupervisores" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Data de Entrada</th>
                        <!-- Adicione mais colunas conforme necessário -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_supervisores as $supervisor) : ?>
                        <tr>
                            <td><?= htmlspecialchars($supervisor['nome']) ?></td>
                            <td><?= htmlspecialchars($supervisor['email']) ?></td>
                            <td><?= htmlspecialchars($supervisor['contacto']) ?></td>
                            <td><?= htmlspecialchars($supervisor['data_entrada']) ?></td>
                            <!-- Adicione mais colunas conforme necessário -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS FOR CHARTS-->
<?php
$months = 6;
$max = 0;
$days = 13;
?>
<script>
    $(document).ready(function () {
        // editar o graifoc de bara
        var bardata = myBarChart.data.datasets[0].data;
        var labels = myBarChart.data.labels;
        <?php for ($i = 0; $i < $months; $i++):
            $dt = (int) Database::prepare(
                'SELECT COUNT(*) FROM tbl_candidato WHERE MONTH(data_entrada) = ?',
                [(new DateTime($months - $i - 1 . ' months ago'))->format('m')]
            )->fetchColumn();
            $max = $dt < $max ?: $dt;   // max is greater than dt ? if not, so max is dt value
            ?>
            //for (let i = 0; i < data.length; i++) {
            bardata[<?= $i ?>] = <?= $dt ?>;
            labels[<?= $i ?>] = '<?= (new DateTime($months - $i - 1 . ' months ago'))->format('M') ?>';
            //}
        <?php endfor; ?>
        myBarChart.options.scales.yAxes[0].ticks.max = <?= (ceil($max / 10) * 1.3) * 10 ?? 1000 ?>;    // escala maxima... maximo aproximado
        myBarChart.update();


        // editar o graifoc de bara
        var linedata = myLineChart.data.datasets[0].data;
        var linelabels = myLineChart.data.labels;
        <?php
        $max = 0;
        for ($i = 0; $i < $days; $i++):
            $dt = (int) Database::prepare(
                'SELECT COUNT(*) FROM `tbl_identificacao_ideia` WHERE DAY(data_submissao) = ?',
                [(new DateTime($days - $i . ' days ago'))->format('d')]
            )->fetchColumn();
            $max = $dt < $max ?: $dt;   // max is greater than dt ? if not, so max is dt value
            ?>

            linelabels[<?= $i ?>] = "<?= (new DateTime($days - $i - 1 . ' days ago'))->format('M d') ?>";
            linedata[<?= $i ?>] = <?= $dt ?>;

            <?php
        endfor;
        ?>



        myLineChart.options.scales.yAxes[0].ticks.max = <?= (ceil($max / 15) * 1.2) * 10 ?? 500 ?>;    // escala maxima... maximo aproximado
        myLineChart.update();

    });
</script>

<?php include '_footer.php' ?>
