<?php
//VALIDAMOS SI HACEMOS PETICION AJAX E INCLUIMOS EL MODELO PRINCIPAL
if ($peticionAjax) {
    require_once("../core/mainModel.php");
} else {
    require_once("./core/mainModel.php");
}

class loginModel extends mainModel
{
    //CREAMOS EL MODELO PARA INICIAR LA SESION
    protected function iniciar_sesion_model($datos)
    {
        $sql = self::conectar()->prepare("SELECT * FROM cuenta WHERE CuentaUsuario=:usuario AND 
                                        CuentaClave=:clave AND CuentaEstado='Activo'");
        $sql->execute(array(
            ":usuario" => $datos["usuario"],
            ":clave" => $datos["clave"]
        ));
        return $sql;
    }

    //CREAMOS EL MODELO PARA CERRAR LA SESION
    protected function cerrar_sesion_model($datos)
    {
        if ($datos["Usuario"] != "" && $datos["Token_S"] ==  $datos["Token"]) {
            $Abitacora = mainModel::actualizar_bitacora($datos["Codigo"], $datos["Horafinal"]);
            if ($Abitacora->rowCount() == 1) {
                //VACIAR LA SESION
                session_unset();
                //ELIMINAR LA SESION O DESTRUIRLA
                session_destroy();
                $respuesta = "true";
            } else {
                $respuesta = "false";
            }
        } else {
            $respuesta = "false";
        }
        return $respuesta;
    }
}
