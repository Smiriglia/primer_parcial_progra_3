<?php

    require_once("./models/class_reserva.php");

    class ReservaController {
        private function SubirFotoReserva($fotoReserva, $nroreserva, $tiporeserva )
        {
            
            $carpeta_archivos = './ImagenesDeReservas2023/';

            $nombre_archivo = $fotoReserva['name'];
            $extension = pathinfo($fotoReserva['name'] , PATHINFO_EXTENSION);
            $nombre_archivo = $nroreserva . $tiporeserva . "." . $extension;
            $tipo_archivo = $fotoReserva['type'];
            $tamano_archivo = $fotoReserva['size'];

            // Ruta destino, carpeta + nombre del archivo que quiero guardar
            $ruta_destino = $carpeta_archivos . $nombre_archivo;

            // Realizamos las validaciones del archivo
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

        public function insertarReserva($tiporeserva, , $numeroDocumento, $email, $tiporeserva, $pais, $ciudad, $telefono, $fotoPerfil) {
            $reserva = new Reserva();
            $reserva->nombre = $nombre;
            $reserva->tipoDocumento = $tipoDocumento;
            $reserva->numeroDocumento = $numeroDocumento;
            $reserva->email = $email;
            $reserva->pais = $pais;
            $reserva->ciudad = $ciudad;
            $reserva->telefono = $telefono;
            
            if ($reserva->setTiporeserva($tiporeserva))
            {
                $nroReserva = $reserva->Insertar();
                return $this->SubirFotoReserva($fotoPerfil, $nroReserva, $reserva->tiporeserva);
            }
            else
            {
                return "El tipo del reserva es invalido";
            }
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