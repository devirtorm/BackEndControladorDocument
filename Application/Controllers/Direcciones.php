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

    public function CrearDireccion() {
        $model = $this->model('Direcciones');
        $nombre_direccion = isset($_POST['nombre_direccion']) ? filter_var($_POST['nombre_direccion'], FILTER_SANITIZE_STRING) : null;
        $logo = isset($_FILES['logo']) ? $_FILES['logo'] : null;
    
        // Debug: Imprimir los datos recibidos
        error_log("Nombre de la dirección: " . $nombre_direccion);
        error_log("Archivo de logo: " . print_r($logo, true));
    
        if ($nombre_direccion && $logo) {
            // Ruta donde se almacenará físicamente la imagen
            $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\images\\";
            $target_file = $target_dir . basename($logo["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            $check = getimagesize($logo["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo json_encode(['message' => 'El archivo no es una imagen.']);
                $uploadOk = 0;
            }
    
            if (file_exists($target_file)) {
                echo json_encode(['message' => 'Lo siento, el archivo ya existe.']);
                $uploadOk = 0;
            }
    
            if ($logo["size"] > 500000) {
                echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
                $uploadOk = 0;
            }
    
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo json_encode(['message' => 'Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.']);
                $uploadOk = 0;
            }
    
            if ($uploadOk == 0) {
                echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
            } else {
                if (move_uploaded_file($logo["tmp_name"], $target_file)) {
                    // URL accesible desde el navegador (almacenada en la base de datos)
                    $logo_url = "http://localhost/controlador_archivos/backend/images/" . basename($logo["name"]);
    
                    $inserted = $model->createDireccion([
                        'nombre_direccion' => $nombre_direccion,
                        'logo' => $logo_url
                    ]);
    
                    if ($inserted) {
                        echo json_encode(['message' => 'Dirección creada correctamente.', 'logo_url' => $logo_url]);
                    } else {
                        echo json_encode(['message' => 'Error al crear dirección.']);
                    }
                } else {
                    echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                }
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
