<?php
//VALIDAMOS SI HACEMOS PETICION AJAX E INCLUIMOS EL MODELO PRINCIPAL
if ($peticionAjax) {
    require_once("../core/mainModel.php");
} else {
    require_once("./core/mainModel.php");
}
//HEREDAMOS LA CLASE DEL MODELO PRINCIPAL DONDE SE ENCUENTRA LA CONEXION
class adminModel extends mainModel{
    //INSERT DE LA TABLA  ADMINISTRADOR
    protected function agregar_admin_model($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO ADMIN (AdminDNI, AdminNombre, AdminApellido, AdminTelefono, AdminDireccion,
                                                CuentaCodigo) VALUES (:dni,:nombre,:apellido,:telefono,:direccion,:codigo)");
        $sql->execute(array(
                ":dni"=>$datos["dni"],
                ":nombre"=>$datos["nombre"],
                ":apellido"=>$datos["apellido"],
                ":telefono"=>$datos["telefono"],
                ":direccion"=>$datos["direccion"],
                ":codigo"=>$datos["codigo"]
        ));                                
        return $sql;        
    }
}

