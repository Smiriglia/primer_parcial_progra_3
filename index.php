<?php
    require_once("./controllers/cliente_controller.php");
    // require_once ("./capa_datos/class_manejador_archivos.php");
    // // Ejemplo de uso
    // $rutaArchivo = 'data.json';

    // // Crear una instancia del ManejadorArchivos
    // $manejadorArchivos = new ManejadorArchivos($rutaArchivo);

    // // Leer el archivo
    // $data = $manejadorArchivos->leer();

    // // Modificar los datos (agregar un nuevo objeto JSON)
    // $nuevoObj = ['id' => 1, 'nombre' => 'Franco'];
    // $data[] = $nuevoObj;

    // // Escribir los datos de vuelta en el archivo
    // $manejadorArchivos->guardar($data);

    // $nuevoCliente = new Cliente();
    // $nuevoCliente->nombre = "Juan";
    // $nuevoCliente->tipoDocumento = "DNI";
    // $nuevoCliente->numeroDocumento = "44266892";
    // $nuevoCliente->email = "cliente1@test.com";
    // $nuevoCliente->setTipoCliente("individual");
    // $nuevoCliente->pais = "Argentina";
    // $nuevoCliente->ciudad = "Avellaneda";
    // $nuevoCliente->telefono = "1132901352";
    // $nuevoCliente->Insertar();
    // var_dump($nuevoCliente);
    // $clientes = Cliente::TraerUnCliente("000001");
    // var_dump($clientes);
    $clienteController = new ClienteController();
    function EstaInsertando()
    {
        $parametros = ["nombre", "tipoDocumento","numeroDocumento","email","tipoCliente","pais","ciudad","telefono"];
        foreach ($parametros as $parametro)
        {
            if (!isset($_POST[$parametro]))
            {
                echo $parametro . "Entre <br>";
                return false;
            }
            echo $parametro . $_POST[$parametro] . "<br>";
        }
        return true;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (EstaInsertando() and isset($_FILES['foto_perfil']))
        {
            echo $clienteController->insertarCliente($_POST["nombre"], $_POST["tipoDocumento"], $_POST["numeroDocumento"], $_POST["email"], $_POST["tipoCliente"],$_POST["pais"],$_POST["ciudad"],$_POST["telefono"], $_FILES['foto_perfil']);
        }
        else
        {
            echo "No has ingresado una accion posible";
        }

    }
    
?>