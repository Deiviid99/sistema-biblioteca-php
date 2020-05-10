<?php
//INCLIMOS EL MODELO CORRESPONDIENTE A LA VISTA
require_once("./model/viewModel.php");

//HEREDAMOS LA CLASE DEL MODELO INCLUIDO
class viewController extends viewModel
{
    //OBTENER LA PLANTILLA PARA MOSTRARLA EN PANTALLA
    public function get_template_controller()
    {
        return  require_once("./view/template.php");
    }
    //OBTENEMOS LA RESPECTIVA VISTA PARA CADA DIRECCION
    public function get_view_controller()
    {
        //VALIDAR SI LA VARIBALE QUE ESTA EN EL HTACCESS VIENE DEFINIDA
        if (isset($_GET["view"])) {
            //DIVIDIR UNA VARIBALE A PARTIR DE UN DELIMITADOR -- ARRAY
            $ruta = explode("/", $_GET["view"]);
            //ACCEDEMOS A LA FUNCION Y LE INDICAMOS EL PARAMETRO EN EL INDICE 0
            $respuesta = viewModel::get_view_model($ruta[0]);
        } else {
            $respuesta = "login";
        }
        return $respuesta;
    }
}
