<?php
    require '../includes/app.php';
    // Importar clases
    use App\Propiedad;
    use App\Vendedor;

    estadoAutenticado();

    //IMPLEMNTAR UN METODO PARA OBTENER TODAS LAS PROPIEDADES
    $propiedades = Propiedad::all();
    $vendedores = Vendedor::all();

    //MUESTRA MENSAJE CONDICIONAL
    $resultado = $_GET['resultado'] ?? null;

    //REVISAR EL POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        

        if($id){
            $tipo = $_POST['tipo'];
            if(validarTipoContenido($tipo)){
                if($tipo === "propiedad"){
                    $vendedor = Propiedad::find($id);
                    $vendedor->eliminar();
                }else if($tipo === "vendedor"){
                    $propiedad = Vendedor::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }

    //INCLUYE UN TEMPLATE
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php $mensaje = mostrarNotificacion (intval($resultado))?>
        <?php if($mensaje):?>
            <p class="alerta exito"><?php echo s($mensaje) ?></p>
        <?php endif;?>

        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <a href="/admin/vendedores/crear.php" class="boton boton-verde">Nuevo(a) Vendedor(a)</a>

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
                            <input type="hidden" value="propiedad" name="tipo">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad->id;?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        
        <h2>Vendedores</h2>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($vendedores as $vendedor) : ?>
                <tr> <!--//MOSTRAR LOS RESULTADOS-->
                    <td><?php echo $vendedor->id; ?></td>
                    <td><?php echo $vendedor->nombre . " " . $vendedor->apellido; ?></td>
                    <td><?php echo $vendedor->telefono; ?></td>
                    <td>
                        <form  method="POST" class="w-100">
                            <input type="hidden" value="<?php echo $vendedor->id;?>" name="id">
                            <input type="hidden" value="vendedor" name="tipo">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="admin/vendedores/actualizar.php?id=<?php echo $vendedor->id;?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
                
    <?php     

    //CERRAR LA CONEXION
    mysqli_close($db);
    incluirTemplate('footer');
?>