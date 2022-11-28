

<?php
require_once './models/ProductoPedido.php';

class Pedido
{
    public $id;
    public $estado;
    public $nombreCliente;
    public $idMozo;
    public $idSocio;
    public $idEmpleado;
    public $idProducto;
    public $idMesa;
    public $codigoPedido;
    public $tipoProducto;

    public $tiempoInicio;
    public $tiempoFinalizacion;
  
    

    
   



    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos 
        (estado, nombreCliente, idMozo, idSocio, idEmpleado, idProducto, idMesa, codigoPedido, tipoProducto) 
        VALUES (:estado, :nombreCliente, :idMozo, :idSocio, :idEmpleado, :idProducto, :idMesa, :codigoPedido, :tipoProducto)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':idSocio', $this->idSocio, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoProducto', $this->tipoProducto, PDO::PARAM_STR);
    
        $consulta->execute();

        

        
        

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
       $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, nombreCliente, idMozo, idSocio, idEmpleado, idProducto, idMesa, 
        codigoPedido FROM pedidos"); 
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    
    }
    public static function obtenerTodosPorEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE estado = :estado "); 
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    
    }

    public static function obtenerTodosPorCodigo($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE codigoPedido = :codigo "); 
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    
    }

    public static function obtenerTodosPorIdMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idMesa = :idMesa "); 
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    
    }

   


}
?>