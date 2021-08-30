<?php

namespace App;

class Propiedad{
    //BASE DE DATOS
    private static $db;
    private static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    //ERRORES
    protected static $errores = [];

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
        $this->imagen = $args['imagen'] ?? '';
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
        return $resultado;
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

    //SUBIDA DE ARCHIVOS
    public function setImagen($imagen){
        //asignar al atributo el nombre de la imagen
        if($imagen){
            $this->imagen = $imagen;
        }
    }

    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key=>$value){
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
    //VALIDACION
    public static function getErrores(){
        return self::$errores;  
    }

    public function validar(){
        if(!$this->titulo){
            self::$errores[] = "Debes añadir un título";
        }
        if(!$this->precio){
            self::$errores[] = "El precio es obligatorio";
        }
        if(strlen($this->descripcion)<50){
            self::$errores[] = "La descripcion es obligatorio y debe tener al menos 50 caracteres";
        }
        if(!$this->habitaciones){
            self::$errores[] = "El número de habitaciones es obligatorio";
        }
        if(!$this->wc){
            self::$errores[] = "El número de baños es obligatorio";
        }
        if(!$this->estacionamiento){
            self::$errores[] = "El número de estacionamiento es obligatorio";
        }
        if(!$this->vendedorId){
            self::$errores[] = "Elige un vendedor";
        }

        if(!$this->imagen){
            self::$errores[] = "La imagen es obligatoria";
        }
        // //VALIDAR POR TAMAÑO (100KB MAXIMO)
        // $medida = 1000 * 10000;
        // if($this->imagen['size'] > $medida){
        //     self::$errores[] = "La imagen es muy pesada, maximo 100Kb";
        // }

        return self::$errores;
    }

}