<?php

    require_once("./models/class_cliente.php");

    class ClienteController {
        private function SubirFotoPerfil($fotoPerfil, $nroCliente, $tipoCliente )
        {
            
            $carpeta_archivos = './ImagenesDeClientes/2023';

            $nombre_archivo = $fotoPerfil['name'];
            $extension = pathinfo($fotoPerfil['name'] , PATHINFO_EXTENSION);
            $nombre_archivo = $nroCliente . $tipoCliente . "." . $extension;
            $tipo_archivo = $fotoPerfil['type'];
            $tamano_archivo = $fotoPerfil['size'];

            // Ruta destino, carpeta + nombre del archivo que quiero guardar
            $ruta_destino = $carpeta_archivos . $nombre_archivo;

            // Realizamos las validaciones del archivo
            if (!((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 1000000))) {
                return "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .png o .jpg<br><li>se permiten archivos de 1000 Kb máximo.</td></tr></table>";
            }
            else
            {
                echo $fotoPerfil['name'];
                echo $fotoPerfil['tmp_name'];

                var_dump($fotoPerfil);
                if (move_uploaded_file($fotoPerfil['tmp_name'],  $ruta_destino))
                {
                    return "El archivo ha sido cargado correctamente.";
                }
                else
                {
                    return "Ocurrió algún error al subir el fichero. No pudo guardarse.";
                }
            }
        }

        public function insertarCliente($nombre, $tipoDocumento, $numeroDocumento, $email, $tipoCliente, $pais, $ciudad, $telefono, $fotoPerfil) {
            $cliente = new Cliente();
            $cliente->nombre = $nombre;
            $cliente->tipoDocumento = $tipoDocumento;
            $cliente->numeroDocumento = $numeroDocumento;
            $cliente->email = $email;
            $cliente->pais = $pais;
            $cliente->ciudad = $ciudad;
            $cliente->telefono = $telefono;
            
            if ($cliente->setTipoCliente($tipoCliente))
            {
                $nroCliente = $cliente->Insertar();
                return $this->SubirFotoPerfil($fotoPerfil, $nroCliente, $cliente->tipoCliente);
            }
            else
            {
                return "El tipo del cliente es invalido";
            }
        }

        public function ConsultarCliente($nroCliente, $tipoCliente)
        {
            $cliente = Cliente::TraerUnCliente($nroCliente);
            if (isset($cliente))
            {

                if ($cliente->tipoCliente === $tipoCliente)
                {
                    return $cliente->MostrarDatos();
                }
                else
                {
                    return "tipo de cliente incorrecto.";
                }
            }
            else
            {
                return "Cliente inexistente";
            }
        }

        // public function modificarCliente($id, $titulo, $cantante, $anio) {
        //     $cliente = new Cliente();
        //     $cliente->id = $id;
        //     $cliente->titulo = $titulo;
        //     $cliente->cantante = $cantante;
        //     $cliente->año = $anio;
        //     return $cliente->ModificarClienteParametros();
        // }

        // public function borrarCliente($id) {
        //     $Cliente = new Cliente();
        //     $Cliente->id = $id;
        //     return $Cliente->BorrarCliente();
        // }

        // public function listarClientes() {
        //     return Cliente::TraerTodoLosClientes();
        // }

        // public function buscarClientePorId($id) {
        //     $retorno = Cliente::TraerUnCliente($id);
        //     if($retorno === false) { // Validamos que exista y si no mostramos un error
        //         $retorno =  ['error' => 'No existe ese id'];
        //     }
        //     return $retorno;
        // }

        // public function buscarClientePorIdYAnio($id, $anio) {
        //     return Cliente::TraerUnClienteAnioParamNombre($id, $anio);
        // }
    }
?>