<?php
//CLASE CORRESPONDIENTE AL MODELO DE LA VISTA
class viewModel
{
    //FUNCION QUE NOS RETORNA EL CONTENIDO QUE SE MUESTRA EN LA VISTA
    protected function get_view_model($view)
    {
        //LISTA BLANCA DE PALABRAS QUE SE PUEDEN ESCRIBIR EN LA URL 8.8.8.8/ejemplo
        $listaBlanca = [
            "adminlist", "adminsearch", "admin", "book", "bookconfig",
            "bookinfo", "catalog", "categorylist", "category", "client",
            "clientlist", "clientsearch", "company", "companylist",
            "home", "myaccount", "mydata", "provider", "providerlist",
            "search"
        ];
        //VALIDAMOS SI EL VALOR DE LA URL SE ENCUENTRA EN LA LISTA BLANCA
        if (in_array($view, $listaBlanca)) {
            //COMPROBAMOS SI ESE ARCHIVO EXISTE, LO REDIRIGIMOS HACIA LOS CONTENIDOS RESPECTIVOS
            if (is_file("./view/contents/" . $view . "-view.php")) {
                $contenido = "./view/contents/" . $view . "-view.php";
            } else {
                $contenido = "login";
            }
        } else if ($view == "login") {
            $contenido = "login";
        } else if ($view == "index") {
            $contenido = "login";
        } else {
            $contenido = "404";
        }

        return $contenido;
    }
}
