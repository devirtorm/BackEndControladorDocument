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



    public function obtenerDocumento($param)
    {

        $model = $this->model('Documentos');
        $result = $model->documentos($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($result);
    }

    public function obtenerDocumentosDesactivados()
    {
        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentos(0);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }


    public function crearDocumento()
    {

        $model = $this->model('Documentos');
        $archivo = isset($_FILES['archivo']) ? $_FILES['archivo'] : null;
        $archivo_url = null;

        if (isset($_POST['titulo'], $_POST['fk_departamento'], $_POST['fk_categoria'], $_POST['fk_tipo_documento'], $_POST['fk_subproceso'])) {
            $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
            $fk_departamento = filter_var($_POST['fk_departamento'], FILTER_SANITIZE_NUMBER_INT);
            $fk_categoria = filter_var($_POST['fk_categoria'], FILTER_SANITIZE_NUMBER_INT);
            $fk_tipo_documento = filter_var($_POST['fk_tipo_documento'], FILTER_SANITIZE_NUMBER_INT);
            $fk_subproceso = filter_var($_POST['fk_subproceso'], FILTER_SANITIZE_NUMBER_INT);

            if ($archivo) {
                $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\documents\\";
                $target_file = $target_dir . basename($archivo["name"]);
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if file already exists
                if (file_exists($target_file)) {
                    echo json_encode(['message' => 'Lo siento, el archivo ya existe.']);
                    $uploadOk = 0;
                }

                // Check file size (5MB max)
                if ($archivo["size"] > 5000000) {
                    echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx" && $fileType != "txt") {
                    echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX y TXT.']);
                    $uploadOk = 0;
                }

                if ($uploadOk == 0) {
                    echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
                    return;
                } else {
                    if (move_uploaded_file($archivo["tmp_name"], $target_file)) {
                        // URL accesible desde el navegador (almacenada en la base de datos)
                        $archivo_url = "http://localhost/controlador_archivos/backend/documents/" . basename($archivo["name"]);
                    } else {
                        echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                        return;
                    }
                }
            }

            $inserted = $model->insertDocumento([
                'titulo' => $titulo,
                'fk_departamento' => $fk_departamento,
                'fk_categoria' => $fk_categoria,
                'fk_tipo_documento' => $fk_tipo_documento,
                'fk_subproceso' => $fk_subproceso,
                'archivo_url' => $archivo_url // Guardar la URL del archivo en la base de datos
            ]);

            if ($inserted) {
                echo json_encode(['message' => 'Documento guardado correctamente.', 'archivo_url' => $archivo_url]);
            } else {
                echo json_encode(['message' => 'Error al guardar documento.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de categoria son inválidos o incompletos.']);
        }
    }

    public function actualizarDocumento($param) {
        $model = $this->model('Documentos');
        $archivo = isset($_FILES['archivo']) ? $_FILES['archivo'] : null;
        $archivo_url = null;
    
        if (isset($param['id']) && $this->validId($param['id'])) {
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
    
            if (isset($_POST['titulo'])) {
                $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
                $fk_departamento = filter_var($_POST['fk_departamento'], FILTER_SANITIZE_NUMBER_INT);
                $fk_categoria = filter_var($_POST['fk_categoria'], FILTER_SANITIZE_NUMBER_INT);
                $fk_tipo_documento = filter_var($_POST['fk_tipo_documento'], FILTER_SANITIZE_NUMBER_INT);
                $fk_subproceso = filter_var($_POST['fk_subproceso'], FILTER_SANITIZE_NUMBER_INT);
    
                if ($archivo) {
                    $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\documents\\";
                    $target_file = $target_dir . basename($archivo["name"]);
                    $uploadOk = 1;
                    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                    // Check if file already exists
                    if (file_exists($target_file)) {
                        echo json_encode(['message' => 'Lo siento, el archivo ya existe.']);
                        $uploadOk = 0;
                    }
    
                    // Check file size (5MB max)
                    if ($archivo["size"] > 5000000) {
                        echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
                        $uploadOk = 0;
                    }
    
                    // Allow certain file formats
                    if (!in_array($fileType, ['pdf', 'doc', 'docx', 'txt'])) {
                        echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX y TXT.']);
                        $uploadOk = 0;
                    }
    
                    if ($uploadOk == 0) {
                        echo json_encode(['message' => 'Lo siento, tu archivo no fue subido.']);
                        return;
                    } else {
                        if (move_uploaded_file($archivo["tmp_name"], $target_file)) {
                            $archivo_url = "http://localhost/controlador_archivos/backend/documents/" . basename($archivo["name"]);
                        } else {
                            echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                            return;
                        }
                    }
                }
    
                $data = [
                    'id' => $id,
                    'titulo' => $titulo,
                    'fk_departamento' => $fk_departamento,
                    'fk_categoria' => $fk_categoria,
                    'fk_tipo_documento' => $fk_tipo_documento,
                    'fk_subproceso' => $fk_subproceso,
                    'archivo_url' => $archivo_url
                ];
    
                $updated = $model->updateDocumento($data);
    
                if ($updated) {
                    echo json_encode(['message' => 'Documento actualizado correctamente.', 'archivo_url' => $archivo_url]);
                } else {
                    echo json_encode(['message' => 'Error: No se pudo actualizar el documento.']);
                }
            } else {
                echo json_encode(['message' => 'Error: Los datos del documento son inválidos o incompletos.']);
            }
        } else {
            echo json_encode(['message' => 'Error: ID de documento inválido.']);
        }
    }
    




    public function eliminarDocumento($param)
    {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {

            $model = $this->model('Documentos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarDocumento($id);

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
    private function validId($id)
    {
        return filter_var($id, FILTER_VALIDATE_INT) !== false && $id > 0;
    }

    public function desactivarDocumento($param)
    {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {

            $model = $this->model('Documentos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);

            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Documento desactivada correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar este documento.'
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

    public function activarDocumento($param)
    {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {

            $model = $this->model('Documentos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 1);

            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Documento activado correctamente.'

                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo activar el documento.'
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



}
