<?php

use MVC\Controller;

class ControllersSubprocesos extends Controller
{

    public function obtenerSubprocesos()
    {

        // Connect to database
        $model = $this->model('Subprocesos');

        $data_list = $model->subprocesos();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearSubproceso() {
        $model = $this->model('Subprocesos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        if ($data !== null && isset($data['subproceso'])) {
            $subproceso = filter_var($data['subproceso'], FILTER_SANITIZE_STRING);
            $inserted = $model->insertarSubproceso(['subproceso' => $subproceso]);
    
            if ($inserted) {
                echo json_encode(['message' => 'Subproceso guardado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar subproceso.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de subproceso son inválidos o incompletos.']);
        }
    }
    


}