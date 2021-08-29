<?php

namespace App;

class Propiedad{
    //BASE DE DATOS
    private static $db;
    private static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    //DEFINIR CONEXION A LA BD
    public static function setDB($database){
        Self::$db = $database;
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? 'imagen.jpg';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y,m,d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function guardar(){

        //SANITIZAR LOS DATOS
        $atributos = $this->sanitizarAtributos();

        $string = join(', ', array_keys($atributos));

        //INSERTAR EN LA BASE DE DATOS
        $query = "INSERT INTO propiedades ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '",array_values($atributos));
        $query .= " ')";
        
        $resultado  = self::$db->query($query);

        debuguear($resultado);
        
    }

    //IDENTIFICAR Y UNIR LOS ATRIBUTOS DE LA BD
    public function atributos(){
        $atributos = [];
        foreach(self::$columnasDB as $columna){
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key=>$value){
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

}