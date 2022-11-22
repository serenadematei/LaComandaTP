<?php
require_once 'Comanda.php';

class GestorDeArchivos
{
    private $ruta;

   

    public function __construct($ruta)
    {
        if (!file_exists($ruta)) {
            mkdir($ruta, 077, true);
        }
        $this->ruta = $ruta;
    }
 
  
    public function getRuta()
    {
        return $this->ruta;
    }

    
    public function setRuta($nuevaRuta)
    {
        $this->ruta = $nuevaRuta;
    }

    public function concatenarRuta($nombreArchivo)
    {
        return $this->getRuta() . $nombreArchivo;
    }
   
    public static function MoverImagen($origen, $destino, $nombreArchivo)
    {
       if (!file_exists($destino))
          mkdir($destino, 0777, true);
 
      var_dump($origen . $nombreArchivo);
      var_dump($destino . $nombreArchivo);
       return rename($origen . $nombreArchivo, $destino . $nombreArchivo);
    }

    
    public function moveImagen($pathFrom, $pathTo, $nombreArchivo)
    {
        if (isset($pathFrom) && isset($pathTo) && isset($nombreArchivo)) {
            $files = scandir($pathFrom);
            foreach ($files as $file) {
                if (strlen($file) > 2) {
                    if (
                        $file == $nombreArchivo
                        &&
                        file_exists($pathFrom . "/" . $file)
                    )
                    {
                        rename($pathFrom . "/" . $file, $pathTo ."/" . $file);
                        return true;

                    }
                }
            }
        }
        return false;
    }
    


    


    public function GuardarArchivoMesa($nombreArchivo)
    {
        $retorno = "Ocurrio un error al intentar guardar el archivo";
            if (
            $_FILES['foto']['type'] == 'image/jpeg' ||
            $_FILES['foto']['type'] == 'image/jpg' ||
            $_FILES['foto']['type'] == 'image/png') 
            {
                try
                {
                    move_uploaded_file($_FILES['foto']['tmp_name'], $this->concatenarRuta($nombreArchivo));
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



    public function ArchivoExistente($pathFrom, $nombreFoto)
    {
        if(isset($pathFrom) && isset($nombreFoto))
        {
            
            $files = scandir($pathFrom);
            foreach($files as $f)
            {
                if(strlen($f)>2) 
                {
                    if(file_exists($pathFrom . "/" . $f))
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    

}
