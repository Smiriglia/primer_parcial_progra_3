<?php
    require_once("./controllers/cliente_controller.php");
    require_once("./controllers/reserva_controller.php");
    require_once("./bibliotecas/utilidades.php");

    $clienteController = new ClienteController();
    $reservaController = new ReservaController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $respuesta;
        if (EstaInsertandoReserva())
        {
            $consultaCliente = $clienteController->ConsultarCliente($_POST["nro_cliente"], $_POST["tipo_cliente"]);
            if (!isset($consultaCliente["error"]))
                $respuesta = $reservaController->insertarReserva($_POST["tipo_cliente"], $_POST["nro_cliente"], $_POST["fechaEntrada"], $_POST["fechaSalida"], $_POST["tipoHabitacion"], $_POST["importeTotal"],$_FILES['foto_reserva']);
            else
                $respuesta = $consultaCliente;
        }
        else
        {
            $respuesta = ["error" => "No has ingresado una accion posible"];
        }

        echo json_encode($respuesta, JSON_PRETTY_PRINT);



    }
    
?>