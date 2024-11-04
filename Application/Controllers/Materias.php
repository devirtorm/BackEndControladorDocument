<?php

use MVC\Controller;

class ControllersMaterias extends Controller
{
    public function ObtenerMaterias()
    {
        $model = $this->model('Materias');
        $data_list = $model->Materias();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerMateriasActivas()
    {
        $model = $this->model('Materias');
        $data_list = $model->MateriasActivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerMateriasInactivas()
    {
        $model = $this->model('Materias');
        $data_list = $model->MateriasInactivas();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerMateria($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Materias');
        $materia = $model->Materia($id);

        if ($materia) {
            $this->response->sendStatus(200);
            $this->response->setContent($materia);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'Materia no encontrada']);
        }
    }

    public function CrearMateria()
    {
        $this->verifyToken(); // Verificar el token JWT
        
        error_log("--- CrearMateria() called ---");
        error_log("POST variables: " . print_r($_POST, true));
        error_log("FILES variables: " . print_r($_FILES, true));
    
        $model = $this->model('Materias');
        $nombre_materia = isset($_POST['nombre_materia']) ? filter_var($_POST['nombre_materia'], FILTER_SANITIZE_STRING) : null;
        $archivo_materia = isset($_FILES['archivo_materia']) ? $_FILES['archivo_materia'] : null;
        $fk_carrera = isset($_POST['fk_carrera']) ? filter_var($_POST['fk_carrera'], FILTER_VALIDATE_INT) : null;
        $fk_cuatrimestre = isset($_POST['fk_cuatrimestre']) ? filter_var($_POST['fk_cuatrimestre'], FILTER_VALIDATE_INT) : null;
    
        error_log("Nombre de la materia: " . $nombre_materia);
        error_log("Archivo de la materia: " . print_r($archivo_materia, true));
        error_log("FK Carrera: " . $fk_carrera);
        error_log("FK Cuatrimestre: " . $fk_cuatrimestre);
    
        if (!isset($_FILES['archivo_materia']) || $_FILES['archivo_materia']['error'] !== UPLOAD_ERR_OK) {
            error_log("File upload error: " . ($_FILES['archivo_materia']['error'] ?? 'No file uploaded'));
            echo json_encode(['message' => 'Error: Problema con la subida del archivo']);
            return;
        }
    
        if ($nombre_materia && $archivo_materia && $fk_carrera && $fk_cuatrimestre !== null) {
            // Detectar sistema operativo
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\asset\\document\\materias\\";
            } else {
                $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/controlador_archivos/backend/asset/document/materias/";
            }
            
            // Sanitizar el nombre del archivo
            $file_name = preg_replace("/[^\p{L}\p{N}.]/u", "_", basename($archivo_materia["name"]));
            $target_file = $target_dir . $file_name;
            
            $uploadOk = 1;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            if (!in_array($fileType, ['pdf', 'doc', 'docx', 'rtf', 'xlsx', 'xls'])) {
                echo json_encode(['message' => 'Solo se permiten archivos PDF, DOC, DOCX, RTF, XLSX y XLS.']);
                $uploadOk = 0;
            }            
    
            if ($uploadOk == 0) {
                echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
            } else {
                error_log("Intentando mover el archivo a: " . $target_file);
                error_log("Archivo temporal: " . $archivo_materia["tmp_name"]);
                error_log("Tamaño del archivo: " . $archivo_materia["size"]);
    
                // Verificar permisos de escritura
                if (!is_writable($target_dir)) {
                    error_log("Error: El directorio de destino no tiene permisos de escritura.");
                    echo json_encode(['message' => 'Error: El directorio de destino no tiene permisos de escritura.']);
                    return;
                }
    
                // Intentar mover el archivo
                if (!move_uploaded_file($archivo_materia["tmp_name"], $target_file)) {
                    $error = error_get_last();
                    error_log("Error al mover el archivo: " . print_r($error, true));
                    
                    // Intentar copiar si mover falla
                    if (!copy($archivo_materia["tmp_name"], $target_file)) {
                        $error = error_get_last();
                        error_log("Error al copiar el archivo: " . print_r($error, true));
                        echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.', 'error' => $error]);
                        return;
                    } else {
                        unlink($archivo_materia["tmp_name"]);
                    }
                }
    
                if (file_exists($target_file)) {
                    error_log("Archivo subido correctamente: " . $target_file);
                    $archivo_materia_url = "http://localhost/controlador_archivos/backend/asset/document/materias/" . $file_name;
    
                    $inserted = $model->createMateria([
                        'nombre_materia' => $nombre_materia,
                        'archivo_materia' => $archivo_materia_url,
                        'fk_carrera' => $fk_carrera,
                        'fk_cuatrimestre' => $fk_cuatrimestre
                    ]);
    
                    if ($inserted) {
                        echo json_encode(['message' => 'Materia creada correctamente.', 'archivo_materia_url' => $archivo_materia_url]);
                    } else {
                        echo json_encode(['message' => 'Error al crear materia.']);
                    }
                } else {
                    error_log("Error: El archivo no se movió a la ubicación deseada.");
                    echo json_encode(['message' => 'Error: El archivo no se movió a la ubicación deseada.']);
                }
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de materia son inválidos o incompletos.']);
        }
    }
    
    public function ActualizarMateria() {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);
    
        if (!$this->validId($id)) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }
    
        $model = $this->model('Materias');
        $nombre_materia = isset($_POST['nombre_materia']) ? filter_var($_POST['nombre_materia'], FILTER_SANITIZE_STRING) : null;
        $fk_carrera = isset($_POST['fk_carrera']) ? intval($_POST['fk_carrera']) : null;
        $fk_cuatrimestre = isset($_POST['fk_cuatrimestre']) ? intval($_POST['fk_cuatrimestre']) : null;
        $archivo_materia = isset($_FILES['archivo_materia']) ? $_FILES['archivo_materia'] : null;
        $archivo_url = null;
    
        if ($nombre_materia && $fk_carrera && $fk_cuatrimestre) {
            // Detectar sistema operativo y establecer directorio base
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\asset\\document\\materias\\";
            } else {
                $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/controlador_archivos/backend/asset/document/materias/";
            }

            // Si se proporcionó un nuevo archivo, manejar la actualización del archivo
            if ($archivo_materia && $archivo_materia['error'] === UPLOAD_ERR_OK) {
                $file_name = preg_replace("/[^\p{L}\p{N}.]/u", "_", basename($archivo_materia["name"]));
                $target_file = $target_dir . $file_name;
    
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                $allowedFileTypes = ['pdf', 'doc', 'docx', 'rtf', 'xlsx', 'xls'];
    
                if (!in_array($fileType, $allowedFileTypes)) {
                    echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX, RTF, XLSX Y XLS.']);
                    $uploadOk = 0;
                }
    
                if ($uploadOk == 0) {
                    echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
                    return;
                } else {
                    if (move_uploaded_file($archivo_materia["tmp_name"], $target_file)) {
                        $archivo_url = "http://localhost/controlador_archivos/backend/asset/document/materias/" . $file_name;
                    } else {
                        echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                        return;
                    }
                }
            }
    
            $data = [
                'id' => $id,
                'nombre_materia' => $nombre_materia,
                'archivo_url' => $archivo_url,
                'fk_carrera' => $fk_carrera,
                'fk_cuatrimestre' => $fk_cuatrimestre
            ];
    
            $updated = $model->updateMateria($data);
    
            if ($updated) {
                echo json_encode(['message' => 'Materia actualizada correctamente.', 'archivo_url' => $archivo_url]);
            } else {
                echo json_encode(['message' => 'Error al actualizar materia.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de materia son inválidos o incompletos.']);
        }
    }
    
    // Método validId para validar el ID
    private function validId($id) {
        return is_numeric($id) && $id > 0;
    }    

    public function DesactivarMateria()
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Materias');
        $updated = $model->updateActivo($id, 0);

        if ($updated) {
            echo json_encode(['message' => 'Materia desactivada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al desactivar materia.']);
        }
    }

    public function ActivarMateria()
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Materias');
        $updated = $model->updateActivo($id, 1);

        if ($updated) {
            echo json_encode(['message' => 'Materia activada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al activar materia.']);
        }
    }

    public function EliminarMateria($id)
    {
        $this->verifyToken(); // Verificar el token JWT

        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Materias');
        $deleted = $model->deleteMateria($id);

        if ($deleted) {
            echo json_encode(['message' => 'Materia eliminada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar materia.']);
        }
    }
}

?>
