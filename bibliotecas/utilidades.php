<?php
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

    function EstaModificandoCliente()
    {
        $input_data = file_get_contents('php://input');
        $decoded_data = json_decode($input_data, true);
        
        $parametros = ["nro_cliente", "nombre", "tipoDocumento","numeroDocumento","email","tipoCliente","pais","ciudad","telefono"];
        // if (!isset($_FILES['foto_perfil']))
        // {
        //     echo "<br><br>" . 'foto_perfil' . "<br><br>";
        //     return false;
        // }
        foreach ($parametros as $parametro)
        {
            if (!isset($decoded_data[$parametro]))
            {
                echo "<br><br>" . $parametro . "<br><br>";
                return false;
            }
        }
        return true;
    }

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

?>