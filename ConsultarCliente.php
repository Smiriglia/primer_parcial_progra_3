<?php
    require_once("./controllers/cliente_controller.php");
    $clienteController = new ClienteController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $respuesta;
        if(isset($_POST["nro_cliente"]) and isset($_POST["tipoCliente"]))
        {
            $respuesta = $clienteController->ConsultarCliente($_POST["nro_cliente"], $_POST["tipoCliente"]);
        }
        else
        {
            $respuesta = ["error" => "faltan parametros."];
        }

        echo json_encode($respuesta, JSON_PRETTY_PRINT);
    }
?>