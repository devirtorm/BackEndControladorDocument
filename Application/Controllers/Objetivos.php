<?php

use MVC\Controller;

class ControllersObjetivos extends Controller
{
    public function objetivos()
{
    // Connect to database
    $model = $this->model('Objetivos');

    $data_list = $model->getObjetivos(1);

    // Send Response
    $this->response->sendStatus(200);
    $this->response->setContent($data_list);
}
public function obtenerDesactivados()
{
    // Connect to database
    $model = $this->model('Objetivos');

    $data_list = $model->getObjeti(0);

    // Send Response
    $this->response->sendStatus(200);
    $this->response->setContent($data_list);
}
private function validId($id) {
    return filter_var($id, FILTER_VALIDATE_INT) !== false && $id > 0;
}
public function activarObjetivo($param) {
    if (isset($param['id']) && $this->validId($param['id'])) {
        $model = $this->model('Objetivos');
        $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
        $updated = $model->actualizarActivo($id, 1);

        if ($updated) {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message' => 'Rol activado correctamente.'
            ]);
        } else {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message' => 'Error: No se pudo activar el rol.'
            ]);
        }
    } else {
        $this->response->sendStatus(400);
        $this->response->setContent([
            'message' => 'Invalid ID or ID is missing.'
        ]);
    }
}

public function desactivarObjetivo($param) {
    if (isset($param['id']) && $this->validId($param['id'])) {
        $model = $this->model('Objetivos');
        $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
        $updated = $model->actualizarActivo($id, 0);

        if ($updated) {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message' => 'Rol desactivado correctamente.'
            ]);
        } else {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message' => 'Error: No se pudo desactivar el rol.'
            ]);
        }
    } else {
        $this->response->sendStatus(400);
        $this->response->setContent([
            'message' => 'Invalid ID or ID is missing.'
        ]);
    }
}
public function eliminarObjetivo($param) {
    if (isset($param['id']) && $this->validId($param['id'])) {
        $model = $this->model('Objetivos');
        $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
        $deleted = $model->eliminarObjetivo($id);

        if ($deleted) {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message' => 'Rol eliminado correctamente.'
            ]);
        } else {
            $this->response->sendStatus(200);
            $this->response->setContent([
                'message' => 'Error: No se pudo eliminar el rol.'
            ]);
        }
    } else {
        $this->response->sendStatus(400);
        $this->response->setContent([
            'message' => 'Invalid ID or ID is missing.'
        ]);
    }
}
public function crearObjetivo() {
    $model = $this->model('Objetivos');
    $json_data = file_get_contents('php://input');
    error_log("JSON Data: " . $json_data);
    $data = json_decode($json_data, true);

    if ($data !== null && 
        isset($data['numero']) && 
        isset($data['descripcion']) && 
        isset($data['active_tab']) && 
        isset($data['indicadores']) && 
        is_array($data['indicadores'])) {
        
        $numero = filter_var($data['numero'], FILTER_SANITIZE_NUMBER_INT);
        $descripcion = filter_var($data['descripcion'], FILTER_SANITIZE_SPECIAL_CHARS);
        $active_tab = filter_var($data['active_tab'], FILTER_SANITIZE_NUMBER_INT);
        
        $indicadores = array_map(function($indicador) {
            return ['nombre' => filter_var($indicador['nombre'], FILTER_SANITIZE_SPECIAL_CHARS)];
        }, $data['indicadores']);

        $objetivoData = [
            'numero' => $numero,
            'descripcion' => $descripcion,
            'active_tab' => $active_tab,
            'indicadores' => $indicadores
        ];

        $inserted = $model->insertarObjetivo($objetivoData);

        if ($inserted) {
            $this->response->sendStatus(201);
            $this->response->setContent([
                'message' => 'Objetivo creado correctamente.',
                'objetivos' => $model->getObjetivos(1)
            ]);
        } else {
            error_log("Error al insertar el objetivo.");
            $this->response->sendStatus(500);   
            $this->response->setContent([
                'message' => 'Error al crear el objetivo.'
            ]);
        }
    } else {
        error_log("Datos del objetivo inválidos o incompletos.");
        $this->response->sendStatus(400);
        $this->response->setContent([
            'message' => 'Error: Los datos del objetivo son inválidos o incompletos.'
        ]);
    }
}

}