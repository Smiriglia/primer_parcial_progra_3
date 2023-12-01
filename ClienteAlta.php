<?php
    require_once("./controllers/cliente_controller.php");
    $clienteController = new ClienteController();
    function EstaInsertandoCliente()
    {
        $parametros = ["nombre", "tipoDocumento","numeroDocumento","email","tipoCliente","pais","ciudad","telefono"];
        if (!isset($_FILES['foto_perfil']))
            return false;
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
        if (EstaInsertandoCliente())
        {
            echo $clienteController->insertarCliente($_POST["nombre"], $_POST["tipoDocumento"], $_POST["numeroDocumento"], $_POST["email"], $_POST["tipoCliente"],$_POST["pais"],$_POST["ciudad"],$_POST["telefono"], $_FILES['foto_perfil']);
        }
        else
        {
            echo "No has ingresado una accion posible";
        }

    }
    
?>