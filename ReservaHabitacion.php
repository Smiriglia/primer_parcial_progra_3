<?php
    require_once("./controllers/cliente_controller.php");
    require_once("./controllers/reserva_controller.php");
    require_once("./bibliotecas/utilidades.php");

    $clienteController = new ClienteController();
    $reservaController = new ReservaController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (EstaInsertandoReserva())
        {
            $consultaCliente = $clienteController->ConsultarCliente($_POST["nro_cliente"], $_POST["tipo_cliente"]);
            if ($consultaCliente != "Cliente inexistente" and $consultaCliente != "tipo de cliente incorrecto.")
                echo $reservaController->insertarReserva($_POST["tipo_cliente"], $_POST["nro_cliente"], $_POST["fechaEntrada"], $_POST["fechaSalida"], $_POST["tipoHabitacion"], $_POST["importeTotal"],$_FILES['foto_reserva']);
            else
                echo $consultaCliente;
        }
        else
        {
            echo "No has ingresado una accion posible";
        }

    }
    
?>