<div class="full-box login-container cover">
    <form action="" method="POST" autocomplete="off" class="logInForm">
        <p class="text-center text-muted"><i class="zmdi zmdi-account-circle zmdi-hc-5x"></i></p>
        <p class="text-center text-muted text-uppercase">Inicia sesión con tu cuenta</p>
        <div class="form-group label-floating">
            <label class="control-label" for="UserName">Usuario</label>
            <input required="" class="form-control" id="UserName" name="txtUsuario" type="text">
            <p class="help-block">Escribe tú nombre de usuario</p>
        </div>
        <div class="form-group label-floating">
            <label class="control-label" for="UserPass">Contraseña</label>
            <input required="" class="form-control" id="UserPass" name="txtPassword" type="password">
            <p class="help-block">Escribe tú contraseña</p>
        </div>
        <div class="form-group text-center">
            <input type="submit" value="Iniciar sesión" class="btn btn-info" style="color: #FFF;">
        </div>
    </form>
</div>
<?php
//SI VIENE DEFINIDO LAS VARIABLES
if (isset($_POST["txtUsuario"]) && isset($_POST["txtPassword"])) {
    require_once("./controller/loginController.php");
    $login = new loginController();
    echo $login->iniciar_sesion_controller();
}
?>