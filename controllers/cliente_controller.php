<?php

    require_once("./models/class_cliente.php");

    class ClienteController {
        private function SubirFotoPerfil($fotoPerfil, $cliente)
        {
            $nroCliente = $cliente->nro_cliente;
            $tipoCliente = $cliente->tipoCliente;

            $carpeta_archivos = './ImagenesDeClientes/2023/';

            $nombre_archivo = $fotoPerfil['name'];
            $extension = pathinfo($fotoPerfil['name'] , PATHINFO_EXTENSION);
            $nombre_archivo = $nroCliente . substr($tipoCliente, 0, 2) . "." . $extension;
            $tipo_archivo = $fotoPerfil['type'];
            $tamano_archivo = $fotoPerfil['size'];

            // Ruta destino, carpeta + nombre del archivo que quiero guardar
            $ruta_destino = $carpeta_archivos . $nombre_archivo;

            // Realizamos las validaciones del archivo
            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 1000000))) {
                ['error' => "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .png o .jpg<br><li>se permiten archivos de 1000 Kb máximo.</td></tr></table>"];
            }
            else
            {
                if (move_uploaded_file($fotoPerfil['tmp_name'],  $ruta_destino))
                {
                    $cliente->nombreArchivo = $nombre_archivo;
                    return ['mensaje' => "El archivo ha sido cargado correctamente."];
                }
                else
                {
                    return ['error' => "Ocurrió algún error al subir el fichero. No pudo guardarse."];
                }
            }
        }

        public function insertarCliente($nombre, $tipoDocumento, $numeroDocumento, $email, $tipoCliente, $pais, $ciudad, $telefono, $modalidadPago = "efectivo", $fotoPerfil = null) {
            $cliente = new Cliente();
            $cliente->nombre = $nombre;
            $cliente->email = $email;
            $cliente->pais = $pais;
            $cliente->ciudad = $ciudad;
            $cliente->telefono = $telefono;
            $cliente->modalidadPago = $modalidadPago;
            
            if ($cliente->setTipoCliente($tipoCliente) and $cliente->SetTipoDocumento($tipoDocumento) and $cliente->SetNumeroDocumento($numeroDocumento))
            {
                $cliente->SetNroCliente();
                if ($fotoPerfil != null)
                {
                    $respuestaArchivo = $this->SubirFotoPerfil($fotoPerfil, $cliente);
                    if (isset($respuestaArchivo["error"]))
                    {
                        return $respuestaArchivo;
                    }
                    elseif ($cliente->Insertar())
                    {
                        return ['mensaje' => "Cliente Agregado Correctamente."];
                    }
                    else
                    {
                        return ['error' => "Error, Hubo un problema al guardar el usuario"];
                    }

                }
                elseif ($cliente->Insertar())
                    return ['mensaje' => "Cliente Modificado Correctamente."];
                else 
                    return ['error' => "Error, Hubo un problema al guardar el usuario"];
            }
            else
            {
                return ['error' => "Error con la informacion del cliente"];
            }
        }

        public function ConsultarCliente($nroCliente, $tipoCliente)
        {
            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
            {
                if ($cliente->estado != "Eliminado")
                    return ["mensaje" => $cliente->MostrarDatos()];
                else
                    return ["error" => "El cliente que intentas consultar esta eliminado"];
            }
            else
            {
                return ["error" => "Cliente inexistente"];
            }
        }

        public function ObtenerReservasActivas($nroCliente, $tipoCliente)
        {
            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
                if ($cliente->estado != "Eliminado")
                    $reservasCliente = Reserva::ObtenerReservasActivasCliente($cliente);
                else
                    $reservasCliente = ["error" => "El cliente esta eliminado"];
            else
                $reservasCliente = [];
            return json_encode($reservasCliente, JSON_PRETTY_PRINT);
        }

        public function ObtenerCancelacionesCliente($nombreCliente, $tipoCliente)
        {
            $cliente = Cliente::TraerUnClienteNombreTipo($nombreCliente, $tipoCliente);
            if (isset($cliente))
                if ($cliente->estado != "Eliminado")
                    $reservasCliente = Reserva::ObtenerReservasCanceladasCliente($cliente);
                else
                    $reservasCliente = ["error" => "El cliente esta eliminado"];
            else
                $reservasCliente = [];
            return json_encode($reservasCliente, JSON_PRETTY_PRINT);
        }

        public function ObtenerReservas($nombreCliente, $tipoCliente)
        {
            $cliente = Cliente::TraerUnClienteNombreTipo($nombreCliente, $tipoCliente);
            if (isset($cliente))
                if ($cliente->estado != "Eliminado")
                    $reservasCliente = Reserva::ObtenerReservas($cliente);
                else
                    $reservasCliente = ["error" => "El cliente esta eliminado"];
            else
                $reservasCliente = [];
            return json_encode($reservasCliente, JSON_PRETTY_PRINT);
        }

        

        public function ObtenerCancelacionesTipoCliente($tipoCliente)
        {
            $clienteAux = new Cliente();
            if ($clienteAux->setTipoCliente($tipoCliente))
                    $reservasCliente = Reserva::ObtenerReservasCanceladasTipoCliente($clienteAux->tipoCliente);
            else
                $reservasCliente = [];
            return json_encode($reservasCliente, JSON_PRETTY_PRINT);
        }

        public function EliminarUsuario($nroCliente, $tipoCliente)
        {
            $cliente = Cliente::TraerUnCliente($nroCliente, $tipoCliente);
            if (isset($cliente))
            {
                if ($cliente->estado != "Eliminado")
                {
                    $nombreImagen = $cliente->nombreArchivo;
                    $pathInicio = "./ImagenesDeClientes/2023/" . $nombreImagen;
                    $pathFinal = "./ImagenesBackupClientes/2023/" . $nombreImagen;
                    if(file_exists($pathInicio) and rename($pathInicio, $pathFinal))
                        if ($cliente->Eliminar())
                            return ['mensaje' => "El usuario se ha eliminado correctamente"];
                        else
                            return ['error' => "Error al eliminar el usuario"];

                    else
                        return ['error' => 'Error, al mover la foto de perfil a backup'];
                }
                else
                {
                    return ["error" => "Error, el usuario ya ha sido eliminado"];
                }
            }
            else
            {
                return ["error" => "Error, credenciales del usuario incorrectas"];
            }
            
            
        }
    }
?>