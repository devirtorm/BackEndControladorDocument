<?php

use MVC\Controller;

class ControllersMacroprocesos extends Controller
{
    public function ObtenerMacroprocesos()
    {
        $model = $this->model('Macroprocesos');
        $data_list = $model->Macroprocesos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerMacroprocesosActivos()
    {
        $model = $this->model('Macroprocesos');
        $data_list = $model->MacroprocesosActivos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerMacroprocesosInactivos()
    {
        $model = $this->model('Macroprocesos');
        $data_list = $model->MacroprocesosInactivos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerMacroproceso($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Macroprocesos');
        $macroproceso = $model->Macroproceso($id);

        if ($macroproceso) {
            $this->response->sendStatus(200);
            $this->response->setContent($macroproceso);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'Macroproceso no encontrado']);
        }
    }

    public function CrearMacroproceso()
    {
        $model = $this->model('Macroprocesos');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['macroproceso'], $data['proposito'])) {
            $macroproceso = filter_var($data['macroproceso'], FILTER_SANITIZE_STRING);
            $proposito = filter_var($data['proposito'], FILTER_SANITIZE_STRING);
            $inserted = $model->createMacroproceso(['macroproceso' => $macroproceso, 'proposito' => $proposito]);

            if ($inserted) {
                echo json_encode(['message' => 'Macroproceso creado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al crear macroproceso.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos del macroproceso son inválidos o incompletos.']);
        }
    }

    public function ActualizarMacroproceso()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Macroprocesos');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['macroproceso'], $data['proposito'])) {
            $macroproceso = filter_var($data['macroproceso'], FILTER_SANITIZE_STRING);
            $proposito = filter_var($data['proposito'], FILTER_SANITIZE_STRING);

            $updated = $model->updateMacroproceso($id, $macroproceso, $proposito);

            if ($updated) {
                echo json_encode(['message' => 'Macroproceso actualizado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al actualizar macroproceso.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos del macroproceso son inválidos o incompletos.']);
        }
    }

    public function DesactivarMacroproceso()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Macroprocesos');
        $updated = $model->updateActivo($id, 0);

        if ($updated) {
            echo json_encode(['message' => 'Macroproceso desactivado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al desactivar macroproceso.']);
        }
    }

    public function ActivarMacroproceso()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Macroprocesos');
        $updated = $model->updateActivo($id, 1);

        if ($updated) {
            echo json_encode(['message' => 'Macroproceso activado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al activar macroproceso.']);
        }
    }

    public function EliminarMacroproceso($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Macroprocesos');
        $deleted = $model->deleteMacroproceso($id);

        if ($deleted) {
            echo json_encode(['message' => 'Macroproceso eliminado correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar macroproceso.']);
        }
    }
}

?>