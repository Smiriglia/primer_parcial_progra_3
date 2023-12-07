<?php
    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            switch(($_GET["accion"]))
            {
                case 'consultaReservas':
                    include 'ConsultasReserva.php';
                    break;
                default:
                    echo json_encode(['error' => 'accion inexistente en get'], JSON_PRETTY_PRINT);
                    break;
            }
            break;

        case 'POST':
            switch(($_GET["accion"]))
            {
                case 'clienteAlta':
                    include 'ClienteAlta.php';
                    break;
                case 'ClienteConsulta':
                    include 'ConsultarCliente.php';
                    break;
                case 'reservaHabitacion':
                    include 'ReservaHabitacion.php';
                    break;
                case 'cancelarReserva':
                    include 'CancelarReserva.php';
                    break;
                case 'ajusteReserva':
                    include 'AjusteReserva.php';
                    break;
                default:
                    echo json_encode(['error' => 'accion inexistente en post'], JSON_PRETTY_PRINT);
                    break;
            }
            break;

        case 'PUT':
            switch(($_GET["accion"])){
                case 'modificarCliente':
                    include 'ModificarCliente.php';
                    break;
                default:
                    echo json_encode(['error' => 'accion inexistente en put'], JSON_PRETTY_PRINT);
                    break;
            }
            break;

        case 'DELETE':
            switch(($_GET["accion"])){
                case 'borrarCliente':
                    include 'BorrarCliente.php';
                    break;
                default:
                    echo json_encode(['error' => 'accion inexistente en delete'], JSON_PRETTY_PRINT);
                    break;
            }
            break;

        default:
            echo 'Metodo no permitido';
            break;
    }
?>