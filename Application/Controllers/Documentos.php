<?php

use MVC\Controller;

class ControllersDocumentos extends Controller
{

    public function obtenerDocumentos()
    {

        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentos(1);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }



    public function obtenerDocumento($param) {

            $model = $this->model('Documentos');
            $result = $model->documentos($param['id']);

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
    } 
    
    public function obtenerDocumentosDesactivadas()
    {
        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->categorias(0);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearDocumento() {
        $model = $this->model('Documentos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        if ($data !== null && isset($data['titulo'], $data['fk_departamento'], $data['fk_categoria'], $data['fk_tipo_documento'], $data['fk_subproseso'])) {
            $nombre_categoria = filter_var($data['titulo'], FILTER_SANITIZE_STRING);
            $id = filter_var($data['fk_departamento'], FILTER_SANITIZE_NUMBER_INT);
            $id = filter_var($data['fk_categoria'], FILTER_SANITIZE_NUMBER_INT);
            $id = filter_var($data['fk_tipo_documento'], FILTER_SANITIZE_NUMBER_INT);


    
            $inserted = $model->insertCategoria([
                'nombre_categoria' => $nombre_categoria,

            ]);
    
            if ($inserted) {
                echo json_encode(['message' => 'Categoria guardada correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar categoria.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de categoria son inválidos o incompletos.']);
        }
    }
    

    
    public function eliminarCategoria($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Categorias');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarCategoria($id);
    
            // Preparar la respuesta
            if ($deleted) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'categoria eliminada correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo eliminar esta categoria.'
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

    public function desactivarCategoria($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Categorias');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Categoria desactivada correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar la categoria.'
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

    public function activarCategoria($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Categorias');
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
    

    public function actualizarCategoria($param) {
        $model = $this->model('Categorias');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        // Verificar si los datos son válidos
        if ($data !== null && isset($data['nombre_categoria'])) {
            $nombre_categoria = filter_var($data['nombre_categoria'], FILTER_SANITIZE_STRING);
            
            if (isset($param['id']) && $this->validId($param['id'])) {
                // Actualizar el área existente
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->updateCategoria(['id' => $id, 'nombre_categoria' => $nombre_categoria]);
        
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent([
                        'message' => 'categoria actualizada correctamente.'
                    ]);
                } else {
                    $this->response->sendStatus(500);
                    $this->response->setContent([
                        'message' => 'Error: No se pudo actualizar la categoria.'
                    ]);
                }
            } 
        } 
    }
    
    
    


}