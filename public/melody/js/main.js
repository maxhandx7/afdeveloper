window.addEventListener("DOMContentLoaded", () => {

    setTimeout(function () {

        var successMessage = document.getElementById('success-message');

        if (successMessage) {

        successMessage.style.opacity = '0';

        setTimeout(function () {

            successMessage.style.display = 'none';

        }, 1000);

    }

    }, 5000);



    /* const temaGuardado = localStorage.getItem("tema");

    if (temaGuardado === "oscuro") {

        temaOscuro();

    } else if (temaGuardado === "claro") {

        temaClaro();

    } */

});





function confirmDelete(event) {
    event.preventDefault(); 
    swal({
        title: "¿Estás seguro?",
        text: "¡No podrás deshacer esta acción!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            event.target.closest('form').submit(); 
        }
    });
}