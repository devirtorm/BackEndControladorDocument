<?php

use MVC\Controller;

class ControllersDocumentos extends Controller
{

    public function obtenerDocumentos($param)
    {

        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentos(1,$param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function obtenerDocumentosDesactivados($param)
    {

        // Connect to database
        $model = $this->model('Documentos');

        $data_list = $model->documentos(0,$param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function obtenerDocumentoByProceso($param)
    {

        $model = $this->model('Documentos');
        $result = $model->getDocumentoByProcesoId($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($result);
    }



    public function obtenerDocumento($param)
    {

        $model = $this->model('Subprocesos');
        $result = $model->documento($param['id']);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($result);
    }

    

    public function crearDocumento()
    {
        error_log("--- crearDocumento() called ---");
        error_log("POST variables: " . print_r($_POST, true));
        error_log("FILES variables: " . print_r($_FILES, true));
    
        $model = $this->model('Documentos');
        $archivo_url = null;
    
        error_log("PHP Configuration:");
        error_log("upload_max_filesize: " . ini_get('upload_max_filesize'));
        error_log("post_max_size: " . ini_get('post_max_size'));
        error_log("max_execution_time: " . ini_get('max_execution_time'));
    
        $required_fields = [
            'titulo', 'fk_departamento', 'nombre_macro_proceso', 'nombre_proceso', 
            'nombre_departamento', 'fk_subproceso', 'fk_proceso', 'fk_categoria', 
            'fk_tipo_documento', 'num_revision', 'fecha_emision'
        ];
    
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                error_log("Missing required field: " . $field);
                echo json_encode(['message' => 'Error: Falta el campo requerido ' . $field]);
                return;
            }
        }
    
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            error_log("File upload error: " . ($_FILES['archivo']['error'] ?? 'No file uploaded'));
            echo json_encode(['message' => 'Error: Problema con la subida del archivo']);
            return;
        }
    
        $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
        $fk_departamento = filter_var($_POST['fk_departamento'], FILTER_SANITIZE_NUMBER_INT);
        $fk_categoria = filter_var($_POST['fk_categoria'], FILTER_SANITIZE_NUMBER_INT);
        $fk_tipo_documento = filter_var($_POST['fk_tipo_documento'], FILTER_SANITIZE_NUMBER_INT);
        $fk_subproceso = filter_var($_POST['fk_subproceso'], FILTER_SANITIZE_NUMBER_INT);
        $fecha_emision = filter_var($_POST['fecha_emision'], FILTER_SANITIZE_STRING);
        $num_revision = filter_var($_POST['num_revision'], FILTER_SANITIZE_NUMBER_INT);
        $fk_proceso = filter_var($_POST['fk_proceso'], FILTER_SANITIZE_NUMBER_INT);
        $nombre_macro_proceso = preg_replace('/[^A-Za-z0-9\-]/', '_', filter_var($_POST['nombre_macro_proceso'], FILTER_SANITIZE_STRING));
        $nombre_proceso = preg_replace('/[^A-Za-z0-9\-]/', '_', filter_var($_POST['nombre_proceso'], FILTER_SANITIZE_STRING));
        $nombre_departamento = preg_replace('/[^A-Za-z0-9\-]/', '_', filter_var($_POST['nombre_departamento'], FILTER_SANITIZE_STRING));
    
        error_log("Sanitized variables:");
        error_log("titulo: $titulo");
        error_log("fk_departamento: $fk_departamento");
        error_log("fk_categoria: $fk_categoria");
        error_log("fk_tipo_documento: $fk_tipo_documento");
        error_log("fk_subproceso: $fk_subproceso");
        error_log("fecha_emision: $fecha_emision");
        error_log("num_revision: $num_revision");
        error_log("fk_proceso: $fk_proceso");
        error_log("nombre_macro_proceso: $nombre_macro_proceso");
        error_log("nombre_proceso: $nombre_proceso");
        error_log("nombre_departamento: $nombre_departamento");
    
        $archivo = $_FILES['archivo'];
        $base_dir = "C:\\xampp\\htdocs\\controlador_archivos\\backend\\asset\\documents\\macroprocesos\\";
        if (!file_exists($base_dir)) {
            error_log("Base directory does not exist: " . $base_dir);
            echo json_encode(['message' => 'El directorio base no existe.']);
            return;
        }
    
        if (!is_writable($base_dir)) {
            error_log("Base directory is not writable: " . $base_dir);
            error_log("Current user: " . exec('whoami'));
            error_log("Directory permissions: " . substr(sprintf('%o', fileperms($base_dir)), -4));
            echo json_encode(['message' => 'El directorio base no tiene permisos de escritura.']);
            return;
        }
    
        $macro_dir = $base_dir . $nombre_macro_proceso . '/';
        $proceso_dir = $macro_dir . 'proceso/' . $nombre_proceso . '/';
        $target_dir = $proceso_dir . 'departamento/' . $nombre_departamento . '/';
    
        $dirs_to_create = [$macro_dir, $proceso_dir, $target_dir];
        foreach ($dirs_to_create as $dir) {
            if (!file_exists($dir)) {
                error_log("Attempting to create directory: " . $dir);
                if (!mkdir($dir, 0755, true)) {
                    $error = error_get_last();
                    error_log("Failed to create directory. Error: " . $error['message']);
                    echo json_encode(['message' => 'Error al crear el directorio.', 'error' => $error['message']]);
                    return;
                }
            }
        }
    
        $original_name = pathinfo($archivo["name"], PATHINFO_FILENAME);
        $file_extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($archivo["name"]);
    
        error_log("File information:");
        error_log("target_dir: $target_dir");
        error_log("original_name: $original_name");
        error_log("file_extension: $file_extension");
        error_log("target_file: $target_file");
    
        if (file_exists($target_file)) {
            $increment = 1;
            do {
                $new_filename = "{$original_name}{$increment}.{$file_extension}";
                $target_file = $target_dir . $new_filename;
                $increment++;
            } while (file_exists($target_file));
        }
    
        $max_size = min(
            $this->return_bytes(ini_get('upload_max_filesize')),
            $this->return_bytes(ini_get('post_max_size')),
            5 * 1024 * 1024  // 5MB
        );
        if ($archivo["size"] > $max_size) {
            error_log("File too large: " . $archivo["size"] . " bytes. Max allowed: " . $max_size);
            echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
            return;
        }
    
        $allowed_extensions = ['pdf', 'doc', 'docx', 'xlsx'];
        if (!in_array($file_extension, $allowed_extensions)) {
            error_log("Invalid file extension: " . $file_extension);
            echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX y XLSX.']);
            return;
        }
    
        error_log("Attempting to move file to: " . $target_file);
        if (!move_uploaded_file($archivo["tmp_name"], $target_file)) {
            $error = error_get_last();
            error_log("Error moving file: " . $error['message']);
            error_log("From: " . $archivo["tmp_name"] . " To: " . $target_file);
            echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.', 'error' => $error['message']]);
            return;
        }
    
        $archivo_url = "http://localhost/controlador_archivos/backend/asset/document/macroprocesos/$nombre_macro_proceso/proceso/$nombre_proceso/departamento/$nombre_departamento/" . basename($target_file);
        error_log("File successfully uploaded. URL: " . $archivo_url);
    
        $inserted = $model->insertDocumento([
            'titulo' => $titulo,
            'fk_departamento' => $fk_departamento,
            'fk_categoria' => $fk_categoria,
            'fk_tipo_documento' => $fk_tipo_documento,
            'fk_subproceso' => $fk_subproceso,
            'archivo_url' => $archivo_url,
            'fecha_emision' => $fecha_emision,
            'num_revision' => $num_revision,
            'fk_proceso' => $fk_proceso
        ]);
    
        if ($inserted === true) {
            error_log("Document successfully inserted into database");
            echo json_encode(['message' => 'Documento guardado correctamente.', 'archivo_url' => $archivo_url]);
        } else {
            error_log("Error inserting document into database: " . print_r($inserted, true));
            echo json_encode(['message' => 'Error al guardar documento.', 'error' => $inserted]);
        }
    }
    
    private function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
    
    
    

    public function actualizarDocumento($param)
    {
        error_log("--- actualizarDocumento() called ---");
        error_log("POST variables: " . print_r($_POST, true));
        error_log("FILES variables: " . print_r($_FILES, true));
    
        $model = $this->model('Documentos');
        $archivo_url = null;
    
        if (!isset($param['id']) || !$this->validId($param['id'])) {
            echo json_encode(['message' => 'Error: ID de documento inválido.']);
            return;
        }
    
        $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
    
        $required_fields = [
            'titulo', 'fk_departamento', 'nombre_macro_proceso', 'nombre_proceso', 
            'nombre_departamento', 'fk_subproceso', 'fk_proceso', 'fk_categoria', 
            'fk_tipo_documento', 'num_revision', 'fecha_emision'
        ];
    
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                error_log("Missing required field: " . $field);
                echo json_encode(['message' => 'Error: Falta el campo requerido ' . $field]);
                return;
            }
        }
    
        $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
        $fk_departamento = filter_var($_POST['fk_departamento'], FILTER_SANITIZE_NUMBER_INT);
        $fk_categoria = filter_var($_POST['fk_categoria'], FILTER_SANITIZE_NUMBER_INT);
        $fk_tipo_documento = filter_var($_POST['fk_tipo_documento'], FILTER_SANITIZE_NUMBER_INT);
        $fk_subproceso = filter_var($_POST['fk_subproceso'], FILTER_SANITIZE_NUMBER_INT);
        $fecha_emision = filter_var($_POST['fecha_emision'], FILTER_SANITIZE_STRING);
        $num_revision = filter_var($_POST['num_revision'], FILTER_SANITIZE_NUMBER_INT);
        $fk_proceso = filter_var($_POST['fk_proceso'], FILTER_SANITIZE_NUMBER_INT);
        $nombre_macro_proceso = preg_replace('/[^A-Za-z0-9\-]/', '_', filter_var($_POST['nombre_macro_proceso'], FILTER_SANITIZE_STRING));
        $nombre_proceso = preg_replace('/[^A-Za-z0-9\-]/', '_', filter_var($_POST['nombre_proceso'], FILTER_SANITIZE_STRING));
        $nombre_departamento = preg_replace('/[^A-Za-z0-9\-]/', '_', filter_var($_POST['nombre_departamento'], FILTER_SANITIZE_STRING));
    
        $base_dir = "/Applications/XAMPP/xamppfiles/htdocs/controlador_archivos/backend/asset/document/macroprocesos/";
        $macro_dir = $base_dir . $nombre_macro_proceso . '/';
        $proceso_dir = $macro_dir . 'proceso/' . $nombre_proceso . '/';
        $target_dir = $proceso_dir . 'departamento/' . $nombre_departamento . '/';
    
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['archivo'];
            
            $original_name = pathinfo($archivo["name"], PATHINFO_FILENAME);
            $file_extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
            $target_file = $target_dir . basename($archivo["name"]);
    
            // Check if file already exists, generate unique filename if needed
            if (file_exists($target_file)) {
                $increment = 1;
                do {
                    $new_filename = "{$original_name}{$increment}.{$file_extension}";
                    $target_file = $target_dir . $new_filename;
                    $increment++;
                } while (file_exists($target_file));
            }
    
            $max_size = min(
                $this->return_bytes(ini_get('upload_max_filesize')),
                $this->return_bytes(ini_get('post_max_size')),
                5 * 1024 * 1024  // 5MB
            );
            if ($archivo["size"] > $max_size) {
                echo json_encode(['message' => 'Lo siento, tu archivo es demasiado grande.']);
                return;
            }
    
            $allowed_extensions = ['pdf', 'doc', 'docx', 'xlsx'];
            if (!in_array($file_extension, $allowed_extensions)) {
                echo json_encode(['message' => 'Lo siento, solo se permiten archivos PDF, DOC, DOCX y XLSX.']);
                return;
            }
    
            if (!move_uploaded_file($archivo["tmp_name"], $target_file)) {
                $error = error_get_last();
                echo json_encode(['message' => 'Lo siento, hubo un error al subir tu archivo.', 'error' => $error['message']]);
                return;
            }
    
            $archivo_url = "http://localhost/controlador_archivos/backend/asset/document/macroprocesos/$nombre_macro_proceso/proceso/$nombre_proceso/departamento/$nombre_departamento/" . basename($target_file);
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
            'fk_proceso' => $fk_proceso,
        ];
    
        $updated = $model->updateDocumento($data);
    
        if ($updated === true) {
            echo json_encode(['message' => 'Documento actualizado correctamente.', 'archivo_url' => $archivo_url]);
        } else {
            echo json_encode(['message' => 'Error al actualizar documento.', 'error' => $updated]);
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
