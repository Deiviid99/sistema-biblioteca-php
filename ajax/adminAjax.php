<?php
//RECIBE LOS DATOS VIA AJAX
//DECLARAMOS LA VARIABLE DE PETICION AJAX
$peticionAjax = true;
//INCLUIMOS LA CONFIGURACION GENERAL
require_once("../core/configGeneral.php");
if (isset($_POST["dni-reg"])) {
    require_once("../controller/adminController.php");
    //INSTANCIAMOS EL CONTROLADOR
    $insAdmin = new adminController();
    //VALIDAMOS LOS CAMPOS REQUERIDOS
    if (
        isset($_POST["dni-reg"]) && isset($_POST["nombre-reg"]) && isset($_POST["apellido-reg"]) &&
        isset($_POST["telefono-reg"]) && isset($_POST["direccion-reg"]) && isset($_POST["usuario-reg"]) &&
        isset($_POST["password1-reg"]) && isset($_POST["password2-reg"]) && isset($_POST["email-reg"])
    ) {
        //MANDAMOS A EJECUTAR EL CONTROLADOR DEL ADMINISTRADOR
        echo $insAdmin->agregar_admin_controller();
    }
} else {
    session_start();
    session_destroy();
    //MANTENER LA SEGURIDAD DE LA PAGINA 
    echo "<script> window.location.href='" . SERVERURL . "login/' </script>";
}
