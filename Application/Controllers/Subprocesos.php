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

    public function obtenerSubprocesosDesactivados()
    {

        // Connect to database
        $model = $this->model('Subprocesos');

        $data_list = $model->subprocesosDesactivados();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearSubproceso() {
        $model = $this->model('Subprocesos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        if ($data !== null && isset($data['subproceso']) && isset($data['fk_proceso'])) {
            $subproceso = filter_var($data['subproceso'], FILTER_SANITIZE_STRING);
            $proceso = filter_var($data['fk_proceso'], FILTER_VALIDATE_INT);
            $inserted = $model->insertarSubproceso(['subproceso' => $subproceso, 'fk_proceso' => $proceso]);
    
            if ($inserted) {
                echo json_encode(['message' => 'Subproceso guardado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar subproceso.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de subproceso son inválidos o incompletos.']);
        }
    }

    public function actualizarSubproceso($param) {
        $model = $this->model('Subprocesos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        // Verificar si los datos son válidos
        if ($data !== null && isset($data['subproceso']) && isset($data['fk_proceso'])) {
            $nombre_subproceso = filter_var($data['subproceso'], FILTER_SANITIZE_STRING);
            $proceso = filter_var($data['fk_proceso'], FILTER_VALIDATE_INT);
            
            if (isset($param['id']) && $this->validId($param['id'])) {
                // Actualizar el área existente
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->updateSubproceso(['id' => $id, 'subproceso' => $nombre_subproceso, 'fk_proceso'=>$proceso]);
        
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent([
                        'message' => 'Subproceso actualizado correctamente.'
                    ]);
                } else {
                    $this->response->sendStatus(500);
                    $this->response->setContent([
                        'message' => 'Error: No se pudo actualizar el subproceso.'
                    ]);
                }
            } 
        } 
    }


    public function eliminarSubproceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Subprocesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarSubproceso($id);
    
            // Preparar la respuesta
            if ($deleted) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Subproceso eliminado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo eliminar el subproceso.'
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

    public function desactivarSubproceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Subprocesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Subproceso actualizado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo actualizar el subproceso.'
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
    
    public function activarSubproceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Subprocesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 1);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Subproceso actualizado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo actualizar el subproceso.'
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
    
    
    


}