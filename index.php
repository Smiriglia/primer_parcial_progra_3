<?php
    if (!isset($_GET["accion"]))
        echo json_encode(['error' => 'Debes ingresar el parametro accion'], JSON_PRETTY_PRINT);
    else
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET':
                switch(($_GET["accion"]))
                {
                    case 'ConsultaReservas':
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
                    case 'ClienteAlta':
                        include 'ClienteAlta.php';
                        break;
                    case 'ConsultarCliente':
                        include 'ConsultarCliente.php';
                        break;
                    case 'ReservaHabitacion':
                        include 'ReservaHabitacion.php';
                        break;
                    case 'CancelarReserva':
                        include 'CancelarReserva.php';
                        break;
                    case 'AjusteReserva':
                        include 'AjustesReserva.php';
                        break;
                    default:
                        echo json_encode(['error' => 'accion inexistente en post'], JSON_PRETTY_PRINT);
                        break;
                }
                break;

            case 'PUT':
                switch(($_GET["accion"])){
                    case 'ModificarCliente':
                        include 'ModificarCliente.php';
                        break;
                    default:
                        echo json_encode(['error' => 'accion inexistente en put'], JSON_PRETTY_PRINT);
                        break;
                }
                break;

            case 'DELETE':
                switch(($_GET["accion"])){
                    case 'BorrarCliente':
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