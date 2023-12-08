<?php
    require_once("./controllers/reserva_controller.php");
    require_once("./controllers/cliente_controller.php");
    
    $clienteController = new ClienteController();
    $reservaController = new ReservaController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        if (isset($_GET["tipoHabitacion"]))
        {
            $tipoHabitacion = $_GET["tipoHabitacion"];
            if (isset($_GET["fecha"]))
                $fecha = $_GET["fecha"];
            else
            {
                $hoy = new DateTime();
                $ayer = $hoy->modify('-1 day');
                $fecha = $ayer->format('d/m/Y');
            }

            echo $reservaController->CalcularTotalReservas($tipoHabitacion, $fecha);
           
        }
        elseif (isset($_GET["nro_cliente"]) and isset($_GET["tipoCliente"]))
        {
            echo $clienteController->ObtenerReservasActivas($_GET["nro_cliente"], $_GET["tipoCliente"]);
        }
        elseif (isset($_GET["fechaEntrada"]) and isset($_GET["fechaSalida"]))
        {
            if (isset($_GET["tipoListado"]) and $_GET["tipoListado"] === "Cancelado")
                echo $reservaController->ListarEntreFechasCancelado($_GET["fechaEntrada"], $_GET["fechaSalida"]);
            else
                echo $reservaController->ListarEntreFechas($_GET["fechaEntrada"], $_GET["fechaSalida"]);
        }
        elseif (isset($_GET["nombreCliente"]) and isset($_GET["tipoCliente"]))
        {
            if (isset($_GET["tipoListado"]) and $_GET["tipoListado"] === "Cancelado")
                echo $clienteController->ObtenerCancelacionesCliente($_GET["nombreCliente"], $_GET["tipoCliente"]);
            else
                echo $clienteController->ObtenerReservas($_GET["nombreCliente"], $_GET["tipoCliente"]);

        }
        elseif (isset($_GET["tipoCliente"]))
        {
            if (isset($_GET["tipoListado"]) and $_GET["tipoListado"] === "fecha")
            {
                $tipoCliente = $_GET["tipoCliente"];
                if (isset($_GET["fecha"]))
                    $fecha = $_GET["fecha"];
                else
                {
                    $hoy = new DateTime();
                    $ayer = $hoy->modify('-1 day');
                    $fecha = $ayer->format('d/m/Y');
                }
                echo $reservaController->CalcularTotalCancelado($tipoCliente, $fecha);
            }
            else
            {
                echo $clienteController->ObtenerCancelacionesTipoCliente($_GET["tipoCliente"]);
            }

        }
        elseif (isset($_GET["modalidadPago"]))
        {
            echo $reservaController->ListarModalidadPago($_GET["modalidadPago"]);
        }
        else
        {
            echo $reservaController->ListarTipoHabitacion();
        }
    }
?>