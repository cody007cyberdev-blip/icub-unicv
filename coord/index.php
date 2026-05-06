<?php
include '../backend.php';

$count_cands = (int) Database::prepare('SELECT COUNT(*) FROM `tbl_candidato`')->fetchColumn();
$count_admin = (int) Database::prepare('SELECT COUNT(*) FROM `tbl_coordenador`')->fetchColumn();
$count_admin += (int) Database::prepare('SELECT COUNT(*) FROM `tbl_supervisor`')->fetchColumn();
#$count_ativos = (int) Database::prepare('SELECT COUNT(*) FROM `gestao_ativos`')->fetchColumn();

$lista_superv = Database::execute("SELECT * FROM tbl_coordenador")->fetchAll();
$lista_coord = Database::execute("SELECT * FROM tbl_supervisor")->fetchAll();
#$lista_ativos = Database::execute("SELECT * FROM gestao_ativos")->fetchAll();
$lista_coord_superv = array_merge($lista_superv, $lista_coord);

include '_header.php' ?>

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
    <div class="focus-bg"></div>
    <div class="content-overlay">
        <h1 class="display-5 fw-bold text-light shadow-lg mb-5"
            style="font-family: 'Josefin Sans','Ubuntu', 'Source Code Pro'">
            Seja Bem-vindo ao Sistema de Gestão do Website <span
                style="color: #a90e2a; font-variant: small-caps">iCUB</span>
        </h1>
        <div class="button-container d-flex flex-column d-md-block d-lg-flex flex-lg-row">
            <a href="candidatos.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir Candidatos</a>
            <a href="candidaturas.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir
                Submissão de Ideias</a>
            <a href="coordenadores.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir
                Coordenador</a>
            <a href="supervisores.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir
                Supervisores</a>
            <a href="projetos.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir Projetos</a>
            <a href="gestaoativos.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir Ativos</a>
            <a href="equipa.php" class="m-0 m-md-3 btn btn-primary btn-lg p-md-4" role="button">Gerir Equipas</a>

        </div>
        <div class="col mt-5">
            <a href="#dashboard" id="gotoDash" class="btn btn-outline-light  rounded-circle">
                <i class="bi bi-arrow-down"></i>
            </a>
        </div>
    </div>
</div>

<div id="dashboard" style='padding-top: 2em' class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <!-- Example Colored Cards for Dashboard Demo-->
        <?php
        // Numero de candidaturas
        $candidaturas = (int) Database::prepare('SELECT COUNT(*) FROM `tbl_candidaturas`')->fetchColumn() ?? 0;
        // $projetos = (int) Database::prepare('SELECT COUNT(*) FROM `tbl_projetos` WHERE status_projeto = ?',['Em andamento'])->fetchColumn() ?? 0;
        $ativos = (int) Database::prepare('SELECT COUNT(*) FROM gestao_ativos')->fetchColumn() ?? 0;
        ?>
        <div class="row">

            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Membros Internos</div>
                                <div class="text-lg fw-bold"><?= $count_admin ?></div>
                            </div>
                            <i class="fas fa-users fs-2 text-white-75"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#tableStaff">Detalhes</a>
                        <div class="text-white"><i class="fas fa-angle-right "></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Submissão de ideias</div>
                                <div class="text-lg fw-bold"><?= $candidaturas ?></div>
                            </div>
                            <i class="fas fa-calendar  fs-2 text-white-75"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="candidaturas.php">Consultar Tabela</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Projetos em curso</div>
                                <div class="text-lg fw-bold"><?= $projectos ?? 0 ?></div>
                            </div>
                            <i class="fas fa-cubes   fs-1 text-white-75"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="<?= COORD ?>/projetos.php">Consultar Tabela</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Ativos do Sistema</div>
                                <div class="text-lg fw-bold"><?= $ativos ?? 0 ?></div>
                            </div>
                            <i class="fas fa-pager  fs-2 text-white-75"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="<?= COORD ?>/gestaoativos.php">Detalhes</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Grafico de Submissão de Ideia nos ultimos dias
                </div>
                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Gráfico de Registro de Candidatos por nos ultimos 6 meses
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
    </div>
    <div id="tableStaff" class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabela dos membros internos
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sexo</th>
                        <th>Email</th>
                        <th>Função</th>
                        <th>Data Entrada</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Sexo</th>
                        <th>Email</th>
                        <th>Função</th>
                        <th>Data Entrada</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($lista_coord_superv as $staff) {
                        $id = $staff['id'];
                        $role = isset($staff['area_atuacao']) ? 'supervisor' : 'coordenador';
                        $dob = new DateTime($staff['data_nascimento'] ?? '');
                        $idade = (new DateTime())->diff($dob)->y;
                        ?>
                        <tr>
                            <td>
                                <?php if ($role == 'coordenador'): ?>
                                    <a href="<?= "ver-coordenador.php?id=$id" ?>">
                                    <?php else: ?>
                                        <a href="<?= "ver-supervisor.php?id=$id" ?>">
                                        <?php endif; ?>
                                        <?= $staff['nome'] ?>
                                    </a>
                            </td>
                            <td><?= $staff['sexo'] ?></td>
                            <td><?= $staff['email'] ?></td>
                            <td><?= $role ?></td>
                            <td><?= (new DateTime($staff['data_entrada']))->format('Y-m-d') ?? 'hoje' ?></td>
                        </tr>
                    <?php } ?>
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