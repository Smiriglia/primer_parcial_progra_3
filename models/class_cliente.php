<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    class Cliente
    {
        public $nro_cliente;
        public $nombre;
        public $tipoDocumento;
        public $numeroDocumento;
        public $email;
        public $tipoCliente;
        public $pais;
        public $ciudad;
        public $telefono;

        public function setTipoCliente($tipo)
        {
            $tipo = strtolower($tipo);
            if ($tipo === "individual" or $tipo === "corporativo")
            {
                $this->tipoCliente = $tipo;
                return true;
            }
            return false;
        }
        public function MostrarDatos()
        {
            return "Pais: " . $this->pais . "  Ciudad: " . $this->ciudad . "  Telefono: " . $this->telefono;
        }

        public function Insertar($rutaArchivo = "./datos/hoteles.json")
        {
            $accesoUltimoNroCliente = new ManejadorArchivos("./datos/ultimo_nro_cliente.json");
            $objetoUltimoNroCliente = $accesoUltimoNroCliente->leer();
            
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $clientes = $objetoAccesoDato->leer();
            $flagEncontrado = false;

            for ($i = 0; $i < count($clientes); $i++) {
                if ($clientes[$i]["nombre"] == $this->nombre and $clientes[$i]["tipoCliente"] == $this->tipoCliente)
                {
                    $this->nro_cliente = $clientes[$i]["nro_cliente"];
                    $clientes[$i] = $this;
                    $flagEncontrado = true;
                    break;
                }
            }
            if (!$flagEncontrado)
            {
                $objetoUltimoNroCliente["nro_cliente"] += 1;
                $this->nro_cliente = str_pad($objetoUltimoNroCliente["nro_cliente"], 6, '0', STR_PAD_LEFT);;
                $clientes[] = $this;
            }

            $objetoAccesoDato->guardar($clientes);
            $accesoUltimoNroCliente->guardar($objetoUltimoNroCliente);
            
            return $this->nro_cliente;
        }

        public static function TraerTodo($rutaArchivo = "./datos/hoteles.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $clientesNoParseados = $objetoAccesoDato->leer();
            $clientes = [];
            foreach ($clientesNoParseados as $clienteNoParseado) 
            {
                $cliente = new Cliente();
                $cliente->nro_cliente = $clienteNoParseado["nro_cliente"];
                $cliente->nombre = $clienteNoParseado["nombre"];
                $cliente->tipoDocumento = $clienteNoParseado["tipoDocumento"];
                $cliente->numeroDocumento = $clienteNoParseado["numeroDocumento"];
                $cliente->email = $clienteNoParseado["email"];
                $cliente->setTipoCliente($clienteNoParseado["tipoCliente"]);
                $cliente->pais = $clienteNoParseado["pais"];
                $cliente->ciudad = $clienteNoParseado["ciudad"];
                $cliente->telefono = $clienteNoParseado["telefono"];
                $clientes[] = $cliente;
            }
            return $clientes;
        }

        public static function TraerUnCliente($nro_cliente)
        {
            $clientes = Cliente::TraerTodo();
            foreach ($clientes as $cliente) 
            {
                if ($cliente->nro_cliente === $nro_cliente)
                {
                    return $cliente;
                }
            }
        }

    //     public static function TraerUnCdAnio($id, $anio)
    //     {
    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=? AND jahr=?");
    //         $consulta->execute(array($id, $anio));
    //         $cdBuscado = $consulta->fetchObject('cd');
    //         return $cdBuscado;
    //     }

    //     public static function TraerUnCdAnioParamNombre($id, $anio)
    //     {
    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=:id AND jahr=:anio");
    //         $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    //         $consulta->bindValue(':anio', $anio, PDO::PARAM_STR);
    //         $consulta->execute();
    //         $cdBuscado = $consulta->fetchObject('cd');
    //         return $cdBuscado;
    //     }

    //     public static function TraerUnCdAnioParamNombreArray($id, $anio)
    //     {
    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=:id AND jahr=:anio");
    //         $consulta->execute(array(':id' => $id, ':anio' => $anio));
    //         $consulta->execute();
    //         $cdBuscado = $consulta->fetchObject('cd');
    //         return $cdBuscado;
    //     }

    //     public function ModificarCd()
    //     {

    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("
    //                 update cds 
    //                 set titel='$this->titulo',
    //                 interpret='$this->cantante',
    //                 jahr='$this->año'
    //                 WHERE id='$this->id'");
    //         return $consulta->execute();
    //     }

    //     public function ModificarCdParametros()
    //     {
    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("
    //                 update cds 
    //                 set titel=:titulo,
    //                 interpret=:cantante,
    //                 jahr=:anio
    //                 WHERE id=:id");
    //         $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //         $consulta->bindValue(':titulo', $this->titulo, PDO::PARAM_INT);
    //         $consulta->bindValue(':anio', $this->año, PDO::PARAM_STR);
    //         $consulta->bindValue(':cantante', $this->cantante, PDO::PARAM_STR);
    //         return $consulta->execute();
    //     }

    //     public function BorrarCd()
    //     {
    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("
    //                 delete 
    //                 from cds 				
    //                 WHERE id=:id");
    //         $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //         $consulta->execute();
    //         return $consulta->rowCount();
    //     }

    //     public static function BorrarCdPorAnio($año)
    //     {
    //         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //         $consulta = $objetoAccesoDato->RetornarConsulta("
    //                 delete 
    //                 from cds 				
    //                 WHERE jahr=:anio");
    //         $consulta->bindValue(':anio', $año, PDO::PARAM_INT);
    //         $consulta->execute();
    //         return $consulta->rowCount();
    //     }
    }
?>