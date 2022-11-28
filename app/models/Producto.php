<?php

class Producto
{
    public $id;
    public $descripcion;
    public $minutosPreparacion;
    public $precio;
    public $tipoProducto;


    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (descripcion,minutosPreparacion,precio,tipoProducto) 
        VALUES (:descripcion, :minutosPreparacion, :precio, :tipoProducto)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':minutosPreparacion', $this->minutosPreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tipoProducto', $this->tipoProducto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, minutosPreparacion, precio,
        tipoProducto FROM productos"); 
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function traerProductoPorId($idProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Producto');

    }

    public static function VerificarId($id)
    {
      $objAccesoDato = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM productos WHERE id = :id");
      $consulta->bindValue(':id', $id, PDO::PARAM_INT);
      $consulta->execute();
      $datosAux = $consulta->fetch(PDO::FETCH_BOTH);
      if ($datosAux != false) {
        return true;
      } else {
        return false;
      }
    }

    public static function obtenerProductosDePedidosPorTipoEmpleado($perfil, $estadoPedido, $codigoPedido)
    {
        try {
         
          
          $tipoProducto=null;
          if($perfil=="bartender")
          {
            $tipoProducto = "Bebida";
          }
          if($perfil=="cocinero")
          {
            $tipoProducto = "Comida";
          }
          
          if($perfil == "cervecero")
          {
            $tipoProducto= "Cerveza";
          }
          

            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT pro.id, pro.descripcion, pro.tipoProducto from productos as pro
             INNER JOIN pedidos as pe ON pe.idProducto = pro.id
             WHERE pe.estado = :estadoPedido AND pro.tipoProducto = :tipoProducto AND pe.codigoPedido = :codigoPedido");
            $consulta->bindValue(':estadoPedido', $estadoPedido, PDO::PARAM_STR);
            $consulta->bindValue(':tipoProducto',  $tipoProducto, PDO::PARAM_STR);
            $consulta->bindValue(':codigoPedido',  $codigoPedido, PDO::PARAM_STR);

            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
    }




    public static function traerTipoProductoPorPerfilEmpleado($perfilEmpleado)
    {

       $retorno = 0;
       switch($perfilEmpleado)
       {
         case 'bartender':
          $retorno = "Bebida";
          break;
         case 'cocinero':
          $retorno = "Comida";
            break;
         case 'cervecero':
          $retorno = "Cerveza";
            break;
       }

       return $retorno;
    }
   
    public static function traerTipoPorId($idProducto)
    {
       $producto = Producto::traerProductoPorId($idProducto);

       return $producto->tipoProducto;
    }



    public static function getMayorMinutosDePreparacion($codigoPedido,$idMesa)
    {
        
        $pedidos = Pedido::obtenerTodos();
        $productos = Producto::obtenerTodos();
        $nuevoArrayProductos = array();
       
        for($i=0; $i<count($pedidos); $i++)
        {
           for($j=0; $j<count($productos) ; $j++)
           {
              if($pedidos[$i]->codigoPedido == $codigoPedido && $pedidos[$i]->idMesa== $idMesa &&
              $pedidos[$i]->idProducto == $productos[$j]->id )
              {
                  array_push($nuevoArrayProductos,$productos[$j]);
              }
           }
        }

        $actualMayor= NULL;
        foreach($nuevoArrayProductos as $p)
       {
          if($p->minutosPreparacion >= $actualMayor)
          {
            
              $actualMayor= $p->minutosPreparacion;
            
          }
       }

        return $actualMayor;
    }

    public function modificarProducto()
    {

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos 
        SET descripcion=:descripcion,
         minutosPreparacion=:minutosPreparacion,
         precio=:precio, 
         tipoProducto=:tipoProducto WHERE id = :id");

        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':minutosPreparacion', $this->minutosPreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tipoProducto', $this->tipoProducto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $consulta->execute();
    }

    
    public static function borrarProducto($id)
    {
      $objAccesoDato = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDato->prepararConsulta("DELETE FROM productos WHERE id = :id");
      $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    
      return $consulta->execute();
    }

    public static function ReadCsv($filename){
      $file = fopen($filename, "r");
      $array = array();
      self::deleteTable();
      try {
          if (!is_null($file)){
          }
          while (!feof($file) ) {
              $line = fgets($file);
              
              if (!empty($line)) {
                
                  $line = str_replace(PHP_EOL, "", $line);
                  $productosArray = explode(",", $line);
                  $p = Producto::crearProductoParametrizado($productosArray[0], $productosArray[1], 
                  $productosArray[2], $productosArray[3], $productosArray[4]);
                 
                  array_push($array, $p);
                  
                  Producto::insertarProductosEnTabla($p);
                  
              }
          }
      } catch (\Throwable $th) {
          echo "Error while reading the file";
      }finally{
          fclose($file);
          return $array;
      }
  }

  public static function crearProductoParametrizado($id, $descripcion, $minutosPreparacion, $precio, $tipoProducto){

    $producto = new Producto();
    $producto->id = $id;
    $producto->descripcion = $descripcion;
    $producto->minutosPreparacion = $minutosPreparacion;
    $producto->precio = $precio;
    $producto->tipoProducto = $tipoProducto;
    
    return $producto;
  }

  public static function insertarProductosEnTabla($p){
    $objDataAccess = AccesoDatos::obtenerInstancia();
    $query = $objDataAccess->prepararConsulta("INSERT INTO `productosLeidos` (id, descripcion, minutosPreparacion, precio, tipoProducto) 
    VALUES (:id, :descripcion, :minutosPreparacion, :precio, :tipoProducto);");
    $query->bindValue(':id', $p->id, PDO::PARAM_INT);
    $query->bindValue(':descripcion', $p->descripcion, PDO::PARAM_STR);
    $query->bindValue(':minutosPreparacion', $p->minutosPreparacion, PDO::PARAM_INT);
    $query->bindValue(':precio', $p->precio, PDO::PARAM_INT);
    $query->bindValue(':tipoProducto', $p->tipoProducto, PDO::PARAM_STR);
    $query->execute();

    return $objDataAccess->obtenerUltimoId();
}
public static function deleteTable(){
  $objDataAccess = AccesoDatos::obtenerInstancia();
  $query = $objDataAccess->prepararConsulta("DELETE FROM `productosLeidos` WHERE 1=1");
  $query->execute();

  return $query->rowCount() > 0;
}

}
?>