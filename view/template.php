<!DOCTYPE html>
<html lang="es">

<head>
    <title><?php echo COMPANY; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>view/css/main.css">
    <!--====== Scripts -->
    <?php include("./view/modules/script.php"); ?>
</head>

<body>
    <?php
    //VA A OBTENER UNA PETICION SI USA AJAX
    $peticionAjax = false;
    //OBTENEMOS DE LA FUNCION VISTA CONTROLADOR
    require_once("./controller/viewController.php");
    $vt = new viewController();
    $vistas = $vt->get_view_controller();
    //COMPARAMOS SI OBTIENE EL VALOR DE VISTA COMO LOGIN Y LO REDIRIGIMOS A SU VISTA
    if ($vistas == "login" || $vistas == "404") :
        if ($vistas == "login") {
            require_once("./view/contents/login-view.php");
        } else {
            require_once("./view/contents/404-view.php");
        } else :
        session_start(["name" => "SBP"]);
        //INCLUIMOS EL CONTROLADOR DE LAS SESIONES PARA VALIDAR    
        require_once("./controller/loginController.php");
        $logControlador = new loginController();
        //COMPROBAMOS SI VIENEN DEFINIDAS LAS VARIABLES DE SESION PARA VER SI EXISTE O NO SESSION
        if (!isset($_SESSION["token_sbp"]) || !isset($_SESSION["usuario_sbp"])) {
            $logControlador->forzar_cierre_sesion_controller();
        }
    ?>
        <!-- SideBar -->
        <?php include("view/modules/navlateral.php"); ?>
        <!-- Content page-->
        <section class="full-box dashboard-contentPage">
            <!-- NavBar -->
            <?php include("view/modules/navbar.php"); ?>
            <!-- Content page -->
            <?php require_once($vistas); ?>
        </section>
    <?php
        include("./view/modules/logoutScript.php");
    endif;
    ?>
    <script>
        $.material.init();
    </script>
</body>

</html>