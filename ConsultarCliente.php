<?php
    require_once("./controllers/cliente_controller.php");
    $clienteController = new ClienteController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST["nro_cliente"]) and isset($_POST["tipoCliente"]))
        {
            echo $clienteController->ConsultarCliente($_POST["nro_cliente"], $_POST["tipoCliente"]);
        }
        else
        {
            echo "faltan parametros.";
        }
    }
?>