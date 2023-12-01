<?php
    require_once("./controllers/cliente_controller.php");
    require_once("./controllers/reserva_controller.php");

    $clienteController = new ClienteController();
    $reservaController = new ReservaController();
    
    function EstaInsertandoReserva()
    {
        if (!isset($_FILES['foto_reserva']))
            return false;
        $parametros = ["tipo_cliente", "nro_cliente", "fechaEntrada","fechaSalida","tipoHabitacion", "importeTotal"];
        foreach ($parametros as $parametro)
        {
            if (!isset($_POST[$parametro]))
            {
                return false;
            }
        }
        return true;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (EstaInsertandoReserva())
        {
            $consultaCliente = $clienteController->ConsultarCliente($_POST["nro_cliente"], $_POST["tipo_cliente"]);
            if ($consultaCliente != "Cliente inexistente")
                echo $reservaController->insertarReserva($_POST["tipo_cliente"], $_POST["nro_cliente"], $_POST["fechaEntrada"], $_POST["fechaSalida"], $_POST["tipoHabitacion"], $_POST["importeTotal"],$_FILES['foto_reserva']);
            else
                echo "No has ingresado un Cliente Existente.";
        }
        else
        {
            echo "No has ingresado una accion posible";
        }

    }
    
?>