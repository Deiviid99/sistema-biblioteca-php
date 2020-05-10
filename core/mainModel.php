<?php
//VALIDAMOS SI REALIZAMOS PETICION AJAX E INCLUIMOS EL CORE DEL NEGOCIO
if ($peticionAjax) {
    require_once("../core/configApp.php");
} else {
    require_once("./core/configApp.php");
}
//CREAMOS UNA CLASE CORRESPONDIENTE AL MODELO PRINCIPAL CON FUNCIONES QUE DISTRIBUYE A TODO EL SISTEMA
class mainModel
{
    //CONECTAMOS A LA BASE DE DATOS METODO PDO
    protected function conectar()
    {
        try {
            $conexion = new PDO(DB_SG, DB_USER, DB_PASSWORD);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec(DB_CHARACTER);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
        return $conexion;
    }

    //FUNCION PARA CONSULTAS SIMPLES
    protected function ejecutar_consulta_simple($consulta)
    {
        //INSTANCIAMOS LA FUNCION CONEXION Y PREPARAMOS LA CONSULTA
        $respuesta = self::conectar()->prepare($consulta);
        $respuesta->execute();
        return $respuesta;
    }

    //FUNCION PARA ENCRIPTAR UN PASSWORD POR HASH
    public function encriptar($string)
    {
        $output = false;
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    //FUNCION PARA DESENCRIPTAR 
    protected function desencriptar($string)
    {
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_decrypt(base64_encode($string), METHOD, $key, 0, $iv);
        return $output;
    }
    //GENERAR CODIGO ALEATORIO
    protected function generar_codigo_aleatorio($letra, $longitud, $num)
    {
        for ($i = 1; $i <= $longitud; $i++) {
            $numero = rand(0, 9);
            $letra .= $numero;
        }
        return $letra . $num;
    }
    //LIMPIAR LAS CADENAS CONTRA LA INYECCION SQL (VALIDACIONES)
    protected function limpiar_cadena($cadena)
    {
        //ELIMINA LOS ESPACIOS EN BLANCO
        $cadena = trim($cadena);
        //QUITA LAS BARRAS INVERTIDAS DE UN STRING
        $cadena = stripcslashes($cadena);
        //TOMAR UNA BUSQUEDA Y DECIR QUE SE QUIERE BUSCAR EN UN STRING 
        $cadena = str_ireplace("<script>", "", $cadena);
        $cadena = str_ireplace("</script>", "", $cadena);
        $cadena = str_ireplace("<script src", "", $cadena);
        $cadena = str_ireplace("<script type>", "", $cadena);
        $cadena = str_ireplace("SELECT * FROM", "", $cadena);
        $cadena = str_ireplace("UPDATE FROM", "", $cadena);
        $cadena = str_ireplace("DELETE FROM", "", $cadena);
        $cadena = str_ireplace("INSERT INTO", "", $cadena);
        $cadena = str_ireplace("--", "", $cadena);
        $cadena = str_ireplace("^", "", $cadena);
        $cadena = str_ireplace("[", "", $cadena);
        $cadena = str_ireplace("]", "", $cadena);
        $cadena = str_ireplace("==", "", $cadena);
        $cadena = str_ireplace("{", "", $cadena);
        $cadena = str_ireplace("}", "", $cadena);
        $cadena = str_ireplace(";", "", $cadena);
        return $cadena;
    }

    //INTERACTUAR CON LOS MODAL MESSAGE
    protected function sweet_alert($datos)
    {
        if ($datos['Alerta'] == "simple") {
            $alerta = "
                    <script>
                            swal('" . $datos['Titulo'] . "', 
                                  '" . $datos['Texto'] . "', 
                                  '" . $datos['Tipo'] . "'
                                 );
                    </script>
                      ";
        } elseif ($datos['Alerta'] == "recargar") {
            $alerta = "
                    <script>
                         swal({
                         title: '" . $datos['Titulo'] . "',
                         text: '" . $datos['Texto'] . "',
                         type: '" . $datos['Tipo'] . "',
                         confirmButtonText: 'Aceptar'
                         }).then(function(){
                         location.reload();
                         });
                    </script>
                      ";
        } elseif ($datos['Alerta'] == "limpiar") {
            $alerta = "
                    <script>
                         swal({
                         title: '" . $datos['Titulo'] . "',
                         text: '" . $datos['Texto'] . "',
                         type: '" . $datos['Tipo'] . "',
                         confirmButtonText: 'Aceptar'
                         }).then(function(){
                            $('.formularioAjax')[0].reset();
                         });
                    </script>
                      ";
        }
        return $alerta;
    }

    //INSERT DE LA TABLA CUENTA
    protected function agregar_cuenta($datos)
    {
        $sql = self::conectar()->prepare("INSERT INTO cuenta(CuentaCodigo, CuentaPrivilegio	, CuentaUsuario, CuentaClave, CuentaEmail,
                                                             CuentaEstado, CuentaTipo, CuentaGenero, CuentaFoto) 
                                                        VALUES(:codigo,:privilegio,:user,:pass,:email,:estado,:tipo,:genero,:foto)");
        $sql->execute(array(
            ":codigo" => $datos["codigo"],
            ":privilegio" => $datos["privilegio"],
            ":user" => $datos["user"],
            ":pass" => $datos["pass"],
            ":email" => $datos["email"],
            ":estado" => $datos["estado"],
            ":tipo" => $datos["tipo"],
            ":genero" => $datos["genero"],
            ":foto" => $datos["foto"]
        ));
        return $sql;
    }

    //DELETE DE LA TABLA CUENTA
    protected function eliminar_cuenta($codigo)
    {
        $sql = self::conectar()->prepare("DELETE FROM cuenta WHERE CuentaCodigo=:codigo");
        $sql->execute(array(":codigo" => $codigo));
        return $sql;
    }

    //INSERT DEL INICIO DE SESION HACIA BITACORA EN EL SISTEMA DE LOS USUARIOS 
    protected function guardar_bitacora($datos)
    {
        $sql = self::conectar()->prepare("INSERT INTO bitacora (BitacoraCodigo, BitacoraFecha, BitacoraHoraInicio,
                                        BitacoraHoraFinal, BitacoraTipo, BitacoraYear, CuentaCodigo) 
                                        VALUES (:codigo, :fecha, :horainicio, :horafinal, :tipo, :anio, :cuenta)");
        $sql->execute(array(
            ":codigo" => $datos["codigo"],
            ":fecha" => $datos["fecha"],
            ":horainicio" => $datos["horainicio"],
            ":horafinal" => $datos["horafinal"],
            ":tipo" => $datos["tipo"],
            ":anio" => $datos["anio"],
            ":cuenta" => $datos["cuenta"]
        ));
        return $sql;
    }

    //UPDATE LA HORA FINAL DEL USUARIO BITACORA EN EL SISTEMA
    protected function actualizar_bitacora($codigo, $hora)
    {
        $sql = self::conectar()->prepare("UPDATE bitacora SET BitacoraHoraFinal=:horafinal WHERE BitacoraCodigo=:codigo");
        $sql->execute(array(
            ":horafinal" => $hora,
            ":codigo" => $codigo
        ));
        return $sql;
    }

    //DELETE DATOS BITACORA EN EL SISTEMA
    protected function eliminar_bitacora($codigo)
    {
        $sql = self::conectar()->prepare("DELETE FROM bitacora WHERE CuentaCodigo=:codigo");
        $sql->execute(array(
            ":codigo" => $codigo
        ));
        return $sql;
    }
}
