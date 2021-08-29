<?php

function conectarDB() : mysqli{
    $db = mysqli_connect("sql205.epizy.com", "epiz_29047024", "9KYEYZt0ilBOB","epiz_29047024_bienes_raices");

    if(!$db){
        echo "Error no se pudo conectar";
        exit;
    }

    return $db;
}
