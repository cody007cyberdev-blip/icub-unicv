</main>
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">&copy; icub unicv 2024, Todos os direitos reservados.</div>
            <div>
                <a href="<?= BASE_URL ?>/politicas.php">Politas & Pricavidade</a>
                &middot;
                <a href="<?= BASE_URL ?>/termos.php">Termos &amp; Condições</a>
            </div>
        </div>
    </div>
</footer>
</div>
</div>

<script src="<?= assets ?>/js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="<?= assets ?>/js/chart-area-demo.js"></script>
<script src="<?= assets ?>/js/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="<?= assets ?>/js/datatables-simple-demo.js"></script>
<script src="https://unpkg.com/feather-icons"></script>

<!-- codigos javascript para varas funcionalidades em coord -->
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
</script>


<?php if ($msg = flashMessage()): ?>
    <script>
        Toast.fire({
            icon: "<?= flashStatus() ?? 'info' ?>",
            title: "<?= $msg ?>"
        });
    </script>
<?php endif; ?>

<!-- funcao para colocar imagem no sweet alert -->
<script>
    function imagem(e) {
        Swal.fire({
            imageUrl: `${e.src}`,
            imageHeight: 350,
            imageAlt: "Foto do Utilizador"
        });
    }
    function imagemLink(link) {
        Swal.fire({
            imageUrl: `${link}`,
            imageHeight: 350,
            imageAlt: "Imagem"
        });
    }
</script>

<script>
    function eliminarUtilizador(id, nome = 'usuario', role = 'Usuário') {
        Swal.fire({
            title: `Eliminar ${nome}?`,
            text: `Desejas mesmo Eliminar este ${role}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, Eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aqui, fazemos uma chamda POST para o proprio site, enviando id para $_POST['deleteID']
                $.post({
                    url: "#",
                    data: {
                        deleteID: id
                    },
                    // Apos terminar tarefa: mostrar o retorno
                    success: res => {
                        window.location.reload();
                    }
                });
            }
        })
    }


    function enableFormFields(e) {
        var formFields = document.querySelectorAll('input, select, textarea');
        formFields.forEach(function (field) {
            field.disabled = false;
            field.removeAttribute('readonly');
        });
        e.classList.add("d-none");
        $("#btn-update").removeClass("d-none");
        $("#btn-cancel").removeClass("d-none");
    }
</script>

</body>

</html>