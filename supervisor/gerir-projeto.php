<?php include '../../backend.php';

$id = (int) ($_GET['id'] ?? 0);

$projeto = (new Model('tbl_projeto'))->get($id);

if (!$projeto) {
    // die('Error Projeto Não Identificado');  // no permitir que um utilizador false seja apresentado
}
include '../_header.php';
?>

<!-- PAGINA -->
<main class="container px-4">

    <h1>pagina para Gerir Projeto <?= $projeto['nome'] ?? 'X' ?></h1>
    <!-- Page Content-->
    

</main>
<!-- FIM PAGINA -->
<?php include '../_footer.php' ?>