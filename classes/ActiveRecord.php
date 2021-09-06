<?php

namespace App;

class ActiveRecord{
    //BASE DE DATOS
    protected static $db;
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];
    protected static $tabla = '';

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
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y,m,d');
        $this->vendedorId = $args['vendedorId'] ?? 1;
    }
    public function guardar(){
        //debuguear($this);
        if(!is_null($this->id)){
            //Actualizar
            //debuguear("actualizando...");
            $this->actualizar();
        }else{
            //Creando un nuevo registo
            //debuguear("Creando...."); 
            $this->crear();
        }
    }
    public function actualizar(){
        
        //SANITIZAR LOS DATOS
        $atributos = $this->sanitizarAtributos();

        $valores = [];

        foreach($atributos as $key=>$value){
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = " . self::$db->escape_string($this->id);
        $query .= " LIMIT 1 ";
        //debuguear($query);
        $resultado = self::$db->query($query);
        
        if($resultado){
            //REDIRECIONAR AL USUARIO
            header('Location: /admin?resultado=2');
        }
    }
    public function crear(){
              
        //SANITIZAR LOS DATOS
        $atributos = $this->sanitizarAtributos();
        
        //INSERTAR EN LA BASE DE DATOS
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '",array_values($atributos));
        $query .= " ')";
        
        $resultado  = self::$db->query($query);  
        //
        if($resultado){
            //REDIRECIONAR AL USUARIO
            header('Location: /admin?resultado=1');
        }
    }

    //Eliminar un registro
    public function eliminar(){
        //ELIMINAR el registro 
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado){
            $this->borrarImagen();
            header('Location: /admin?resultado=3');
        }
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
        //Elimina la imagen previa
        if(!is_null($this->id)){
            $this->borrarImagen();
        }
        //asignar al atributo el nombre de la imagen
        if($imagen){
            $this->imagen = $imagen;
        }
    }
    //Elimina el archivo
    public function borrarImagen(){
        //comprobar is existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo){
            unlink(CARPETA_IMAGENES . $this->imagen);
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

    //Lista todos los registros
        public static function all(){
        //ESCRIBIR EL QUERY
        $query = "SELECT * FROM " . static::$tabla;
        
        $resultado = self::constularSQL($query);

        return $resultado;
    }

    //Busca un registro por su id
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";

        $resultado = self::constularSQL($query);

        return array_shift($resultado);
    }

    public static function constularSQL($query){
        //consultar la bd
        $resultado = self::$db->query($query);

        //Iterar sobre los resultados
        $array = [];

        while($registro = $resultado->fetch_assoc()){
            $array[] = self::crearObejto($registro);
        }
        
        //Liberar memoria
        $resultado->free();

        //retornar los resultados
        return $array;
    }

    protected static function crearObejto($registro){
        $objeto = new self;
        foreach($registro as $key=>$value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    //Sincroniza el objeto en memoria con los cambios realizads por el usuario
    public function sincronizar($args = []){
        foreach($args as $key => $value){
            if(property_exists($this, $key) && !is_null($value)){
                $this->$key=$value;
            }
        }
    }
}