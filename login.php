<?php
    //BASE DE DATOS
    require 'includes/app.php';
    $db = conectarDB();

    //AUTENTICAR EL USUARIO
    $errores = [];
    $email = "";
    $password = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        $email = mysqli_real_escape_string($db, filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if(!$email){
            $errores[] = "El email es obligatorio o no es válido";
        }
        if(!$password){
            $errores[] = "El password es obligatorio"; 
        }

        if(empty($errores)){
            //revisar si el usuario existe
            $query = "SELECT * FROM usuarios WHERE email = '${email}'";
            $resultado = mysqli_query($db, $query);

            if($resultado->num_rows){
                //revisar si el password es correcto
                $usuario = mysqli_fetch_assoc($resultado);

                //revisar si el password es correcto
                $autenticado = password_verify($password, $usuario['password']);

                if($autenticado){
                    //el usuario esta autenticado ingresa al sistema
                    session_start();

                    //llenar de datos la sesion
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /admin');
                }else{
                    $errores[] = "El password es incorrecto";
                }
            }else{
                $errores[] = "El usuario No Existe";
            }

        }
    }


    //INCLUYE EL HEADER
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error ): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" action="login.php" novalidate>
            <fieldset>
                <legend>Email y Password</legend>

                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" value="<?php echo $email ?>">

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu passsword" id="passwaord" value="<?php echo $password ?>">

            </fieldset>

            <input type="submit" value="Iniciar Sesión" class=" boton boton-verde">
        </form>
    </main>

    <?php     
    incluirTemplate('footer');
?>