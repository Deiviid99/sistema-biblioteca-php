<?php
//RECIBE LOS DATOS VIA AJAX
//DECLARAMOS LA VARIABLE DE PETICION AJAX
$peticionAjax = true;
//INCLUIMOS LA CONFIGURACION GENERAL
require_once("../core/configGeneral.php");
if (isset($_GET["Token"])) {
    require_once("../controller/loginController.php");
    $logout = new loginController();
    echo $logout->cerrar_sesion_controller();
} else {
    session_start();
    session_destroy();
    //MANTENER LA SEGURIDAD DE LA PAGINA 
    echo "<script> window.location.href='" . SERVERURL . "login/' </script>";
}
