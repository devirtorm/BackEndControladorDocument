<?php

use MVC\Controller;

class ControllersCarreras extends Controller
{
    public function ObtenerCarreras()
    {
        $model = $this->model('Carreras');
        $data_list = $model->Carreras();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarrerasActivas()
    {
        $model = $this->model('Carreras');
        $data_list = $model->CarrerasActivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarrerasInactivas()
    {
        $model = $this->model('Carreras');
        $data_list = $model->CarrerasInactivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarrera($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Carreras');
        $carrera = $model->Carrera($id);

        if ($carrera) {
            $this->response->sendStatus(200);
            $this->response->setContent($carrera);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'Carrera no encontrada']);
        }
    }

    public function ObtenerCarrerasPorDireccion($fk_direccion)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $fk_direccion = end($segments);
        $fk_direccion = intval($fk_direccion);

        if ($fk_direccion === 0) {
            echo json_encode(['message' => 'Error: ID de dirección inválido.']);
            return;
        }

        $model = $this->model('Carreras');
        $carreras = $model->CarrerasPorDireccion($fk_direccion);

        if (!empty($carreras)) {
            $this->response->sendStatus(200);
            $this->response->setContent($carreras);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'No se encontraron carreras para esta dirección']);
        }
    }

    public function ObtenerCarrerasdeMenosdeDosDocumentosActivas()
    {
        $model = $this->model('Carreras');
        $data_list = $model->CarrerasdeMenosdeDosDocumentosActivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function CrearCarrera()
    {
        $this->verifyToken(); // Verificar el token JWT

        $model = $this->model('Carreras');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_carrera']) && isset($data['fk_direccion'])) {
            $nombre_carrera = filter_var($data['nombre_carrera'], FILTER_SANITIZE_STRING);
            $fk_direccion = filter_var($data['fk_direccion'], FILTER_VALIDATE_INT);
            $inserted = $model->createCarrera(['nombre_carrera' => $nombre_carrera, 'fk_direccion' => $fk_direccion]);

            if ($inserted) {
                echo json_encode(['message' => 'Carrera creada correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al crear carrera.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de carrera son inválidos o incompletos.']);
        }
    }

    public function ActualizarCarrera()
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Carreras');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_carrera']) && isset($data['fk_direccion'])) {
            $nombre_carrera = filter_var($data['nombre_carrera'], FILTER_SANITIZE_STRING);
            $fk_direccion = intval($data['fk_direccion']);

            $updated = $model->updateCarrera($id, $nombre_carrera, $fk_direccion);

            if ($updated) {
                echo json_encode(['message' => 'Carrera actualizada correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al actualizar carrera.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de carrera son inválidos o incompletos.']);
        }
    }


    public function DesactivarCarrera()
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Carreras');
        $updated = $model->updateActivo($id, 0);

        if ($updated) {
            echo json_encode(['message' => 'Carrera desactivada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al desactivar carrera.']);
        }
    }

    public function ActivarCarrera()
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Carreras');
        $updated = $model->updateActivo($id, 1);

        if ($updated) {
            echo json_encode(['message' => 'Carrera activada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al activar carrera.']);
        }
    }

    public function EliminarCarrera($id)
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Carreras');
        $deleted = $model->deleteCarrera($id);

        if ($deleted) {
            echo json_encode(['message' => 'Carrera eliminada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar carrera.']);
        }
    }
}

?>
