<?php

use MVC\Controller;

class ControllersCuatrimestres extends Controller
{
    public function ObtenerCuatrimestres()
    {
        $model = $this->model('Cuatrimestres');
        $data_list = $model->Cuatrimestres();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCuatrimestresActivos()
    {
        $model = $this->model('Cuatrimestres');
        $data_list = $model->CuatrimestresActivos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCuatrimestresInactivos()
    {
        $model = $this->model('Cuatrimestres');
        $data_list = $model->CuatrimestresInactivos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCuatrimestre($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Cuatrimestres');
        $cuatrimestre = $model->Cuatrimestre($id);

        if ($cuatrimestre) {
            $this->response->sendStatus(200);
            $this->response->setContent($cuatrimestre);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'Cuatrimestre no encontrado']);
        }
    }

    public function CrearCuatrimestre()
    {
        $model = $this->model('Cuatrimestres');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_cuatrimestre'])) {
            $nombre_cuatrimestre = filter_var($data['nombre_cuatrimestre'], FILTER_SANITIZE_STRING);
            $inserted = $model->createCuatrimestre(['nombre_cuatrimestre' => $nombre_cuatrimestre]);

            if ($inserted) {
                echo json_encode(['message' => 'Cuatrimestre creado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al crear cuatrimestre.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de cuatrimestre son inválidos o incompletos.']);
        }
    }

    public function ActualizarCuatrimestre()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Cuatrimestres');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_cuatrimestre'])) {
            $nombre_cuatrimestre = filter_var($data['nombre_cuatrimestre'], FILTER_SANITIZE_STRING);

            $updated = $model->updateCuatrimestre($id, $nombre_cuatrimestre);

            if ($updated) {
                echo json_encode(['message' => 'Cuatrimestre actualizado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al actualizar cuatrimestre.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de cuatrimestre son inválidos o incompletos.']);
        }
    }

    public function DesactivarCuatrimestre()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Cuatrimestres');
        $updated = $model->updateActivo($id, 0);

        if ($updated) {
            echo json_encode(['message' => 'Cuatrimestre desactivado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al desactivar cuatrimestre.']);
        }
    }

    public function ActivarCuatrimestre()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Cuatrimestres');
        $updated = $model->updateActivo($id, 1);

        if ($updated) {
            echo json_encode(['message' => 'Cuatrimestre activado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al activar cuatrimestre.']);
        }
    }

    public function EliminarCuatrimestre($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Cuatrimestres');
        $deleted = $model->deleteCuatrimestre($id);

        if ($deleted) {
            echo json_encode(['message' => 'Cuatrimestre eliminado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar cuatrimestre.']);
        }
    }
}

?>
