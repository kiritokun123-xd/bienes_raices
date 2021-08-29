<?php
    require 'includes/funciones.php';
    incluirTemplate('header');
?>


    <main class="contenedor seccion contenido-centrado">
        <h1>Guia para la Decoración de tu Hogar</h1>


        <picture>
            <source srcset="build/img/destacada2.webp" type="image/webp"> 
            <source srcset="build/img/destacada2.jpg" type="image/jpeg">
            <img loading="lazy" src="build/img/destacada2.jpg" alt="imagen de la propiedad"> 
        </picture>

        <p class="informacion-meta">Escrito el: <span>20/10/2021</span> por: <span>Admin</span></p>

        <div class="resumen-propiedad">
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Cupiditate eos accusamus quasi alias delectus doloremque beatae sapiente fugiat eveniet officiis. Doloremque modi praesentium amet debitis iure commodi, cum nostrum. Debitis? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos provident quam neque, laudantium voluptas optio ipsa corrupti, ullam consequuntur eos esse ex rem fugit ducimus, praesentium non voluptate facilis cupiditate!</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia natus deleniti ullam. Voluptatem consequuntur natus dolor assumenda autem amet! Fuga nisi cupiditate inventore ratione corporis placeat magni vel voluptatibus fugit.</p>
        </div>
    </main>

    <?php     
    incluirTemplate('footer');
?>