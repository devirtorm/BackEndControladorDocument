<?php

use MVC\Controller;

class ControllersDirecciones extends Controller
{
    public function ObtenerDirecciones() //funciona
    {
        $model = $this->model('Direcciones');
        $data_list = $model->Direcciones();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }
    
    public function ObtenerDireccionesActivas() //funciona
    {
        $model = $this->model('Direcciones');
        $data_list = $model->DireccionesActivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerDireccionesInactivas() //funciona
    {
        $model = $this->model('Direcciones');
        $data_list = $model->DireccionesInactivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerDireccion($id) //funciona
    {
        // Obtener el último segmento de la URL que corresponde al ID
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);

        // Convertir $id a entero
        $id = intval($id);

        // Verificar si se proporcionó un ID válido
        if ($id === 0) {
            // Manejar el caso en que no se proporciona un ID válido
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        // Conectar a la base de datos
        $model = $this->model('Direcciones');

        // Obtener dirección por ID
        $direccion = $model->Direccion($id);

        // Enviar respuesta
        if ($direccion) {
            $this->response->sendStatus(200);
            $this->response->setContent($direccion);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'Dirección no encontrada']);
        }
    }

    public function CrearDireccion() //funciona
    {
        $model = $this->model('Direcciones');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_direccion'])) {
            $nombre_direccion = filter_var($data['nombre_direccion'], FILTER_SANITIZE_STRING);
            $inserted = $model->createDireccion(['nombre_direccion' => $nombre_direccion]);

            if ($inserted) {
                echo json_encode(['message' => 'Dirección creada correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al crear dirección.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de dirección son inválidos o incompletos.']);
        }
    }

    public function ActualizarDireccion() { //funciona
        // Obtener el último segmento de la URL que corresponde al ID
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
    
        // Convertir $id a entero
        $id = intval($id);
    
        // Verificar si se proporcionó un ID válido
        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }
    
        $model = $this->model('Direcciones');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
    
        if ($data !== null && isset($data['nombre_direccion'])) {
            $nombre_direccion = filter_var($data['nombre_direccion'], FILTER_SANITIZE_STRING);
    
            $updated = $model->updateDireccion($id, $nombre_direccion);
    
            if ($updated) {
                echo json_encode(['message' => 'Nombre de dirección actualizado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al actualizar nombre de dirección.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de dirección son inválidos o incompletos.']);
        }
    }

    public function DesactivarDireccion() {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);
    
        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }
    
        $model = $this->model('Direcciones');
        $updated = $model->updateActivo($id, 0);
    
        if ($updated) {
            echo json_encode(['message' => 'Dirección desactivada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al desactivar dirección.']);
        }
    }
    
    public function ActivarDireccion() {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);
    
        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }
    
        $model = $this->model('Direcciones');
        $updated = $model->updateActivo($id, 1);
    
        if ($updated) {
            echo json_encode(['message' => 'Dirección activada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al activar dirección.']);
        }
    }

    public function EliminarDireccion($id) //funciona
    {
        // Obtener el último segmento de la URL que corresponde al ID
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);

        // Convertir $id a entero
        $id = intval($id);

        // Verificar si se proporcionó un ID válido
        if ($id === 0) {
            // Manejar el caso en que no se proporciona un ID válido
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Direcciones');
        $deleted = $model->deleteDireccion($id);

        if ($deleted) {
            echo json_encode(['message' => 'Dirección eliminada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar dirección.']);
        }
    }

}

?>
