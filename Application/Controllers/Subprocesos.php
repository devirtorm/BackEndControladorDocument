<?php

use MVC\Controller;

class ControllersSubprocesos extends Controller
{
    public function crearSubproceso() {
        // Conectar al modelo
        $model = $this->model('Subprocesos');
    
        // Leer los datos JSON de la solicitud
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
    
        // Verificar si se recibieron datos válidos
        if ($data !== null && isset($data['subproceso'])) {
            // Insertar la persona en la base de datos
            $inserted = $model->insertarSubproceso($data);
    
            // Verificar si la inserción fue exitosa
            if ($inserted) {
                echo "Subproceso guardado correctamente.";
            } else {
                echo "Error al guardar subproceso.";
            }
        } else {
            // Si no se recibieron datos válidos, mostrar un mensaje de error
            echo "Error: Los datos de subproceso son inválidos o incompletos.";
        }
    }
}