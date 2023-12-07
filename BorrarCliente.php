<?php
    require_once("./controllers/cliente_controller.php");
    $respuesta;
    $clienteController = new ClienteController();
    if(isset($_GET["nro_cliente"]) && isset($_GET["tipoCliente"])){
        $respuesta = $clienteController->EliminarUsuario($_GET["nro_cliente"], $_GET["tipoCliente"]);
    }
    else
    {
        $respuesta = ['error' => 'Error, los parametros no coinciden con ninguna opcion posible'];
    }

    echo json_encode($respuesta, JSON_PRETTY_PRINT);
?>