<?php
    require_once("./controllers/reserva_controller.php");
    $reservaController = new ReservaController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST["idReserva"]) )
        {
            $respuesta = $reservaController->CancelarReserva($_POST["nro_cliente"], $_POST["tipoCliente"], $_POST["idReserva"]);
        }
        else
        {
            $error = [];
            $error["error"] = "No has ingresado una accion posible";
            $respuesta = $error;
        }

        echo json_encode($respuesta, JSON_PRETTY_PRINT);

    }
    
?>