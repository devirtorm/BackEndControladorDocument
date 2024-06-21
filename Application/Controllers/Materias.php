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
        $model = $this->model('Materias');
        $nombre_materia = isset($_POST['nombre_materia']) ? filter_var($_POST['nombre_materia'], FILTER_SANITIZE_STRING) : null;
        $archivo_materia = isset($_FILES['archivo_materia']) ? $_FILES['archivo_materia'] : null;
        $fk_cuatrimestre = isset($_POST['fk_cuatrimestre']) ? filter_var($_POST['fk_cuatrimestre'], FILTER_VALIDATE_INT) : null;

        // Debug: Imprimir los datos recibidos
        error_log("Nombre de la materia: " . $nombre_materia);
        error_log("Archivo de la materia: " . print_r($archivo_materia, true));
        error_log("FK Cuatrimestre: " . $fk_cuatrimestre);

        if ($nombre_materia && $archivo_materia && $fk_cuatrimestre !== null) {
            $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\document\\";
            $target_file = $target_dir . basename($archivo_materia["name"]);
            $uploadOk = 1;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!in_array($fileType, ['pdf', 'doc', 'docx'])) {
                echo json_encode(['message' => 'Solo se permiten archivos PDF, DOC y DOCX.']);
                $uploadOk = 0;
            }

            if (file_exists($target_file)) {
                echo json_encode(['message' => 'Lo siento, el archivo ya existe.']);
                $uploadOk = 0;
            }

            if ($archivo_materia["size"] > 5000000) {
                echo json_encode(['message' => 'El archivo es demasiado grande.']);
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
            } else {
                if (move_uploaded_file($archivo_materia["tmp_name"], $target_file)) {
                    $archivo_materia_url = "http://localhost/controlador_archivos/backend/document/" . basename($archivo_materia["name"]);

                    $inserted = $model->createMateria([
                        'nombre_materia' => $nombre_materia,
                        'archivo_materia' => $archivo_materia_url,
                        'fk_cuatrimestre' => $fk_cuatrimestre
                    ]);

                    if ($inserted) {
                        echo json_encode(['message' => 'Materia creada correctamente.', 'archivo_materia_url' => $archivo_materia_url]);
                    } else {
                        echo json_encode(['message' => 'Error al crear materia.']);
                    }
                } else {
                    echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                }
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de materia son inválidos o incompletos.']);
        }
    }

    public function ActualizarMateria()
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        $model = $this->model('Materias');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['nombre_materia']) && isset($data['archivo_materia']) && isset($data['fk_cuatrimestre'])) {
            $nombre_materia = filter_var($data['nombre_materia'], FILTER_SANITIZE_STRING);
            $archivo_materia = filter_var($data['archivo_materia'], FILTER_SANITIZE_STRING);
            $fk_cuatrimestre = intval($data['fk_cuatrimestre']);

            $updated = $model->updateMateria($id, $nombre_materia, $archivo_materia, $fk_cuatrimestre);

            if ($updated) {
                echo json_encode(['message' => 'Materia actualizada correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al actualizar materia.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de materia son inválidos o incompletos.']);
        }
    }

    public function DesactivarMateria()
    {
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
