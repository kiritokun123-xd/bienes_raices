<?php

use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

require '../../includes/app.php';
    estadoAutenticado();

    //VALIDAR LA URL POR ID VALIDO
    $id = $_GET['id'];
    $id =filter_var($id, FILTER_VALIDATE_INT);
    

    if(!$id){
        header('Location: /admin');
    }

    //Obtener datos de la propiedad
    $propiedad = Propiedad::find($id);
    
    //Consulta para obtener todos los vendedores
    $vendedores = Vendedor::all(); 


    //ARREGLO CON MENSAJES DE ERRORES
    $errores = Propiedad::getErrores();

    
    //EJECUTAR EL CODIGO DESPUES DE QuE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Asignar los atributos
        $args = $_POST['propiedad'];
        
        $propiedad->sincronizar($args);
        
        $errores = $propiedad->validar();
        
        //Validación subida de archivos
        //GENERAR UN NOMBRE UNICO
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        
        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        //REVISAR QUE EL AAREGLO DE ERRORES ESTE VACIO
        if(empty($errores)){
            if($_FILES['propiedad']['tmp_name']['imagen']){
                //GUARDA LA IMAGEN EN EL SERVIDOR
                $image->save(CARPETA_IMAGENES . $nombreImagen);
            }
            
            $propiedad->guardar();
        }

    }
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php
            foreach($errores as $error){ ?>
                <div class="alerta error">
                <?php echo $error ?>
                </div>
                <?php
            }
        ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <?php require '../../includes/templates/formulario_propiedades.php' ?>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>

    <?php     
    incluirTemplate('footer');
?>