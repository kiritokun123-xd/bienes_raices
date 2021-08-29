<?php
    require '../includes/funciones.php';
    $autenticado = estadoAutenticado();

    if(!$autenticado){
        header('Location: /');
    }

    //IMPORTAR LA CONECCION
    require '../includes/config/database.php';
    $db = conectarDB();

    //ESCRIBIR EL QUERY
    $query = "SELECT * FROM propiedades";

    //CONSULTAR LA BD
    $resultadoConsulta = mysqli_query($db, $query);

    

    //MUESTRA MENSAJE CONDICIONAL
    $resultado = $_GET['resultado'] ?? null;

    //REVISAR EL POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        

        if($id){
            //elimnar archivo
            $query = "SELECT imagen FROM propiedades WHERE id = ${id}";

            $resultado = mysqli_query($db, $query);
            $propiedad = mysqli_fetch_assoc($resultado);

            unlink('../imagenes/' . $propiedad['imagen']);
            //ELIMINAR LA PROPIEDAD 
            $query = "DELETE FROM propiedades WHERE id = ${id}";
            $resultado = mysqli_query($db, $query);

            if($resultado){
                header('Location: /admin?resultado=3');
            }
        }
    }

    //INCLUYE UN TEMPLATE
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if($resultado == 1):?>
            <p class="alerta exito">Anuncio Cargado Correctamente</p>
        <?php elseif($resultado == 2):?>
            <p class="alerta actualizado">Anuncio Actualizado Correctamente</p>
        <?php elseif($resultado == 3):?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php endif; ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while( $propiedad = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                <tr> <!--//MOSTRAR LOS RESULTADOS-->
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad['precio']; ?></td>
                    <td>
                        <form  method="POST" class="w-100">
                            <input type="hidden" value="<?php echo $propiedad['id'];?>" name="id">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad['id'];?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
                
    <?php     

    //CERRAR LA CONEXION
    mysqli_close($db);
    incluirTemplate('footer');
?>