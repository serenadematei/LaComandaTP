<?php


class ProductoPedido
{
    public $id;
    public $codigoPedido;
    public $idProducto;
    public $idEmpleado;



    public static function crearProductoPedido($codigoPedido, $idProducto, $idEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productopedido (codigoPedido, idProducto, idEmpleado) 
        VALUES (:codigoPedido, :idProducto, :idEmpleado)");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idProducto, idEmpleado FROM productopedido"); 
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }


}
?>