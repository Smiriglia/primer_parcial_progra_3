<?php
    require_once("./controllers/cliente_controller.php");
    require_once("./bibliotecas/utilidades.php");
    $clienteController = new ClienteController();

    if ($_SERVER['REQUEST_METHOD'] === 'PUT')
    {
        if (EstaModificandoCliente())
        {
            $input_data = file_get_contents('php://input');
            $decoded_data = json_decode($input_data, true);
            $resultado = $clienteController->ConsultarCliente($decoded_data["nro_cliente"], $decoded_data["tipoCliente"]);
            if ($resultado != "Cliente inexistente" and $resultado != "tipo de cliente incorrecto.")
            {
                if (isset($decoded_data["modalidadPago"]))
                    $modalidad = $decoded_data["modalidadPago"];
                else
                    $modalidad = "efectivo";
                echo $clienteController->insertarCliente($decoded_data["nombre"], $decoded_data["tipoDocumento"], $decoded_data["numeroDocumento"], $decoded_data["email"], $decoded_data["tipoCliente"],$decoded_data["pais"],$decoded_data["ciudad"],$decoded_data["telefono"], $modalidad);
            }
            else
            {
                echo $resultado;
            }
        }
        else
        {
            echo "No has ingresado una accion posible";
        }
    }
    
?>