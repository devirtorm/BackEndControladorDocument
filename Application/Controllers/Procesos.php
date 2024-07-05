<?php

use MVC\Controller;

class ControllersProcesos extends Controller
{

    public function obtenerProcesos()
    {

        // Connect to database
        $model = $this->model('Procesos');

        $data_list = $model->procesos(1);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    
    public function ObtenerProcesoByMacroId($param)
    {

        // Connect to database
        $model = $this->model('Procesos');

        $data_list = $model->getProcesosBymacroprocesoId($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }
    public function ObtenerProcesoByDepartamentoId($param)
    {
        // Connect to database
        $model = $this->model('Procesos');

        $data_list = $model->getProcesosByDepartamento($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }


/* obtener el subproceso dependiendo del proceso que seleccione en el select del front */
    public function ObtenerSubProcesoByProcesoId($param)
    {
        // Connect to database
        $model = $this->model('Procesos');

        $data_list = $model->getSubprocesosByProceso($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }



    public function obtenerProceso($param) {

            $model = $this->model('Procesos');
            $result = $model->procesos($param['id']);

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
    } 
    
    public function obtenerProcesosDesactivados()
    {

        // Connect to database
        $model = $this->model('Procesos');

        $data_list = $model->procesos(0);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearProcesos() {
        $model = $this->model('Procesos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        if ($data !== null && isset($data['proceso']) && isset($data['macroproceso'])) {
            $proceso = filter_var($data['proceso'], FILTER_SANITIZE_STRING);
            $macroproceso = filter_var($data['macroproceso'], FILTER_SANITIZE_STRING);
            $inserted = $model->insertProceso(['proceso' => $proceso, 'macroproceso' => $macroproceso]);
    
            if ($inserted) {
                echo json_encode(['message' => 'Proceso guardado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar Proceso.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de proceso son inválidos o incompletos.']);
        }
    }

    

    
    public function eliminarProceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Procesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarProceso($id);
    
            // Preparar la respuesta
            if ($deleted) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Proceso eliminado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo eliminar el proceso.'
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

    public function desactivarProceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Procesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Proceso desactivado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar el proceso.'
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

    public function activarProceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Procesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 1);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Proceso desactivado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar el proceso.'
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
    

    public function actualizarProceso($param) {
        $model = $this->model('Procesos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        // Verificar si los datos son válidos
        if ($data !== null && isset($data['proceso']) && isset($data['macroproceso'])) {
            $proceso = filter_var($data['proceso'], FILTER_SANITIZE_STRING);
            $macroproceso = filter_var($data['macroproceso'], FILTER_SANITIZE_STRING);
            
            if (isset($param['id']) && $this->validId($param['id'])) {
                // Actualizar el área existente
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->updateProceso(['id' => $id, 'proceso' => $proceso, 'macroproceso' => $macroproceso]);
        
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent([
                        'message' => 'proceso actualizado correctamente.'
                    ]);
                } else {
                    $this->response->sendStatus(500);
                    $this->response->setContent([
                        'message' => 'Error: No se pudo actualizar el proceso.'
                    ]);
                }
            } 
        } 
    }
    
    
    


}