window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    var myDataTable;
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        myDataTable =new simpleDatatables.DataTable(datatablesSimple);
    }
});
