</main>
<!-- Footer-->
<footer class="bg-light py-4 mt-auto">
    <div class="container px-5">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row">
            <div class="col-auto">
                <div class="small m-0 text-dark">© <?= date("Y")?> ICUB. Todos os direitos reservados.</div>
            </div>
            <div class="col-auto">
                <a class="link-dark small" href="https://unicv.edu.cv" target="_blank">UniCV</a>
                <span class="text-black mx-1">&middot;</span>
                <a class="link-dark small" href="<?= BASE_URL ?>/termos.php">Termos</a>
                <span class="text-black mx-1">&middot;</span>
                <a class="link-dark small" href="<?= BASE_URL ?>/politicas.php">Políticas</a>
                <span class="text-black mx-1">&middot;</span>
                <a class="link-dark small" href="mailto:icub@unicv.edu.cv?subject=Feedback da Vossa Pagina">Contacto</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>

<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

<script>
    $(document).ready(function () {
        $("#table_id").DataTable({
            "pageLength": 10,
            lengthMenu: [
                [3, 10, 25, 50],
                [3, 10, 25, 50]
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-PT.json"
            }
        });

        // Adicionando estilo para a tabela
        $("#table_id_wrapper").addClass("table-responsive");

        // Estilizando os botões de paginação
        $(".paginate_button").addClass("btn btn-sm btn-outline-secondary");

        // Estilizando os botões de alteração de tamanho de página
        $(".custom-select").addClass("form-select form-select-sm");

        // Estilizando os botões de busca
        $(".dataTables_filter input").addClass("form-control form-control-sm");

    });
</script>
<!-- 
<style>
    /* Estilo para os botões de navegação */
    .paginate_button {
        color: #ffffff !important;
        border-color: #ffffff !important;
        background-color: #a90e2a !important;
        margin: 2px !important;
    }

    /* Estilo para os botões de navegação quando estão ativos */
    .paginate_button.active {
        background-color: #ffffff !important;
        color: #a90e2a !important;
    }

    /* Estilo para a cor do contorno da tabela */
    .table-bordered th,
    .table-bordered td {
        border-color: #ffffff !important;
    }
</style> -->

<script>
    function eliminar(id) {
        Swal.fire({
            title: 'Desejas Eliminar o Registo?',
            showCancelButton: true,
            confirmButtonText: 'Sim, Eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "delete.php?txtID=" + id;
            }
        })
    }
</script>

<!-- CSS e Codigo javascript para colocar o menu activo de acordo com a pagina -->
<style>
    .actived {
        color: whitesmoke;
    }
</style>
<script>

    // Obter o nome do arquivo da página atual atravez do php pathinfo['basename'] 
    var filename = '<?= pathinfo($_SERVER['PHP_SELF'])['basename'] ?>';
    // Selecionar todos os links de menu
    var links = document.querySelectorAll('.nav-link');
    // Iterar sobre cada link
    links.forEach(function (link) {
        // Obter o nome do arquivo do link
        var linkUrl = link.getAttribute('href');
        var linkFilename = linkUrl.substring(linkUrl.lastIndexOf('/') + 1);
        // Comparar o nome do arquivo da página atual com o nome do arquivo do link
        if (filename === linkFilename) {
            // Adicionar a classe "active" ao link
            link.classList.add('actived');
        }
    });
</script>

</body>

</html>