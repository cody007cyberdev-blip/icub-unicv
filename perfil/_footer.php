<footer class="footer-admin mt-auto footer-light">
    <div class="container-xl px-4">
        <div class="row">
            <div class="col-md-6 small">© <?= date("Y")?>, ICUB. Todos os direitos reservados.</div>
            <div class="col-md-6 text-md-end small">
            <a  href="<?= BASE_URL ?>/politicas.php">Políticas & Privaciade</a>
                ·
                <a href="<?= BASE_URL ?>/termos.php">Termos</a>
            </div>
        </div>
    </div>
</footer>


<?php if ($msg = flashMessage()): ?>
    <script>
        Swal.fire({
            title: "Atenção",
            text: '<?= $msg ?>',
            icon: '<?= flashStatus() ?? 'info' ?>'
        });
    </script>
<?php endif; ?>

<!-- funcao para colocar imagem no sweet alert -->
<script>
    function imagem(e) {
        Swal.fire({
            imageUrl: `${e.src}`,
            imageHeight: 350,
            imageAlt: "Foto do candidato"
        });
    }
</script>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="<?= assets ?>/js/profile-scripts.js"></script>


<script src="https://assets.startbootstrap.com/js/sb-customizer.js"></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vedd3670a3b1c4e178fdfb0cc912d969e1713874337387"
    integrity="sha512-EzCudv2gYygrCcVhu65FkAxclf3mYM6BCwiGUm6BEuLzSb5ulVhgokzCZED7yMIkzYVg65mxfIBNdNra5ZFNyQ=="
    data-cf-beacon='{"rayId":"885eafb4ed14f0df","version":"2024.4.1","token":"6e2c2575ac8f44ed824cef7899ba8463"}'
    crossorigin="anonymous"></script>
</body>

</html>