<?php

    require '../../includes/app.php';

    use App\Vendedor;

    estadoAutenticado();

    //Validar que sea un ID válido
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header('Location: /admin');
    }

    //obtener el arreglo del vendedor
    $vendedor = Vendedor::find($id);
    
    //Arreglos con mensajes de erores
    $errores = Vendedor::getErrores();

    //debuguear($vendedor);
    //EJECUTAR EL CODIGO DESPUES DE QuE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Asiganr los valores 
        $args = $_POST['vendedor'];
        // Sincronizar objeto en memoria
        $vendedor->sincronizar($args);
        //validar 
        $errores = $vendedor->validar();
        
        if(empty($errores)){
            $vendedor->guardar();
        }
    }

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Vendedor(a)</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php
            foreach($errores as $error){ ?>
                <div class="alerta error">
                <?php echo $error ?>
                </div>
                <?php
            }
        ?>

        <form class="formulario" method="POST">
            
        <?php require '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Guardar Cambios" class="boton boton-verde">
        </form>
    </main>

    <?php     
    incluirTemplate('footer');
?>