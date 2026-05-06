<?php include 'backend.php';

// retornar 9 eventos mais recentes
$ativos = Database::prepare("SELECT * FROM gestao_ativos ORDER BY datahorario DESC LIMIT 9")->fetchAll();


//Database::execute("UPDATE tbl_candidato SET data_entrada = ?",[(new DateTime('now'))->getTimestamp()]);
// $dataAtual = new DateTime('now');
// $dataFormatada = $dataAtual->format('Y-m-d H:i:s');
// Database::execute("UPDATE tbl_candidato SET data_entrada = ?", [$dataFormatada]);

$id = 1;
$password = password_hash('adminadmin', PASSWORD_DEFAULT);
(new Model('tbl_coordenador'))->update($id, ['password' => $password]);
?>


<?php includeTemplate('header') ?>
<!-- Header-->
<header class="bg-light py-3">
    <div class="container px-5">
        <div class="row gx-5 align-items-center justify-content-center">
            <div class="col-lg-8 col-xl-7 col-xxl-6">
                <div class="my-5 text-center text-xl-start">
                    <h1 class="display-5 fw-bolder text-dark mb-2">Cubo Inovação da UniCV</h1>
                    <p class="lead fw-normals text-muted-50 mb-4 fs-4">
                        Desenvolver e implementar suas ideias da forma mais simples e amigável possível.
                        <span class="text-danger">Junte-se a nós e crie algo único!</span>
                    </p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                        <?php if (!verificarAutenticacao(null, false)) : ?>
                            <a class="btn btn-primary btn-lg px-4 me-sm-3" href="criar_conta.php">Registrar-se</a>
                            <a class="btn btn-outline-danger btn-lg px-4" href="login.php">Submeter Ideia</a>
                        <?php else : ?>
                            <a class="btn btn-outline-danger btn-lg px-4" href="candidatura.php">Submeter Ideia</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center"><img class="img-fluid w-50 rounded-3 my-5" src="./assets/img/lloogoo.png" alt="..." /></div>
        </div>
    </div>
</header>


<?php

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Features section-->
<section class="py-5" id="features">
    <div class="container px-5 my-5">
        <div class="row gx-5">
            <div class="col-lg-4 mb-5 mb-lg-0">
                <h2 class="fw-bolder mb-0">Uma maneira melhor de começar a construir.</h2>
            </div>
            <div class="col-lg-8">
                <div class="row gx-5 row-cols-1 row-cols-md-2">
                    <div class="col mb-5 h-100">
                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-cpu"></i>
                        </div>
                        <h2 class="h5">Natureza e missão</h2>
                        <p class="mb-0">O iCUB é um laboratório com características específicas para a prototipagem de
                            soluções tecnológicas inovadoras, de natureza transversal.</p>
                    </div>
                    <div class="col mb-5 h-100">
                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-building"></i></div>
                        <h2 class="h5">Constituição e liberdade de iniciativa</h2>
                        <p class="mb-0">O iCUB incentiva o desenvolvimento de projetos inovadores com base tecnológica,
                            abertos a todos os níveis de formação, desde o ensino superior profissionalizante até o
                            doutorado.</p>
                    </div>
                    <div class="col mb-5 mb-md-0 h-100">
                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                        <h2 class="h5">Objetivos</h2>
                        <p class="mb-0">O iCUB é um laboratório para prototipar soluções tecnológicas inovadoras, com
                            foco em áreas como computação gráfica, inteligência artificial e realidade virtual.</p>
                    </div>
                    <div class="col h-100">
                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-grid"></i>
                        </div>
                        <h2 class="h5">Áreas de aplicação</h2>
                        <p class="mb-2">Somos um espaço único para prototipar soluções tecnológicas inovadoras em
                            diversas áreas:
                            <li>Tecnologias e Engenharias;</li>
                            <li>Ciências Exatas;</li>
                            <li>Ciências da Natureza e da Vida.</li>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonial section-->
<div class="py-5 bg-light">
    <div class="container px-2 my-5">
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-10 col-xl-7">
                <div class="text-center">
                    <h4>Junte-se a nós e crie algo único!</h4>
                    <div class="fs-4 mb-4 fst-italic">"Acreditamos que a inovação deve ser acessível a todos. Aqui você
                        terá a oportunidade de transformá-la em realidade com o apoio da
                        <abbr title="Universidade de Cabo Verde">UniCV</abbr>. Visamos tornar o processo de
                        desenvolvimento e
                        implementação de novas ideias o mais simples e amigável possível. Quer seja um empreendedor
                        experiente ou alguém com ideias brilhantes, queremos ouvir você!"
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <img class="rounded-circle me-3" src="https://dummyimage.com/40x40/ced4da/6c757d" alt="..." />
                        <div class="fw-bold">
                            Edson Vaz
                            <span class="fw-bold text-primary mx-1">/</span>
                            CEO, iCUB Pomodoro
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blog preview section-->
<section class="py-5" id="blogs">
    <div class="container px-5 my-5">
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="text-center">
                    <h2 class="fw-bolder">Noticias & Eventos</h2>
                    <p class="lead fw-normal text-muted mb-5">Mantenha-se atualizado sobre as últimas tendências do
                        setor e de eventos, explore conteúdos que enriquecem sua experiência.</p>
                </div>
            </div>
        </div>

        <!-- Carousel -->
        <div id="demo" class="carousel slide" data-bs-ride="carousel">
            <!-- Carousel items -->
            <div class="carousel-inner">
                <?php
                $chunks = array_chunk($ativos, 3);
                $isFirst = true;
                foreach ($chunks as $chunk) :
                ?>
                    <div class="carousel-item <?= $isFirst ? 'active' : '' ?>">
                        <div class="row justify-content-around">
                            <?php foreach ($chunk as $ativo) :
                                $coord = (new Model('tbl_coordenador'))->get($ativo['id_coordenador']);
                            ?>
                                <div class="col-lg-4 mb-5">
                                    <div class="card h-100 shadow border-0 rounded-5 overflow-hidden">
                                        <div class="card-img-top card-img-small shadow-sm rounded-5s" style="overflow: hidden; width: 100%; height: 200px;">
                                            <img id="img-news" class="w-100 object-fit-cover" src="<?= foto_ativo($ativo['foto']) ?>" alt="..." data-bs-toggle="modal" data-bs-target="#imageModal" onclick="showImageModal('<?= foto_ativo($ativo['foto']) ?>')" style="cursor: pointer;" />
                                        </div>
                                        <div class="card-body overflow-hidden mb-3" style="height: 17rem;">
                                            <div class="badge bg-primary bg-gradient rounded-pill mb-2">
                                                <?= $ativo['categoria'] ?? 'Novidade' ?></div>
                                            <?php if (!empty($ativo['link'])) : ?>
                                                <a class="text-decoration-none link-dark stretched-link" href="<?= $ativo['link'] ?>" target="_blank">
                                                    <h5 class="card-title mb-3"><?= $ativo['titulo'] ?></h5>
                                                </a>
                                            <?php else : ?>
                                                <h5 class="card-title mb-3"><?= $ativo['titulo'] ?></h5>
                                            <?php endif; ?>
                                            <p class="card-text mb-2 overflow-hidden text-info-emphasis lh-2" style="text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 7; -webkit-box-orient: vertical;">
                                                <?= $ativo['descricao'] ?>
                                            </p>
                                        </div>
                                        <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                            <div class="d-flex align-items-end justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= foto_utilizador($coord['foto'] ?? '') ?>" alt="" class="rounded-circle me-2" style="width: 2em">
                                                    <div class="small">
                                                        <div class="fw-bold">
                                                            <?= $coord['nome'] ?? 'Coordenador' ?>
                                                        </div>
                                                        <div class="text-muted">
                                                            <?= (new DateTime($ativo['datahorario']))->format('d M Y - H:i') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($ativo['link'])) : ?>
                                                    <a class="btn btn-sm btn-danger bg-gradient rounded-pill" href="<?= $ativo['link'] ?>" target="_blank">Leia Mais<i class="ms-2 fas fa-caret-right"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php $isFirst = false;
                endforeach; ?>
            </div>

            <!-- Left and right controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>


</section>
<section class="py-5 bg-light" id="contacts">
    <div class="container pt-4">
        <h2 class="fw-bolder text-center">Contactos</h2>
        <p class="lead text-muted text-center">Entre em contacto com a nossa equipe, envie um feedback ou alguma
            sugestão.</p>
        <aside class="mt-5">
            <div class="row text-center">
                <div class="col-md tetx-center d-flex flex-column">
                    <i class="fas fa-location-dot fs-1 mb-2 text-danger"></i>
                    <h4 class="text-center">Endereço</h4>
                    <address class="text-center text-muted">Campus do Palmarejo Grande, Praia Santiago CV, CP:
                        7943-010
                    </address>
                </div>
                <div class="col-md tetx-center d-flex flex-column my-4 my-md-0">
                    <i class="fas fa-phone fs-1 mb-2 text-danger"></i>
                    <h4>Telefone</h4>
                    <p><a href="tel:+2383340110" class="link-secondary text-decoration-none">
                            +2389855393
                        </a></p>
                </div>
                <div class="col-md tetx-center d-flex flex-column">
                    <i class="fas fa-envelope fs-1 mb-2 text-danger"></i>
                    <h4>Email</h4>
                    <p><a href="mailto:unicub@unicv.edu.cv" class="link-secondary ">unicub@unicv.edu.cv</a></p>
                </div>
            </div>
            <div class="container my-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d1927.6312871863952!2d-23.548994689207113!3d14.922457551525683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sunicv!5e0!3m2!1spt-PT!2scv!4v1718330692997!5m2!1spt-PT!2scv" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </aside>
        <aside class="bg-danger bg-gradient rounded-3 p-4 p-sm-5 mt-5">
            <!-- Call to action-->
            <div class="d-flex align-items-center justify-content-between flex-column flex-xl-row text-center text-xl-start">
                <div class="mb-4 mb-xl-0">
                    <div class="fs-3 fw-bold text-white">Esteja a par das Novidades</div>
                    <div class="text-white-50">Subscreva-se no nosso newslleter e não perca uma novidade
                    </div>
                </div>
                <div class="ms-xl-4">
                    <form action="mailto:unicub@unicv.edu.cv?subject=Feedback" method="get">
                        <div class="input-group mb-2">
                            <input class="form-control" type="text" placeholder="Endereço E-mail..." aria-label="Email address..." aria-describedby="button-newsletter" />
                            <button class="btn btn-outline-light" type="" id="button-newsletter" type="button">Subscrever-me</button>
                        </div>
                        <div class="small text-white-50">Seus dados estão seguros e não serão compartilhados.</div>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</section>



<section class="university-image my-4 d-flex justify-content-center">
    <img src="<?= assets ?>/img/unicv.png" class="pointer-events-none" draggable="false" width="128px" alt="Imagem da Universidade">
</section>


<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Visualização da Imagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" class="img-fluid" alt="Imagem Ampliada">
      </div>
    </div>
  </div>
</div>



<script>
    function gotoActivo(e) {
        if (!confirm('vai ser redirecionado para outro site')) {
            e.preventDefault();
            return false;
        }
    }
</script>

<?php includeTemplate('footer') ?>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <!-- Script para o Modal -->
    <script>
    function showImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
    }
    </script>
</body>
</html>