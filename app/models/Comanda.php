<?php
 

class Comanda
{
    public $id;
    public $idMesa;
    public $idSocio;
    public $idMozo;
    public $codigoPedido;
    public $foto;



    public function crearComanda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comandas (idMesa, idSocio, idMozo, codigoPedido) 
        VALUES (:idMesa, :idSocio, :idMozo, :codigoPedido)");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idSocio', $this->idSocio, PDO::PARAM_INT);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
       
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idMesa, idSocio, idMozo, codigoPedido, foto FROM comandas"); 
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }

    public static function verificarExistencia($codigoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM comandas WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
        $datosAux = $consulta->fetch(PDO::FETCH_BOTH);

      if ($datosAux) {
        return true;
      } else {
        return false;
      }
    }

    public static function traerComandaPorCodigoPedido($codigoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM comandas WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject("Comanda");
    }

    public static function traerComandaPorId($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM comandas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject("Comanda");
    }

    public static function RelacionarFotoMesaConPedido($foto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE comandas SET foto = :foto WHERE id = :id');
        $consulta->bindValue(':id', self::getUltimoId(), PDO::PARAM_INT);
        $consulta->bindValue(':foto', $foto, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function getUltimoId()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT MAX(id) FROM comandas");
        $consulta->execute();
        $fila = $consulta->fetch();
        return $fila[0];
    }


  //ojo aca
    public static function cambiarEstadoPedidosAListoParaServir($codigoPedido,$tipoProducto)
    {
       
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE pedidos SET estado = "Listo para servir"
        WHERE codigoPedido = :codigoPedido AND tipoProducto = :tipoProducto AND estado = "En preparacion" ');
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoProducto', $tipoProducto, PDO::PARAM_STR);
      
        $consulta->execute();

    }
    

    
    public static function cambiarEstadoPedidosAEnPreparacion($codigoPedido, $tipoProducto)
    {
        
        $tiempoInicio = date("H:i:s");
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE pedidos SET estado = "En preparacion", tiempoInicio = :tiempoInicio
        WHERE codigoPedido = :codigoPedido AND tipoProducto = :tipoProducto AND estado = "Pendiente" ');
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoProducto', $tipoProducto, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoInicio', $tiempoInicio, PDO::PARAM_STR);
        $consulta->execute();

    }
    
    

    public static function calcularTiempoFinalizacionPedidos()
    {
      
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $pedidos = Pedido::obtenerTodos();
        $productos = Producto::obtenerTodos();

        $TAMpedidos = count($pedidos);
        $TAMproductos = count($productos);

    
          
        for($i=0 ; $i< $TAMpedidos ; $i++)
        {
            
            for($j=0; $j<$TAMproductos ; $j++)
            {
                
                if($pedidos[$i]->idProducto == $productos[$j]->id)
                {
                    
                    $tiempoFinalizacion = date('H:i:s', strtotime($pedidos[$i]->tiempoInicio . ' + ' . $productos[$j]->minutosPreparacion. ' minutes'));
                    $idProducto = $pedidos[$i]->idProducto;

                    $consulta = $objAccesoDatos->prepararConsulta('UPDATE pedidos SET tiempoFinalizacion = :tiempoFinalizacion
                    WHERE idProducto = :idProducto');
                    $consulta->bindValue(':tiempoFinalizacion', $tiempoFinalizacion, PDO::PARAM_STR);
                    $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
                  
                    $consulta->execute();
                }
            }
        }
    }

    public static function GuardarArchivo($nombreArchivo,$destino)
    {
        $retorno = "Ocurrio un error al intentar guardar el archivo";
            if (
            $_FILES['foto']['type'] == 'image/jpeg' ||
            $_FILES['foto']['type'] == 'image/jpg' ||
            $_FILES['foto']['type'] == 'image/png') 
            {
                try
                {
                    move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
                    $retorno = "Archivo guardado con exito";
                }
                catch (Exception $e)
                {
                    echo 'Excepción capturada: ', $e->getMessage(), "\n";
                }
                finally
                {
                    return $retorno;
                }
              
            }
        
    }


}
?>