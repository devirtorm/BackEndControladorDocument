<?php

use MVC\Controller;

class ControllersRoles extends Controller
{
    public function obtenerRoles()
    {
        $model = $this->model('Roles');
        $data_list = $model->roles(1);

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function obtenerRol($param) {
        $model = $this->model('Roles');
        $result = $model->rol($param['id']);

        $this->response->sendStatus(200);
        $this->response->setContent($result);
    }

    public function obtenerRolesDesactivados()
    {
        $model = $this->model('Roles');
        $data_list = $model->rolex(0);

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearRol() {
        $model = $this->model('Roles');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_rol'])) {
            $rol = htmlspecialchars($data['nombre_rol'], ENT_QUOTES, 'UTF-8');
          
            $inserted = $model->insertRol(['nombre_rol' => $rol]);

            if ($inserted) {
                echo json_encode(['message' => 'Rol guardado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar Rol.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos del rol son inválidos o incompletos.']);
        }
    }

    public function eliminarRol($param) {
        if (isset($param['id']) && $this->validId($param['id'])) {
            $model = $this->model('Roles');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarRol($id);

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

    private function validId($id) {
        return filter_var($id, FILTER_VALIDATE_INT) !== false && $id > 0;
    }

    public function desactivarRol($param) {
        if (isset($param['id']) && $this->validId($param['id'])) {
            $model = $this->model('Roles');
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

    public function activarRol($param) {
        if (isset($param['id']) && $this->validId($param['id'])) {
            $model = $this->model('Roles');
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

    public function actualizarRol($param) {
        $model = $this->model('Roles');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_rol'])) {
            $nombre_rol = filter_var($data['nombre_rol'], FILTER_SANITIZE_STRING);
            
            if (isset($param['id']) && $this->validId($param['id'])) {
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->updateRol(['id' => $id, 'nombre_rol' => $nombre_rol]);
        
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent([
                        'message' => 'Rol actualizado correctamente.'
                    ]);
                } else {
                    $this->response->sendStatus(500);
                    $this->response->setContent([
                        'message' => 'Error: No se pudo actualizar el rol.'
                    ]);
                }
            } 
        } 
    }
}