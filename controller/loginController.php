<?php
//VALIDAMOS SI HACEMOS PETICION AJAX E INCLUIMOS NUESTRO MODELO
if ($peticionAjax) {
    require_once("../model/loginModel.php");
} else {
    require_once("./model/loginModel.php");
}

class loginController extends loginModel
{
    //CREAMOS LA FUNCION PARA ENVIAR EL INICIO DE SESION
    public function iniciar_sesion_controller()
    {
        //RECIBIMOS LOS PARAMETROS
        $usuario = mainModel::limpiar_cadena($_POST["txtUsuario"]);
        $password = mainModel::limpiar_cadena($_POST["txtPassword"]);
        //ENCRIPTAMOS EL PASSWORD
        $password = mainModel::encriptar($password);
        //PREPARAMOS UN ARRAY DE DATOS
        $datosLogin = array(
            "usuario" => $usuario,
            "clave" => $password
        );
        //ENVIAMOS EL ARRAY HACIA EL MODELO
        $datosCuenta = loginModel::iniciar_sesion_model($datosLogin);
        if ($datosCuenta->rowCount() > 0) {
            //ALMACENAMOS LOS DATOS DE LOGIN
            $row = $datosCuenta->fetch();
            $fechaActual = date("Y-m-d");
            $yearActual = date("Y");
            $horaActual = date("H:i:s a");

            $consulta1 = mainModel::ejecutar_consulta_simple("SELECT id FROM bitacora");

            $numero = ($consulta1->rowCount()) + 1;

            $codigoB = mainModel::generar_codigo_aleatorio("CB", 8, $numero);
            //ARRAY DE DATOS QUE SE ENVIA AL MODELO
            $datosBitacora = array(
                "codigo" => $codigoB,
                "fecha" => $fechaActual,
                "horainicio" => $horaActual,
                "horafinal" => "Sin registro",
                "tipo" => $row["CuentaTipo"],
                "anio" => $yearActual,
                "cuenta" => $row["CuentaCodigo"]
            );
            //MANDAMOS A INSERTAR LOS DATOS
            $insertarBitacora = mainModel::guardar_bitacora($datosBitacora);
            if ($insertarBitacora->rowCount() >= 1) {
                //INICIAR SESION
                session_start(["name" => "SBP"]);
                $_SESSION["usuario_sbp"] = $row["CuentaUsuario"];
                $_SESSION["tipo_sbp"] = $row["CuentaTipo"];
                $_SESSION["privilegio_sbp"] = $row["CuentaPrivilegio"];
                $_SESSION["foto_sbp"] = $row["CuentaFoto"];
                $_SESSION["codigo_cuenta_sbp"] = $row["CuentaCodigo"];
                $_SESSION["codigo_bitacora_sbp"] = $codigoB;
                //CREAMOS UN TOKEN PARA CADA SESION
                $_SESSION["token_sbp"] = md5(uniqid(mt_rand(), true));
                if ($row["CuentaTipo"] == "Administrador") {
                    //GUARDAMOS EN UNA VARIABLE UNA URL
                    $url = SERVERURL . "home/";
                } else {
                    $url = SERVERURL . "catalog/";
                }
                return $urlLocation = "<script> window.location='" . $url . "' </script>";
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "No hemos podido iniciar la sesión por problemas técnicos, por favor intente nuevamente",
                    "Tipo" => "error"
                ];
            }
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El Usuario y/o Contraseña que acabas de ingresar son incorrectos",
                "Tipo" => "error"
            ];
            return mainModel::sweet_alert($alerta);
        }
    }
    //CREAMOS LA FUNCION  PARA CERRAR LA SESION
    public function cerrar_sesion_controller()
    {
        session_start(["name" => "SBP"]);
        $token = mainModel::desencriptar($_GET["Token"]);
        $horasalida = date("H:i:s a");
        //CREAMOS EL ARRAY DE LOS DATOS PARA ENVIAR AL MODELO
        $datos = array(
            "Usuario" => $_SESSION["usuario_sbp"],
            "Token_S" => $_SESSION["token_sbp"],
            "Token" => $token,
            "Codigo" => $_SESSION["codigo_bitacora_sbp"],
            "Horafinal" => $horasalida
        );
        return loginModel::cerrar_sesion_model($datos);
    }
    //CREAMOS LA FUNCION PARA FORZAR CERRAR SESION A USUARIOS QUE NO HAYAN INICIADO SESION
    public function forzar_cierre_sesion_controller()
    {
        session_destroy();
        return header("Location: " . SERVERURL . "login/");
    }
}
