<?php
    require '../../includes/app.php';

    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;

    estadoAutenticado();

    $db = conectarDB();

    //CONSULTAR PARA OBTENER LOS VENDEDORES
    $consulta = "SELECT*FROM vendedores";
    $resultado = mysqli_query($db, $consulta);


    //ARREGLO CON MENSAJES DE ERRORES
    $errores = Propiedad::getErrores();

    $titulo = "";
    $precio = "";
    $descripcion = "";
    $habitaciones = "";
    $wc = "";
    $estacionamiento = "";
    $vendedorId = "";
    
    //EJECUTAR EL CODIGO DESPUES DE QuE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        /*CREA UNA NUEVA INSTANCIA*/
        $propiedad = new Propiedad($_POST);

        //GENERAR UN NOMBRE UNICO
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        //SETEAR LA IMAGEN
        //realiza un RESIZE A LA IMAGEN CON INTERVENTION
        if($_FILES['imagen']['tmp_name']){
            $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800,600);
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
            $resultado = $propiedad->guardar();

            if($resultado){
                //REDIRECIONAR AL USUARIO
                header('Location: /admin?resultado=1');
            }
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
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Titulo: </label>
                <input type="text" id="titulo" name="titulo"  placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

                <label for="precio">Precio: </label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

                <label for="imagen">Imagen: </label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

                <label for="descripcion">Descripcion: </label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información Propiedad</legend>

                <label for="habitaciones">Habitaciones: </label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ejemplo: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

                <label for="wc">Baños: </label>
                <input type="number" id="wc" name="wc" placeholder="Ejemplo: 3" min="1" max="9" value="<?php echo $wc; ?>">

                <label for="estacionamiento">Estacionamiento: </label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ejemplo: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedorId">
                    <option value="">-- Seleccione --</option>
                    <?php while($row = mysqli_fetch_assoc($resultado)) : ?>
                        <option <?php echo $vendedorId == $row['id'] ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"> <?php echo $row['nombre'] . " " . $row['apellido']; ?> </option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>

    <?php     
    incluirTemplate('footer');
?>