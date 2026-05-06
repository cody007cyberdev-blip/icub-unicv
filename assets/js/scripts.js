/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

/**
 * Destacar o menu activo com a classe actived
 */
let caminhoCompleto = window.location.href;
var filename = caminhoCompleto.split('/').pop();
var links = document.querySelectorAll('.nav-link');
// Iterar sobre cada link e Comparar o nome do arquivo da página atual com o nome do arquivo no link
links.forEach(function (link) {
    var linkUrl = link.getAttribute('href');
    var linkFilename = linkUrl;//.substring(linkUrl.lastIndexOf('/') + 1);
    if (/*filename*/ caminhoCompleto === linkFilename) {
        //Adicionar a classe "active" ao link
        link.classList.add('actived');
    }
});

