<?php
    require 'includes/app.php';
    incluirTemplate('header');
?>


    <main class="contenedor seccion">
        <h1>Conoce Sobre Nosotros</h1>

        <div class="contenido-nosotros">
            <div class="imagen">
                <picture>
                    <source srcset="build/img/nosotros.webp" type="image/webp">
                    <source srcset="build/img/nosotros.jpg" type="image/jpeg">
                    <img loading="lazy" src="build/img/nosotros.jpg" alt="Sobre Nosotros">
                </picture>
            </div>

            <div class="texto-nosotros">
                <blockquote>25 Años de Experiencia</blockquote>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum, dignissimos ab quod iure iste sint. Sunt, dignissimos qui et inventore modi dicta soluta eum ut quos provident adipisci illo similique?</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est, dolorum blanditiis corporis odit mollitia esse nulla quisquam eaque deserunt rem? Ratione placeat asperiores amet beatae nobis quae autem similique quas!</p>
            </div>
        </div>
    </main>

    <section class="contenedor seccion">
        <h1>Más Sobre Nosotros</h1>
        <div class="iconos-nosotros">
            <div class="icono">
                <img src="build/img/icono1.svg" alt="Icono Seguridad" loading="lazy">
                <h3>Seguridad</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quae non alias nostrum nesciunt explicabo excepturi incidunt unde similique? Necessitatibus at repellendus blanditiis animi minima aspernatur doloremque? Vel, quisquam iure. In!</p>
            </div>
            <div class="icono">
                <img src="build/img/icono2.svg" alt="Icono Precio" loading="lazy">
                <h3>Precio</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quae non alias nostrum nesciunt explicabo excepturi incidunt unde similique? Necessitatibus at repellendus blanditiis animi minima aspernatur doloremque? Vel, quisquam iure. In!</p>
            </div>
            <div class="icono">
                <img src="build/img/icono3.svg" alt="Icono Tiempo" loading="lazy">
                <h3>Tiempo</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quae non alias nostrum nesciunt explicabo excepturi incidunt unde similique? Necessitatibus at repellendus blanditiis animi minima aspernatur doloremque? Vel, quisquam iure. In!</p>
            </div>
        </div>
    </section>

    <?php     
    incluirTemplate('footer');
?>