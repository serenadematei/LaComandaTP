<?php


class Usuario
{
    public $id;
    public $perfil; 
    public $usuario; 
    public $clave;


    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (perfil, usuario, clave) VALUES (:perfil, :usuario, :clave)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, perfil, usuario, clave FROM usuarios"); 
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }


    

    public static function obtenerUsuario($usuario)
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, perfil, usuario, clave FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
        
    }

    public static function designarSocio()
    {
        $socios = Usuario::traerSocios();
        $count = count($socios);
        $random = rand(0, $count - 1);
        $socioDesignado = $socios[$random];

        return $socioDesignado->id;
    }

    public static function designarMozo()
    {
        $mozos = Usuario::traerMozos();

        $count = count($mozos);
        $random = rand(0, $count - 1);
        $mozoDesignado = $mozos[$random];

        return $mozoDesignado->id;
    }

    public static function designarEmpleado($idProducto)
    {
        $producto = Producto::traerProductoPorId($idProducto);
       

        switch($producto->tipoProducto)
        {
            case "Bebida":
                $bartenders = Usuario::traerBartenders();
                $count = count($bartenders);
                $random = rand(0, $count - 1);
                $bartenderDesignado = $bartenders[$random];

                return $bartenderDesignado->id;
                break;

            case "Comida":
                $cocineros = Usuario::traerCocineros();
                $count = count($cocineros);
                $random = rand(0, $count - 1);
                $cocineroDesignado= $cocineros[$random];

                return $cocineroDesignado->id;
                break;

            case "Cerveza":
                $cerveceros = Usuario::traerCerveceros();
                $count = count($cerveceros);
                $random = rand(0, $count - 1);
                $cerveceroDesignado = $cerveceros[$random];

                return $cerveceroDesignado->id;
                break;
        }
    }
    
    public static function traerSocios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE perfil = 'socio' ");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function traerMozos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE perfil = 'mozo' ");
        $consulta->execute();
     
       return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function traerBartenders()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE perfil = 'bartender'     ");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function traerCocineros()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE perfil = 'cocinero' ");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function traerCerveceros()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE perfil = 'cervecero' ");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }


    
    public static function obtenerUsuarioId($id)
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
        
    }


    
    
    public function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario= :usuario, clave = :clave , perfil=:perfil WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuarios, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    
    public static function borrarUsuario($usuario)
    {
        return 0;
    }
    
     

    public static function verificarId($id)
    {
      $objAccesoDato = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM usuarios WHERE id = :id");
      $consulta->bindValue(':id', $id, PDO::PARAM_INT);
      $consulta->execute();
      $datosAux = $consulta->fetch(PDO::FETCH_BOTH);
      if ($datosAux) {
        return true;
      } else {
        return false;
      }
    }
 
    public static function verificarDatos($usuario, $perfil, $clave) : int
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, perfil, usuario, clave FROM usuarios  WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $mail, PDO::PARAM_STR);
        $consulta->execute();
        $retorno = 0;
        $userDataBase = $consulta->fetchObject('Usuario'); 

        if($userDataBase != null){
            if($userDataBase->mail == $mail){
                
                if(password_verify($clave,$userDataBase->clave) ||  $userDataBase->clave == $clave){
                    
                    $retorno = 1;
                }else{
                    var_dump($clave);
                    var_dump($userDataBase->clave);
                    $retorno = 2;
                }
            }
        }
        return $retorno;

    }


}