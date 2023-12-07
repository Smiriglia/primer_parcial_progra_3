<?php
    require_once("./controllers/cliente_controller.php");
    require_once("./bibliotecas/utilidades.php");
    $clienteController = new ClienteController();
    $respuesta;

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (EstaInsertandoCliente())
        {
            if (isset($_POST["modalidadPago"]))
                $modalidad = $_POST["modalidadPago"];
            else
                $modalidad = "efectivo";
            $respuesta = $clienteController->insertarCliente($_POST["nombre"], $_POST["tipoDocumento"], $_POST["numeroDocumento"], $_POST["email"], $_POST["tipoCliente"],$_POST["pais"],$_POST["ciudad"],$_POST["telefono"], $modalidad, $_FILES['foto_perfil']);
        }
        else
        {
            $respuesta = ['error' => "No has ingresado una accion posible"];
        }
        echo json_encode($respuesta, JSON_PRETTY_PRINT);
    }
    
?>