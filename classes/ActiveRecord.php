<?php

namespace App;

class ActiveRecord{
    //BASE DE DATOS
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //ERRORES
    protected static $errores = [];


    //DEFINIR CONEXION A LA BD
    public static function setDB($database){
        Self::$db = $database;
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
        foreach(static::$columnasDB as $columna){
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
        return static::$errores;  
    }

    public function validar(){
        static::$errores = [];
        return static::$errores;
    }

    //Lista todos los registros
    public static function all(){
        //ESCRIBIR EL QUERY
        $query = "SELECT * FROM " . static::$tabla;
        
        $resultado = self::constularSQL($query);

        return $resultado;
    }
    
    // Obtiene determinado número de registros
    public static function get($cantidad){
        //ESCRIBIR EL QUERY
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        
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
            $array[] = static::crearObejto($registro);
        }
        
        //Liberar memoria
        $resultado->free();

        //retornar los resultados
        return $array;
    }

    protected static function crearObejto($registro){
        $objeto = new static;
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