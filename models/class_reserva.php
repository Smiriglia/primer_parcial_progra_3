<?php
    require_once ("./capa_datos/class_manejador_archivos.php");
    class Reserva
    {
        public $id;
        public $tipoCliente;
        public $nro_cliente;
        public $fechaEntrada;
        public $fechaSalida;
        public $tipoHabitacion;
        public $importeTotal;

        public function setTipoHabitacion($tipo)
        {
            $tipo = strtolower($tipo);
            if ($tipo === "doble" or $tipo === "individual" or $tipo === "suite")
            {
                $this->tipoHabitacion = $tipo;
                return true;
            }
            return false;
        }

        // public function MostrarDatos()
        // {
        //     return "Pais: " . $this->pais . "  Ciudad: " . $this->ciudad . "  Telefono: " . $this->telefono;
        // }

        public function Insertar($rutaArchivo = "./datos/reservas.json")
        {
            $accesoUltimoIdReservas = new ManejadorArchivos("./datos/ultimo_id_reservas.json");
            $objetoUltimoIdReservas = $accesoUltimoIdReservas->leer();
            
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $reservas = $objetoAccesoDato->leer();

            

            $objetoUltimoIdReservas["id"] += 1;
            $this->id = $objetoUltimoIdReservas["id"];
            $reservas[] = $this;

            $objetoAccesoDato->guardar($reservas);
            $accesoUltimoIdReservas->guardar($objetoUltimoIdReservas);
            
            return $this->id;
        }

        public static function TraerTodo($rutaArchivo = "./datos/reservas.json")
        {
            $objetoAccesoDato = new ManejadorArchivos($rutaArchivo);
            $reservasNoParseados = $objetoAccesoDato->leer();
            $reservas = [];
            foreach ($reservasNoParseados as $reservaNoParseado) 
            {
                $reserva = new Reserva();
                $reserva->id = $reservaNoParseado["id"];
                $reserva->nro_cliente = $reservaNoParseado["nro_cliente"];
                $reserva->fechaEntrada = $reservaNoParseado["fechaEntrada"];
                $reserva->fechaSalida = $reservaNoParseado["fechaSalida"];
                $reserva->setTipoHabitacion($reservaNoParseado["tipoHabitacion"]);
                $reserva->tipoCliente = $reservaNoParseado["tipoCliente"];
                $reserva->importeTotal = $reservaNoParseado["importeTotal"];
                $reservas[] = $reserva;
            }
            return $reservas;
        }

        public static function TraerUnaReserva($id)
        {
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($reserva->id === $id)
                {
                    return $reserva;
                }
            }
        }

        public static function CalcularTotalReservas($tipoHabitacion, $fecha){
            $importeTotal = 0;
            $reservas = Reserva::TraerTodo();
            $tipoHabitacion = strtolower($tipoHabitacion);
            foreach ($reservas as $reserva) 
            {
                if ($reserva->tipoHabitacion === $tipoHabitacion and $reserva->fechaEntrada == $fecha)
                {
                    $importeTotal += $reserva->importeTotal;
                }
            }
            return $importeTotal;
        }

        public static function ObtenerReservasCliente($cliente)
        {
            $reservasCliente = [];
            $reservas = Reserva::TraerTodo();
            foreach ($reservas as $reserva) 
            {
                if ($cliente->nro_cliente == $reserva->nro_cliente)
                    $reservasCliente[] = $reserva;
            }
            return $reservasCliente;
        }

        public static function ObtenerReservasTipoHabitacion($tipoHabitacion)
        {
            $reservas = Reserva::TraerTodo();
            $reservasFiltradas = [];
            foreach ($reservas as $reserva) 
            {
                if ($reserva->tipoHabitacion == $tipoHabitacion)
                {
                    $reservasFiltradas[] = $reserva;
                }
            }
            return $reservasFiltradas;
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