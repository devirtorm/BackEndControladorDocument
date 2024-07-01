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

    public function obtenerDocumentosDesactivados()
    {

        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentos(0);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }



    public function obtenerDocumento($param)
    {

        $model = $this->model('Documentos');
        $result = $model->documento($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($result);
    }


    public function crearDocumento()
    {
        $model = $this->model('Documentos');
        $archivo = isset($_FILES['archivo']) ? $_FILES['archivo'] : null;
        $archivo_url = null;
    
        if (
            isset($_POST['titulo'], $_POST['fk_departamento'], $_POST['fk_categoria'], $_POST['fk_tipo_documento'],
            $_POST['fk_subproceso'], $_POST['num_revision'], $_POST['fecha_emision'])
        ) {
            $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
            $fk_departamento = filter_var($_POST['fk_departamento'], FILTER_SANITIZE_NUMBER_INT);
            $fk_categoria = filter_var($_POST['fk_categoria'], FILTER_SANITIZE_NUMBER_INT);
            $fk_tipo_documento = filter_var($_POST['fk_tipo_documento'], FILTER_SANITIZE_NUMBER_INT);
            $fk_subproceso = filter_var($_POST['fk_subproceso'], FILTER_SANITIZE_NUMBER_INT);
            $fecha_emision = filter_var($_POST['fecha_emision'], FILTER_SANITIZE_STRING);
            $num_revision = filter_var($_POST['num_revision'], FILTER_SANITIZE_NUMBER_INT);
    
            if ($archivo) {
                $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\documents\\";
                $original_name = pathinfo($archivo["name"], PATHINFO_FILENAME);
                $file_extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
                $target_file = $target_dir . basename($archivo["name"]);
                $uploadOk = 1;
    
                // Check if file already exists, generate unique filename if needed
                if (file_exists($target_file)) {
                    $increment = 1;
                    do {
                        $new_filename = "{$original_name}{$increment}.{$file_extension}";
                        $target_file = $target_dir . $new_filename;
                        $increment++;
                    } while (file_exists($target_file));
                }
    
                // Check file size (5MB max)
                if ($archivo["size"] > 5000000) {
                    echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
                    return;
                }
    
                // Allow certain file formats
                $allowed_extensions = ['pdf', 'doc', 'docx', 'xlsx'];
                if (!in_array($file_extension, $allowed_extensions)) {
                    echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX y XLSX.']);
                    return;
                }
    
                // Upload file
                if (move_uploaded_file($archivo["tmp_name"], $target_file)) {
                    $archivo_url = "http://localhost/controlador_archivos/backend/documents/" . basename($target_file);
                } else {
                    echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                    return;
                }
            }
    
            $inserted = $model->insertDocumento([
                'titulo' => $titulo,
                'fk_departamento' => $fk_departamento,
                'fk_categoria' => $fk_categoria,
                'fk_tipo_documento' => $fk_tipo_documento,
                'fk_subproceso' => $fk_subproceso,
                'archivo_url' => $archivo_url,
                'fecha_emision' => $fecha_emision,
                'num_revision' => $num_revision,
            ]);
    
            if ($inserted) {
                echo json_encode(['message' => 'Documento guardado correctamente.', 'archivo_url' => $archivo_url]);
            } else {
                echo json_encode(['message' => 'Error al guardar documento.', 'error' => $inserted]);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de documento son inválidos o incompletos.']);
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
                $fecha_emision = filter_var($_POST['fecha_emision'], FILTER_SANITIZE_STRING);
                $num_revision = filter_var($_POST['num_revision'], FILTER_SANITIZE_NUMBER_INT);
    
                if ($archivo) {
                    $target_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\documents\\";
                    $original_name = pathinfo($archivo["name"], PATHINFO_FILENAME);
                    $file_extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
                    $target_file = $target_dir . basename($archivo["name"]);
                    $uploadOk = 1;
    
                    // Check if file already exists, generate unique filename if needed
                    if (file_exists($target_file)) {
                        $increment = 1;
                        do {
                            $new_filename = "{$original_name}{$increment}.{$file_extension}";
                            $target_file = $target_dir . $new_filename;
                            $increment++;
                        } while (file_exists($target_file));
                    }
    
                    // Check file size (5MB max)
                    if ($archivo["size"] > 5000000) {
                        echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
                        return;
                    }
    
                    // Allow certain file formats
                    $allowed_extensions = ['pdf', 'doc', 'docx', 'xlsx'];
                    if (!in_array($file_extension, $allowed_extensions)) {
                        echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX y XLSX.']);
                        return;
                    }
    
                    // Upload file
                    if (move_uploaded_file($archivo["tmp_name"], $target_file)) {
                        $archivo_url = "http://localhost/controlador_archivos/backend/documents/" . basename($target_file);
                    } else {
                        echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.']);
                        return;
                    }
                }
    
                $data = [
                    'id' => $id,
                    'titulo' => $titulo,
                    'fk_departamento' => $fk_departamento,
                    'fk_categoria' => $fk_categoria,
                    'fk_tipo_documento' => $fk_tipo_documento,
                    'fk_subproceso' => $fk_subproceso,
                    'archivo_url' => $archivo_url,
                    'fecha_emision' => $fecha_emision,
                    'num_revision' => $num_revision,
                ];
    
                $updated = $model->updateDocumento($data);
    
                if ($updated) {
                    echo json_encode(['message' => 'Documento actualizado correctamente.', 'archivo_url' => $archivo_url]);
                } else {
                    echo json_encode(['message' => 'Error: No se pudo actualizar el documento.', 'error' => $updated]);
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


    public function obtener()
    {
        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentProcesosEspecificos();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function buscar()
    {
        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentosBuscador();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }



}
