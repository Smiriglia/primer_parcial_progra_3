<?php

    require_once("./models/class_reserva.php");

    class ReservaController {
        private function SubirFotoReserva($fotoReserva, $tipoCliente, $nro_cliente, $id)
        {
            
            $carpeta_archivos = './ImagenesDeReservas2023/';
            $nombre_archivo = $fotoReserva['name'];
            $extension = pathinfo($fotoReserva['name'] , PATHINFO_EXTENSION);
            $nombre_archivo = $tipoCliente . $nro_cliente . $id .  "." . $extension;
            $tipo_archivo = $fotoReserva['type'];
            $tamano_archivo = $fotoReserva['size'];

            $ruta_destino = $carpeta_archivos . $nombre_archivo;

            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 1000000))) {
                return "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .png o .jpg<br><li>se permiten archivos de 1000 Kb máximo.</td></tr></table>";
            }
            else
            {
                if (move_uploaded_file($fotoReserva['tmp_name'],  $ruta_destino))
                {
                    return "El archivo ha sido cargado correctamente.";
                }
                else
                {
                    return "Ocurrió algún error al subir el fichero. No pudo guardarse.";
                }
            }
        }

        public function insertarReserva($tipoCliente, $nro_cliente, $fechaEntrada, $fechaSalida, $tipoHabitacion, $importeTotal,$fotoReserva) {
            $reserva = new Reserva();
            $reserva->tipoCliente = $tipoCliente;
            $reserva->nro_cliente = $nro_cliente;
            $reserva->fechaEntrada = $fechaEntrada;
            $reserva->fechaSalida = $fechaSalida;
            $reserva->importeTotal = $importeTotal;
            
            if ($reserva->setTipoHabitacion($tipoHabitacion))
            {
                $id = $reserva->Insertar();
                return $this->SubirFotoReserva($fotoReserva, $tipoCliente, $nro_cliente, $id);
            }
            else
            {
                return "El tipo del reserva es invalido";
            }
        }

        public function CalcularTotalReservas($tipoHabitacion, $fecha)
        {
            $importeTotal = Reserva::CalcularTotalReservas($tipoHabitacion, $fecha);
            return "El importe total de habitaciones " . $tipoHabitacion . " en la fecha " . $fecha . " Es de: " . $importeTotal;
        }

        public function ListarEntreFechas($fechaEntrada, $fechaSalida)
        {
            $reservas = Reserva::TraerTodo();
            $reservasFiltradas = [];
            $fechaEntradaParseada = DateTime::createFromFormat('d/m/Y', $fechaEntrada);
            $fechaSalidaParseada = DateTime::createFromFormat('d/m/Y', $fechaSalida);

            usort($reservas, function($a, $b)
            {
                $fechaA = DateTime::createFromFormat('d/m/Y', $a->fechaEntrada);
                $fechaB = DateTime::createFromFormat('d/m/Y', $b->fechaEntrada);

                if ($fechaA === false || $fechaB === false)
                    return 0;

                return $fechaB->getTimestamp() - $fechaA->getTimestamp();
            });
            
            foreach ($reservas as $reserva) 
            {
                if ($reserva->fechaEntrada >= $fechaEntradaParseada && $reserva->fechaEntrada <= $fechaSalidaParseada)
                    $reservasFiltradas[] = $reserva;
            }

            return json_encode($reservasFiltradas, JSON_PRETTY_PRINT);
        }

        public function ListarTipoHabitacion()
        {
            $listaTipos = [];
            $tiposHabitacion = ["doble", "individual", "suite"];
            foreach ($tiposHabitacion as $tipoHabitacion) 
            {
                $listaFiltrada = Reserva::ObtenerReservasTipoHabitacion($tipoHabitacion);
                $listaTipos[$tipoHabitacion] = $listaFiltrada;
            }

            return json_encode($listaTipos, JSON_PRETTY_PRINT);
        }

        public function CancelarReserva($nro_cliente, $tipoCliente, $idReserva)
        {
            return Reserva::CancelarReserva($nro_cliente, $tipoCliente, $idReserva);
        }

        public function AjustarReserva($idReserva, $motivoAjuste)
        {
            $reserva = Reserva::TraerUnaReserva($idReserva);
            if (isset($reserva))
                return $reserva->Ajustar($motivoAjuste);
            else
                return ['error' => 'Error, Reserva inexistente'];
        }

        

        // public function modificarreserva($id, $titulo, $cantante, $anio) {
        //     $reserva = new reserva();
        //     $reserva->id = $id;
        //     $reserva->titulo = $titulo;
        //     $reserva->cantante = $cantante;
        //     $reserva->año = $anio;
        //     return $reserva->ModificarreservaParametros();
        // }

        // public function borrarreserva($id) {
        //     $reserva = new reserva();
        //     $reserva->id = $id;
        //     return $reserva->Borrarreserva();
        // }

        // public function listarreservas() {
        //     return reserva::TraerTodoLosreservas();
        // }

        // public function buscarreservaPorId($id) {
        //     $retorno = reserva::TraerUnreserva($id);
        //     if($retorno === false) { // Validamos que exista y si no mostramos un error
        //         $retorno =  ['error' => 'No existe ese id'];
        //     }
        //     return $retorno;
        // }

        // public function buscarreservaPorIdYAnio($id, $anio) {
        //     return reserva::TraerUnreservaAnioParamNombre($id, $anio);
        // }
    }
?>