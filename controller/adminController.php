<?php
//VALIDAMOS SI HACEMOS PETICION AJAX E INCLUIMOS NUESTRO MODELO
if ($peticionAjax) {
    require_once("../model/adminModel.php");
} else {
    require_once("./model/adminModel.php");
}
//HEREDAMOS LA CLASE DEL MODELO CORRESPONDIENTE QUE SE INCLUYE
class adminController extends adminModel
{
    //AGREGAR EL ADMINISTRADOR
    public function agregar_admin_controller()
    {
        $dni = mainModel::limpiar_cadena($_POST["dni-reg"]);
        $nombre = mainModel::limpiar_cadena($_POST["nombre-reg"]);
        $apellido = mainModel::limpiar_cadena($_POST["apellido-reg"]);
        $telefono = mainModel::limpiar_cadena($_POST["telefono-reg"]);
        $direccion = mainModel::limpiar_cadena($_POST["direccion-reg"]);
        $usuario = mainModel::limpiar_cadena($_POST["usuario-reg"]);
        $password1 = mainModel::limpiar_cadena($_POST["password1-reg"]);
        $password2 = mainModel::limpiar_cadena($_POST["password2-reg"]);
        $email = mainModel::limpiar_cadena($_POST["email-reg"]);
        $genero = mainModel::limpiar_cadena($_POST["optionsGenero"]);
        $privilegio = mainModel::limpiar_cadena($_POST["optionsPrivilegio"]);

        //VALIDAR EL TIPO DE GENERO
        if ($genero == "Masculino") {
            $foto = "TeacherMaleAvatar.png";
        } else {
            $foto = "TeacherFemaleAvatar.png";
        }
        //VALIDACIONES DEL REGISTRO ADMINISTRADOR
        if ($password1 != $password2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "Las contraseñas que acabas de ingresar no coinciden, por favor intenta nuevamente",
                "Tipo" => "error"
            ];
        } else {
            $consulta1 = mainModel::ejecutar_consulta_simple("SELECT AdminDNI FROM admin WHERE AdminDNI='$dni'");
            if ($consulta1->rowCount() >= 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El DNI que acabas de ingresar ya se encuentra registrado en el sistema",
                    "Tipo" => "error"
                ];
            } else {
                if ($email != "") {
                    $consulta2 = mainModel::ejecutar_consulta_simple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail='$email'");
                    $ec = $consulta2->rowCount();
                } else {
                    $ec = 0;
                }
                if ($ec >= 1) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "El Email que acabas de ingresar ya se encuentra registrado en el sistema",
                        "Tipo" => "error"
                    ];
                } else {
                    $consulta3 =  mainModel::ejecutar_consulta_simple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario='$usuario'");
                    if ($consulta3->rowCount() >= 1) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrio un error inesperado",
                            "Texto" => "El Usuario que acabas de ingresar ya se encuentra registrado en el sistema",
                            "Tipo" => "error"
                        ];
                    } else {
                        $consulta4 = mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta");
                        $numero = ($consulta4->rowCount()) + 1;
                        //GENERAMOS EL NOMBRE DE USUARIO
                        $codigo = mainModel::generar_codigo_aleatorio("AC", 8, $numero);
                        //ENCRIPTAMOS EL PASSWORD
                        $clave = mainModel::encriptar($password1);
                        //LLENAMOS EL ARRAY CON LOS PARAMETROS INGRESADOS EN EL FORMULARIO
                        $dataAC = array(
                            "codigo" => $codigo,
                            "privilegio" => $privilegio,
                            "user" => $usuario,
                            "pass" => $clave,
                            "email" => $email,
                            "estado" => "Activo",
                            "tipo" => "Administrador",
                            "genero" => $genero,
                            "foto" => $foto
                        );
                        //GUARDAR LA VARIABLE ARRAY EN EL MODELO DE LA CUENTA
                        $guardarCuenta = mainModel::agregar_cuenta($dataAC);
                        //COMPROBAR SI SE GUARDO LA CUENTA
                        if ($guardarCuenta->rowCount() >= 1) {
                            $dataAD = array(
                                "dni" => $dni,
                                "nombre" => $nombre,
                                "apellido" => $apellido,
                                "telefono" => $telefono,
                                "direccion" => $direccion,
                                "codigo" => $codigo
                            );
                            //GUARDAMOS LA VARIABLE ARRAY EN EL MODELO DEL ADMINISTRADOR
                            $guardarAdmin = adminModel::agregar_admin_model($dataAD);
                            if ($guardarAdmin->rowCount() >= 1) {
                                $alerta = [
                                    "Alerta" => "limpiar",
                                    "Titulo" => "Administrador registrado",
                                    "Texto" => "El administrador se registro con éxito en el sistema",
                                    "Tipo" => "success"
                                ];
                            } else {
                                mainModel::eliminar_cuenta($codigo);
                                $alerta = [
                                    "Alerta" => "simple",
                                    "Titulo" => "Ocurrio un error inesperado",
                                    "Texto" => "No hemos podido registrar el administrador",
                                    "Tipo" => "error"
                                ];
                            }
                        } else {
                            $alerta = [
                                "Alerta" => "simple",
                                "Titulo" => "Ocurrio un error inesperado",
                                "Texto" => "No hemos podido registrar el administrador",
                                "Tipo" => "error"
                            ];
                        }
                    }
                }
            }
        }
        return mainModel::sweet_alert($alerta);
    }
}
