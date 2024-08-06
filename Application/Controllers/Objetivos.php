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
}