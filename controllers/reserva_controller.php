<?php

    require_once("./models/class_reserva.php");
    require_once("./models/class_cliente.php");

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
                return ["error" => "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .png o .jpg<br><li>se permiten archivos de 1000 Kb máximo.</td></tr></table>"];
            }
            else
            {
                if (move_uploaded_file($fotoReserva['tmp_name'],  $ruta_destino))
                {
                    return ["mensaje" => "El archivo ha sido cargado correctamente."];
                }
                else
                {
                    return ["error" => "Ocurrió algún error al subir el fichero. No pudo guardarse."];
                }
            }
        }

        public function insertarReserva($tipoCliente, $nro_cliente, $fechaEntrada, $fechaSalida, $tipoHabitacion, $importeTotal,$fotoReserva) {
            $reserva = new Reserva();
            $clienteAux = Cliente::TraerUnCliente($nro_cliente, $tipoCliente);

            if (isset($clienteAux))
            {
                if ($clienteAux->estado !== "Cancelado")
                {
                    $reserva->nro_cliente = $nro_cliente;
                    $reserva->fechaEntrada = $fechaEntrada;
                    $reserva->fechaSalida = $fechaSalida;
                    $reserva->importeTotal = $importeTotal;
                    
                    if ($reserva->setTipoHabitacion($tipoHabitacion) and isset($clienteAux))
                    {
                        $reserva->tipoCliente = $clienteAux->tipoCliente;
                        $reserva->modalidadPago = $clienteAux->modalidadPago;

                        $id = $reserva->Insertar();
                        return $this->SubirFotoReserva($fotoReserva, $tipoCliente, $nro_cliente, $id);
                    }
                    else
                    {
                        return ["error" => "El tipo del reserva es invalido"];
                    }
                }
                else
                {
                    return ["error" => "Un cliente eliminado no puede crear una reserva"];
                }
            }
            else
            {
                return ["error" => "Cliente no encontrado"];
            }
        }

        public function CalcularTotalReservas($tipoHabitacion, $fecha)
        {
            $importeTotal = Reserva::CalcularTotalReservas($tipoHabitacion, $fecha);
            return "El importe total de habitaciones " . $tipoHabitacion . " en la fecha " . $fecha . " Es de: " . $importeTotal;
        }

        public function CalcularTotalCancelado($tipoCliente, $fecha)
        {
            $reservas = Reserva::TraerTodo();
            $importeCancelado = 0;

            $clienteAux = new Cliente();
            $clienteAux->setTipoCliente($tipoCliente);

            foreach ($reservas as $reserva)
            {
                if ($reserva->tipoCliente === $clienteAux->tipoCliente and
                    $reserva->fechaEntrada === $fecha and
                    $reserva->estado === "Cancelado")
                {
                    $importeCancelado += $reserva->importeTotal;
                }
            }

            return "El importe total cancelado clientes de tipo: " . $tipoCliente . " en la fecha " . $fecha . " Es de: " . $importeCancelado;
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
                $fecha = DateTime::createFromFormat('d/m/Y', $reserva->fechaEntrada);
                if ($reserva->estado !== "Cancelado" and $fecha >= $fechaEntradaParseada and $fecha <= $fechaSalidaParseada)
                    $reservasFiltradas[] = $reserva;
            }

            return json_encode($reservasFiltradas, JSON_PRETTY_PRINT);
        }
        public function ListarEntreFechasCancelado($fechaEntrada, $fechaSalida)
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
                $fecha = DateTime::createFromFormat('d/m/Y', $reserva->fechaEntrada);
                if ($reserva->estado === "Cancelado" and $fecha >= $fechaEntradaParseada and $fecha <= $fechaSalidaParseada)
                {    
                    $reservasFiltradas[] = $reserva;
                }
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

        public function ListarModalidadPago($modalidadPago)
        {
            return json_encode(Reserva::ListarModalidadPago($modalidadPago), JSON_PRETTY_PRINT);
        }

        public function CancelarReserva($nro_cliente, $tipoCliente, $idReserva)
        {
            $cliente = Cliente::TraerUnCliente($nro_cliente, $tipoCliente);
            if (isset($cliente))
                return Reserva::CancelarReserva($cliente->nro_cliente, $cliente->tipoCliente, $idReserva);
            else
                return ["error" => "Error, Cliente inexistente"];
        }

        public function AjustarReserva($idReserva, $motivoAjuste)
        {
            $reserva = Reserva::TraerUnaReserva($idReserva);
            if (isset($reserva))
                return $reserva->Ajustar($motivoAjuste);
            else
                return ['error' => 'Error, Reserva inexistente'];
        }
    }
?>