<?php
    require '../../includes/app.php';

    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;

    estadoAutenticado();

    $db = conectarDB();

    $propiedad = new Propiedad();

    //CONSULTAR PARA OBTENER LOS VENDEDORES
    $consulta = "SELECT*FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    //ARREGLO CON MENSAJES DE ERRORES
    $errores = Propiedad::getErrores();
    
    //EJECUTAR EL CODIGO DESPUES DE QuE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        /*CREA UNA NUEVA INSTANCIA*/
        $propiedad = new Propiedad($_POST['propiedad']);

        //GENERAR UN NOMBRE UNICO
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        //SETEAR LA IMAGEN
        //realiza un RESIZE A LA IMAGEN CON INTERVENTION
        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        /*VALIDAR*/
        $errores = $propiedad->validar();

        //REVISAR QUE EL ARREGLO DE ERRORES ESTE VACIO
        if(empty($errores)){
            //crear la carpeta imagenes
            if(!is_dir(CARPETA_IMAGENES)){
                mkdir(CARPETA_IMAGENES);
            }

            //GUARDA LA IMAGEN EN EL SERVIDOR
            $image->save(CARPETA_IMAGENES . $nombreImagen);

            //SUBE A LA BD
            $propiedad->guardar();
        }

    }

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php
            foreach($errores as $error){ ?>
                <div class="alerta error">
                <?php echo $error ?>
                </div>
                <?php
            }
        ?>

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            
        <?php require '../../includes/templates/formulario_propiedades.php' ?>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>

    <?php     
    incluirTemplate('footer');
?>