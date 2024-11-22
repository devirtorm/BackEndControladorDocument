<?php

use MVC\Controller;

class ControllersDepartamentos extends Controller
{

    public function obtenerDepartamentos()
    {

        // Connect to database
        $model = $this->model('Departamentos');

        $data_list = $model->departamentos(1);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }



    public function obtenerDepartamento($param) {

            $model = $this->model('Departamentos');
            $result = $model->departamentos($param['id']);

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
    } 
    
    public function obtenerDepartamentosDesactivados()
    {
        // Connect to database
        $model = $this->model('Departamentos');

        $data_list = $model->departamentos(0);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearDepartamento() {
        $this->verifyToken(); // Verificar el token JWT

        $model = $this->model('Departamentos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
        
        if ($data !== null && isset($data['nombre_departamento'], $data['fk_area'])) {
            $nombre_departamento = filter_var($data['nombre_departamento'], FILTER_SANITIZE_SPECIAL_CHARS);
            $fk_area = filter_var($data['fk_area'], FILTER_SANITIZE_SPECIAL_CHARS);
            
            $inserted = $model->insertDepartamento([
                'nombre_departamento' => $nombre_departamento, 
                'fk_area' => $fk_area
            ]);
            
            if ($inserted) {
                echo json_encode(['message' => 'Departamento guardado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar Departamento.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos del departamento son inválidos o incompletos.']);
        }
    }
    

    
    public function eliminarDepartamentos($param) {
        $this->verifyToken(); // Verificar el token JWT

        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Departamentos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarDepartamento($id);
    
            // Preparar la respuesta
            if ($deleted) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'departamento eliminado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo eliminar este departamento.'
                ]);
            }
        } else {
            // Preparar la respuesta para parámetro inválido
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }
    
    // Método auxiliar para validar el ID
    private function validId($id) {
        return filter_var($id, FILTER_VALIDATE_INT) !== false && $id > 0;
    }

    public function desactivarDepartamento($param) {
        $this->verifyToken(); // Verificar el token JWT

        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Departamentos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Departamento desactivado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar el departamento.'
                ]);
            }
        } else {
            // Preparar la respuesta para parámetro inválido
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }

    public function activarDepartamento($param) {
        $this->verifyToken(); // Verificar el token JWT

        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Departamentos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 1);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Departamento activado correctamente.'

                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo activar el departamento.'
                ]);
            }
        } else {
            // Preparar la respuesta para parámetro inválido
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }
    
    public function actualizarDepartamento($param) {
        $this->verifyToken(); // Verificar el token JWT

        $model = $this->model('Departamentos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
        
        // Verificar si los datos son válidos
        if ($data !== null && isset($data['nombre_departamento']) && isset($data['fk_area'])) {
            $nombre_departamento = filter_var($data['nombre_departamento'], FILTER_SANITIZE_SPECIAL_CHARS);
            $fk_area = filter_var($data['fk_area'], FILTER_SANITIZE_NUMBER_INT);
            
            if (isset($param['id']) && $this->validId($param['id'])) {
                // Actualizar el departamento existente
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->updateDepartamento([
                    'id' => $id, 
                    'nombre_departamento' => $nombre_departamento,
                    'fk_area' => $fk_area
                ]);
                
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent([
                        'message' => 'Departamento actualizado correctamente.'
                    ]);
                } else {
                    $this->response->sendStatus(500);
                    $this->response->setContent([
                        'message' => 'Error: No se pudo actualizar el departamento.'
                    ]);
                }
            } else {
                $this->response->sendStatus(400);
                $this->response->setContent([
                    'message' => 'Error: ID de departamento inválido.'
                ]);
            }
        } else {
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Error: Datos incompletos o inválidos.'
            ]);
        }
    }
    
    
    


}