<?php
    require_once("./controllers/reserva_controller.php");
    require_once("./bibliotecas/utilidades.php");
    $reservaController = new ReservaController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST["idReserva"]) and isset($_POST["motivoAjuste"]))
        {
            $respuesta = $reservaController->AjustarReserva($_POST["idReserva"], $_POST["motivoAjuste"]);
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