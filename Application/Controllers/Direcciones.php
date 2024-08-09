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
            // Detectar sistema operativo
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\asset\\images\\direcciones\\";
            } else {
                $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/controlador_archivos/backend/asset/images/direcciones/";
            }
    
            // Sanitizar el nombre del archivo
            $file_name = preg_replace("/[^\p{L}\p{N}.]/u", "_", basename($logo["name"]));
            $target_file = $target_dir . $file_name;
    
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            $check = getimagesize($logo["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo json_encode(['message' => 'El archivo no es una imagen.']);
                $uploadOk = 0;
            }
    
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo json_encode(['message' => 'Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.']);
                $uploadOk = 0;
            }
    
            if ($uploadOk == 0) {
                echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
            } else {
                // Verificar permisos de escritura
                if (!is_writable($target_dir)) {
                    error_log("Error: El directorio de destino no tiene permisos de escritura.");
                    echo json_encode(['message' => 'Error: El directorio de destino no tiene permisos de escritura.']);
                    return;
                }
    
                // Intentar mover el archivo
                if (!move_uploaded_file($logo["tmp_name"], $target_file)) {
                    $error = error_get_last();
                    error_log("Error al mover el archivo: " . print_r($error, true));
                    
                    // Intentar copiar si mover falla
                    if (!copy($logo["tmp_name"], $target_file)) {
                        $error = error_get_last();
                        error_log("Error al copiar el archivo: " . print_r($error, true));
                        echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.', 'error' => $error]);
                        return;
                    } else {
                        unlink($logo["tmp_name"]);
                    }
                }
    
                if (file_exists($target_file)) {
                    // URL accesible desde el navegador (almacenada en la base de datos)
                    $logo_url = "http://localhost/controlador_archivos/backend/asset/images/direcciones/" . $file_name;
    
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
                    error_log("Error: El archivo no se movió a la ubicación deseada.");
                    echo json_encode(['message' => 'Error: El archivo no se movió a la ubicación deseada.']);
                }
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de dirección son inválidos o incompletos.']);
        }
    }

    public function ActualizarDireccion() {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);
    
        if (!$this->validId($id)) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }
    
        $model = $this->model('Direcciones');
        $nombre_direccion = isset($_POST['nombre_direccion']) ? filter_var($_POST['nombre_direccion'], FILTER_SANITIZE_STRING) : null;
        $logo = isset($_FILES['logo']) ? $_FILES['logo'] : null;
        $logo_url = null;
    
        if ($nombre_direccion) {
            // Detectar sistema operativo y establecer directorio base
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\asset\\images\\direcciones\\";
            } else {
                $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/controlador_archivos/backend/asset/images/direcciones/";
            }
    
            // Si se proporcionó un nuevo logo, manejar la actualización del archivo
            if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
                $file_name = preg_replace("/[^\p{L}\p{N}.]/u", "_", basename($logo["name"]));
                $target_file = $target_dir . $file_name;
    
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
                if (!in_array($fileType, $allowedFileTypes)) {
                    echo json_encode(['message' => 'Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.']);
                    $uploadOk = 0;
                }
    
                if ($uploadOk == 0) {
                    echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
                    return;
                } else {
                    if (move_uploaded_file($logo["tmp_name"], $target_file)) {
                        $logo_url = "http://localhost/controlador_archivos/backend/asset/images/direcciones/" . $file_name;
                    } else {
                        echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                        return;
                    }
                }
            }
    
            $data = [
                'id' => $id,
                'nombre_direccion' => $nombre_direccion,
                'logo_url' => $logo_url
            ];
    
            $updated = $model->updateDireccion($data);
    
            if ($updated) {
                echo json_encode(['message' => 'Dirección actualizada correctamente.', 'logo_url' => $logo_url]);
            } else {
                echo json_encode(['message' => 'Error al actualizar dirección.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de dirección son inválidos o incompletos.']);
        }
    }
    
    // Método validId para validar el ID
    private function validId($id) {
        return is_numeric($id) && $id > 0;
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
