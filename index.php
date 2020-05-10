<?php
require_once("./core/configGeneral.php");
//INCLUIMOS EL CONTROLADOR DE LA VISTA
require_once("./controller/viewController.php");
//INSTANCIAMOS LA CLASE DEL CONTROLADOR E INDICAMOS LA FUNCION CREADA
$template = new viewController();
$template->get_template_controller();
