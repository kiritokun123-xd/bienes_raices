<?php
    require '../includes/app.php';
    use App\Propiedad;
    use App\Vendedor;

    estadoAutenticado();

    //IMPLEMNTAR UN METODO PARA OBTENER TODAS LAS PROPIEDADES
    $propiedades = Propiedad::all();

    //MUESTRA MENSAJE CONDICIONAL
    $resultado = $_GET['resultado'] ?? null;

    //REVISAR EL POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        

        if($id){
            $propiedad = Propiedad::find($id);

            $propiedad->eliminar();
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

        <h2>Propiedades</h2>
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
                <?php foreach($propiedades as $propiedad) : ?>
                <tr> <!--//MOSTRAR LOS RESULTADOS-->
                    <td><?php echo $propiedad->id; ?></td>
                    <td><?php echo $propiedad->titulo; ?></td>
                    <td><img src="/imagenes/<?php echo $propiedad->imagen; ?>" class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad->precio; ?></td>
                    <td>
                        <form  method="POST" class="w-100">
                            <input type="hidden" value="<?php echo $propiedad->id;?>" name="id">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad->id;?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Vendedores</h2>
    </main>
                
    <?php     

    //CERRAR LA CONEXION
    mysqli_close($db);
    incluirTemplate('footer');
?>