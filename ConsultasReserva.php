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

            return $reservaController->CalcularTotalReservas($tipoHabitacion, $fecha);
           
        }
        elseif (isset($_GET["nro_cliente"]))
        {
            echo $clienteController->ObtenerReservas($_GET["nro_cliente"]);
        }
        elseif (isset($_GET["fechaEntrada"]) and isset($_GET["fechaSalida"]))
        {
            echo $reservaController->ListarEntreFechas($_GET["fechaEntrada"], $_GET["fechaSalida"]);
        }
        else
        {
            echo $reservaController->ListarTipoHabitacion();
        }
    }
?>