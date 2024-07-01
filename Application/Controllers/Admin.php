<?php

use MVC\Controller;

class ControllersAdmin extends Controller
{

    public function admin()
    {

        // Connect to database
        $model = $this->model('Admin');

        $data_list = $model->getAllAdmin();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }


        public function obtenerPersona($param) {
            $model = $this->model('Personas');
            $result = $model->obtenerPersonaPorId($param['id']);
    
            $this->response->sendStatus(200);
            $this->response->setContent($result);
        }
    
        public function adminDesactivados() {
            $model = $this->model('Admin');
            $data_list = $model->personasDesactivadas();
    
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        }
    
        public function crearAdmin() {
            $model = $this->model('Admin');
            $json_data = file_get_contents('php://input');
            error_log("JSON Data: " . $json_data);
            $data = json_decode($json_data, true);
    
            if ($data !== null && isset($data['contrasenia']) && isset($data['fk_persona'])) {
                $inserted = $model->insertarPersona($data);
    
                if ($inserted) {
                    echo json_encode(['message' => 'Persona guardada correctamente.']);
                } else {
                    echo json_encode(['message' => 'Error al guardar Persona.']);
                }
            } else {
                echo json_encode(['message' => 'Error: Los datos de la persona son inválidos o incompletos.']);
            }
        }
    
        public function eliminarPersona($param) {
            if (isset($param['id']) && $this->validId($param['id'])) {
                $model = $this->model('Personas');
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $deleted = $model->eliminarPersona($id);
    
                if ($deleted) {
                    $this->response->sendStatus(200);
                    $this->response->setContent(['message' => 'Persona eliminada correctamente.']);
                } else {
                    $this->response->sendStatus(200);
                    $this->response->setContent(['message' => 'Error: No se pudo eliminar la persona.']);
                }
            } else {
                $this->response->sendStatus(400);
                $this->response->setContent(['message' => 'Invalid ID or ID is missing.']);
            }
        }
    
        private function validId($id) {
            return filter_var($id, FILTER_VALIDATE_INT) !== false && $id > 0;
        }
    
        public function desactivarPersona($param) {
            if (isset($param['id']) && $this->validId($param['id'])) {
                $model = $this->model('Admin');
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->actualizarActivo($id, 0);
    
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent(['message' => 'Persona desactivada correctamente.']);
                } else {
                    $this->response->sendStatus(200);
                    $this->response->setContent(['message' => 'Error: No se pudo desactivar la persona.']);
                }
            } else {
                $this->response->sendStatus(400);
                $this->response->setContent(['message' => 'Invalid ID or ID is missing.']);
            }
        }
    
        public function activarAdmin($param) {
            if (isset($param['id']) && $this->validId($param['id'])) {
                $model = $this->model('Admin');
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->actualizarActivo($id, 1);
    
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent(['message' => 'Persona activada correctamente.']);
                } else {
                    $this->response->sendStatus(200);
                    $this->response->setContent(['message' => 'Error: No se pudo activar la persona.']);
                }
            } else {
                $this->response->sendStatus(400);
                $this->response->setContent(['message' => 'Invalid ID or ID is missing.']);
            }
        }
    
        public function actualizarPersona($param) {
            $model = $this->model('Personas');
            $json_data = file_get_contents('php://input');
            error_log("JSON Data: " . $json_data);
            $data = json_decode($json_data, true);
    
            if ($data !== null && isset($data['nombres']) && isset($data['primer_apellido']) && isset($data['telefono']) && isset($data['correo']) && isset($data['fk_rol'])) {
                if (isset($param['id']) && $this->validId($param['id'])) {
                    $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                    $data['id_persona'] = $id;
                    $updated = $model->updatePersona($data);
    
                    if ($updated) {
                        $this->response->sendStatus(200);
                        $this->response->setContent(['message' => 'Persona actualizada correctamente.']);
                    } else {
                        $this->response->sendStatus(500);
                        $this->response->setContent(['message' => 'Error: No se pudo actualizar la persona.']);
                    }
                }
            }
        }
    }