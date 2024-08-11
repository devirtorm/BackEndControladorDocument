<?php

use MVC\Controller;

class ControllersValores extends Controller
{

    public function valores()
    {
        // Connect to database
        $model = $this->model('Valores');
    
        $data_list = $model->getValores(1);
    
        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerOff()
    {
        // Connect to database
        $model = $this->model('Valores');
    
        $data_list = $model->getValor(0);
    
        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }
    public function desactivarValor($param) {
        if (isset($param['id']) && $this->validId($param['id'])) {
            $model = $this->model('Valores');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);
    
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Valor desactivado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar el Valor.'
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
    public function activarValor($param) {
        if (isset($param['id']) && $this->validId($param['id'])) {
            $model = $this->model('Valores');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 1);
    
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'valor activado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo activar el valor.'
                ]);
            }
        } else {
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }
    public function eliminarValor($param) {
        if (isset($param['id']) && $this->validId($param['id'])) {
            $model = $this->model('Valores');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarObjetivo($id);
    
            if ($deleted) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'valor eliminado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo eliminar el valor.'
                ]);
            }
        } else {
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }
  
    public function actualizarValores() {
        $model = $this->model('Valores');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        // Verificar si los datos son válidos
        if ($data !== null && isset($data['nombre']) && isset($data['descripcion']) && isset($data['icono']) && isset($data['id'])) {
            $nombre = filter_var($data['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $descripcion = filter_var($data['descripcion'], FILTER_SANITIZE_SPECIAL_CHARS);
            $icono = filter_var($data['icono'], FILTER_SANITIZE_SPECIAL_CHARS);
            $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    
            // Actualizar el área existente
            $updated = $model->updateUsuario([
                'id' => $id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'icono' => $icono
            ]);
    
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Usuario actualizado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(500);
                $this->response->setContent([
                    'message' => 'Error: No se pudo actualizar el usuario.'
                ]);
            }
        } else {
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Datos de entrada inválidos.'
            ]);
        }
    }
    
public function crearValor()
{
    $model = $this->model('Valores');
    $json_data = file_get_contents('php://input');
    error_log("JSON Data: " . $json_data);
    $data = json_decode($json_data, true);

    if ($data !== null && isset($data['nombre']) && isset($data['descripcion']) && isset($data['icono'])) {
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $descripcion = filter_var($data['descripcion'], FILTER_SANITIZE_SPECIAL_CHARS);
        $icono = filter_var($data['icono'], FILTER_SANITIZE_SPECIAL_CHARS);

        $inserted = $model->insertValor([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'icono' => $icono
        ]);

        if ($inserted) {
            echo json_encode(['message' => 'Valor guardado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al guardar el valor.']);
        }
    } else {
        echo json_encode(['message' => 'Error: Los datos del valor son inválidos o incompletos.']);
    }
}
    }
