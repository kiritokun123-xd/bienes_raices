<?php

    require '../../includes/app.php';

    use App\Vendedor;

    estadoAutenticado();

    $vendedor = new Vendedor();

    //Arreglos con mensajes de erores
    $errores = Vendedor::getErrores();

    //EJECUTAR EL CODIGO DESPUES DE QuE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $vendedor = new Vendedor($_POST['vendedor']);

        //validar que no haya campos vacios
        $errores =  $vendedor->validar();

        //No hay errores
        if(empty($errores)){
            //Sube BD
            $vendedor->guardar();
        }
    }

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Regisgtrar Vendedor</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php
            foreach($errores as $error){ ?>
                <div class="alerta error">
                <?php echo $error ?>
                </div>
                <?php
            }
        ?>

        <form class="formulario" method="POST" action="/admin/vendedores/crear.php">
            
        <?php require '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Registrar Vendedor" class="boton boton-verde">
        </form>
    </main>

    <?php     
    incluirTemplate('footer');
?>