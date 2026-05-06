<?php include '../backend.php';

$id = (int) ($_GET['id'] ?? 0);

$ideia = (new Model('tbl_identificacao_ideia'))->get($id);

if (!$ideia) {
    redirect('../404.php', "ID de Ideia que procura não existe");
}
// else if($projeto['id_supervisor'] != $_SESSION['usuario']['id']){
//     // caso for supervisor ou condadto, deve-se verificar autorizacao para ver
//     // redirect('../401.php', "nologin");
// }

// dados a utilizar:
$candidatura = (new Model('tbl_candidaturas'))->get($ideia['id_candidatura']);
$candidato = (new Model('tbl_candidato'))->get($candidatura['id_candidato']);
$avaliacao = (new Model('tbl_avaliacao'))->find('id_ideia', $id);



include '_header.php';
?>

<!-- PAGINA -->
<main class="container position-relative">
    <div class="position-absolute pointer-events-none opacity-75" style="top: 5em; right: 3%">
        <?php if ($avaliacao && $avaliacao['status'] == 'Aprovado'): ?>
            <div class=" bg-gradient btn btn-success shadow rounded-circle text-light" title="Aprovado"><i class="fas fa-thumbs-up fs-4"></i></div>
        <?php elseif ($avaliacao && $avaliacao['status'] == 'Recusado'): ?>
            <div class=" bg-gradient btn btn-danger shadow rounded-circle text-light" title="Recusado"><i class="fas fa-thumbs-down fs-4"></i></div>
        <?php endif; ?>
    </div>

    <!-- Page Content-->
    <section class="py-md-5">
        <div class="container-md px-4 px-md-5 mt-0 mb-5">
            <div class="row gx-5">
                <div class="col-lg-3">
                    <div
                        class="d-flex flex-column align-items-center mt-lg-5 mb-4 btn btn-light btn-lg p-2 justify-content-start">
                        <div class="object-fit-scale bg-blacks mb-3" style="width:48px; height: 48px">
                            <img class="rounded-circle h-100 shadow-sm"
                                src=" <?= foto_candidato($candidato['foto']) ?> " alt="..." style="width:100%;"
                                onclick="imagem(this)" />
                        </div>
                        <div class="ms-3s">
                            <div class="fw-bold mb-2"><a href="ver-candidato.php?id=<?= $candidato['id'] ?>"
                                    class="text-dark"><?= $candidato['nome'] ?></a></div>
                            <div class="fw-bold mb-2 text-muted small"><?= $candidato['email'] ?></div>
                            <div class="text-muted small"><a
                                    href="<?= doc_identificacao . $candidatura['doc_identifi'] ?>">Doc.
                                    Identificação</a></div>
                        </div>
                    </div>
                    <!-- Academico -->
                    <div
                        class="d-flex flex-column align-items-center mt-lg-5 mb-4 btn btn-light btn-lg p-2 justify-content-center">
                        <div class="mb-2">
                            <i class="fas fa-graduation-cap display-5 text-dark opacity-75"></i>
                        </div>
                        <div class="ms-3s text-center">
                            <div class="fw-bold"><?= $candidatura['ano_curso'] ?></div>
                            <div class="text-muted small my-2"><?= $candidatura['area_formacao'] ?></div>
                            <div class="text-muted small "><a
                                    href="<?= comprovativo_estudante . $candidatura['doc_comprovativo'] ?>">Comprovativo
                                    Academico</a>
                            </div>
                        </div>
                    </div>
                    <!-- Contactar -->
                    <div
                        class="d-flex flex-column align-items-center mt-lg-5 mb-4 btn btn-light btn-lg p-2 justify-content-center">
                        <div class="mb-2">
                            <i class="fas fa-address-card display-5 text-dark opacity-75"></i>
                        </div>
                        <div class="ms-3s text-center">
                            <div class="fw-bold"><?= $candidato['nacionalidade'] ?></div>
                            <div class="fw-bold text-muted mt-2"><?= $candidatura['endereco'] ?></div>
                            <div class="text-muted small my-2"><i
                                    class="fas fa-phone me-2"></i><?= $candidatura['telefone'] ?></div>
                            <div class="text-muted small my-2"><i
                                    class="fas fa-phone me-2"></i><?= $candidatura['telemovel'] ?></div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Post content-->
                    <article>
                        <!-- Post header-->
                        <header class="mb-4">
                            <!-- Post title-->
                            <h1 class="fw-bolder mb-1"><?= $ideia['titulo_ideia'] ?></h1>
                            <!-- Post meta content-->
                            <div class="text-muted fst-italic mb-2">
                                <?= (new DateTime($ideia['data_submissao']))->format("l, d F Y") ?>
                            </div>
                            <!-- Post categories-->
                            <a class="badge bg-secondary text-decoration-none link-light"><?= $ideia['sector'] ?></a>
                            <a class="badge bg-warning text-decoration-none link-light"><?= $ideia['estado'] ?></a>
                        </header>
                        <!-- Preview image figure-->
                        <video class="ratio-16x9 rounded-4 shadow mb-4" width="100%"
                            src="<?= video_apresentacao($ideia['video']) ?>" controls></video>
                        <!-- <figure class="mb-4"><img class="img-fluid rounded" src="https://dummyimage.com/900x400/ced4da/6c757d.jpg" alt="..." /></figure> -->
                        <!-- Post content-->
                        <section class="mb-5">
                            <h2 class="fw-bolder mb-4 mt-5">Descrição do Objetivo da Solução Proposta</h2>
                            <p class="fs-5 mb-4"><?= $ideia['descri_conceito'] ?></p>

                            <h6 class="lead fw-bold">Descrição de Estado da Proposta</h6>
                            <p class="fs-5 mb-4"><?= $ideia['info_complementar'] ?></p>
                        </section>
                    </article>
                    <!-- Avaliation section-->
                    <section class="">
                        <a name="" id="" class="btn btn-outline-info me-md-5"
                            href="<?= doc_apresentacao . $ideia['doc_apresent'] ?>" role="button">Ver Documento de
                            Apresentação</a>

                        <a name="" id="" class="btn btn-success mx-auto text-uppercase" href="avaliar.php?id=<?= $id ?>"
                            role="button">Avaliar</a>

                    </section>

                </div>
            </div>
        </div>
    </section>
</main>
<!-- FIM PAGINA -->




<script>

    $("#gerarComprovativoBtn").click(function () {
        if (!verificarAvaliacao(<?= $avaliacao ? 'true' : 'false' ?>)) {
            return false;
        }
        window.location.href = "comprovativoavaliacao.php?txtID=<?= $id ?>";
    });

    function verificarAvaliacao(aval) {
        if (!aval) {
            flashMessage("Avalie primeiro a ideia antes de gerar o documento.", "alert");
            return false;
        }
        return true;
    }


</script>

<?php include '_footer.php' ?>