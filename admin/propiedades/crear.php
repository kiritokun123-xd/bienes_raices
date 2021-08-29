<?php
    require '../../includes/app.php';

    use App\Propiedad;

    estadoAutenticado();

    $db = conectarDB();

    //CONSULTAR PARA OBTENER LOS VENDEDORES
    $consulta = "SELECT*FROM vendedores";
    $resultado = mysqli_query($db, $consulta);


    //ARREGLO CON MENSAJES DE ERRORES
    $errores = [];

    $titulo = "";
    $precio = "";
    $descripcion = "";
    $habitaciones = "";
    $wc = "";
    $estacionamiento = "";
    $vendedorId = "";
    
    //EJECUTAR EL CODIGO DESPUES DE QuE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $propiedad = new Propiedad($_POST);

        $propiedad->guardar();
        
        //ASIGANR FILES HACIA UNA VARIABLE
        $imagen = $_FILES['imagen'];
        var_dump($imagen['name']);
        

        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $precio = mysqli_real_escape_string($db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
        $wc = mysqli_real_escape_string($db, $_POST['wc']);
        $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
        $vendedorId = mysqli_real_escape_string($db, $_POST['vendedor']);
        $creado = date('Y/m/d');

        if(!$titulo){
            $errores[] = "Debes añadir un título";
        }
        if(!$precio){
            $errores[] = "El precio es obligatorio";
        }
        if(strlen($descripcion)<50){
            $errores[] = "La descripcion es obligatorio y debe tener al menos 50 caracteres";
        }
        if(!$habitaciones){
            $errores[] = "El número de habitaciones es obligatorio";
        }
        if(!$wc){
            $errores[] = "El número de baños es obligatorio";
        }
        if(!$estacionamiento){
            $errores[] = "El número de estacionamiento es obligatorio";
        }
        if(!$vendedorId){
            $errores[] = "Elige un vendedor";
        }
        if(!$imagen['name'] || $imagen['error']){
            $errores[] = "La imagen es obligatoria";
        }
        //VALIDAR POR TAMAÑO (100KB MAXIMO)
        $medida = 1000 * 10000;
        if($imagen['size'] > $medida){
            $errores[] = "La imagen es muy pesada, maximo 100Kb";
        }

        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";

        //REVISAR QUE EL AAREGLO DE ERRORES ESTE VACIO
        if(empty($errores)){
            //**SUBIDA DE ARCHIVOS */

            //CREAR UNA CARPETA
            $carpetaImagenes = "../../imagenes/";

            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            //GENERAR UN NOMBRE UNICO
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            //SUBIR LA IMAGEN

            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
            


            

            //echo $query;

            $resultado = mysqli_query($db, $query);

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