<?php

use MVC\Controller;

class ControllersCarreraDocumentos extends Controller
{
    public function ObtenerCarreraDocumentos($param)
    {
        // Conectar al modelo
        $model = $this->model('CarreraDocumentos');

        // Llamar a la función del modelo
        $data_list = $model->CarreraDocumentos($param['id']);

        // Enviar respuesta
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarreraDocumento($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

        // Conectar al modelo
        $model = $this->model('CarreraDocumentos');
        
        // Llamar a la función del modelo
        $data_list = $model->CarreraDocumento($id);

        // Enviar respuesta
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarreraDocumentosActivas($param)
    {
        // Conectar al modelo
        $model = $this->model('CarreraDocumentos');

        // Llamar a la función del modelo
        $data_list = $model->CarreraDocumentosActivas($param['id']);

        // Enviar respuesta
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarreraDocumentosInactivas($param)
    {
        // Conectar al modelo
        $model = $this->model('CarreraDocumentos');

        // Llamar a la función del modelo
        $data_list = $model->CarreraDocumentosInactivas($param['id']);

        // Enviar respuesta
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function CrearCarreraDocumento()
    {
        $model = $this->model('CarreraDocumentos');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['fk_carrera']) && isset($data['fk_documento'])) {
            $fk_carrera = filter_var($data['fk_carrera'], FILTER_VALIDATE_INT);
            $fk_documento = filter_var($data['fk_documento'], FILTER_VALIDATE_INT);

            $inserted = $model->createCarreraDocumento(['fk_carrera' => $fk_carrera, 'fk_documento' => $fk_documento]);

            if ($inserted) {
                // Retorna una respuesta exitosa
                echo json_encode(['message' => 'Documento asociado correctamente a la carrera.', 'data' => $data]);
            } else {
                // Retorna un mensaje de error si la inserción falla
                echo json_encode(['message' => 'Error al asociar el documento a la carrera.', 'data' => []]);
            }
        } else {
            // Retorna un mensaje de error si los datos son inválidos o incompletos
            echo json_encode(['message' => 'Error: Los datos son inválidos o incompletos.', 'data' => []]);
        }
    }

    public function ActualizarCarreraDocumento($id)
    {
        $model = $this->model('CarreraDocumentos');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['fk_carrera']) && isset($data['fk_documento'])) {
            $fk_carrera = filter_var($data['fk_carrera'], FILTER_VALIDATE_INT);
            $fk_documento = filter_var($data['fk_documento'], FILTER_VALIDATE_INT);
            $updated = $model->updateCarreraDocumento($id, $fk_carrera, $fk_documento);

            if ($updated) {
                echo json_encode(['message' => 'Asociación actualizada correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al actualizar la asociación.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos son inválidos o incompletos.']);
        }
    }

    public function EliminarCarreraDocumento($id)
    {
        $model = $this->model('CarreraDocumentos');
        $deleted = $model->deleteCarreraDocumento($id);

        if ($deleted) {
            echo json_encode(['message' => 'Asociación eliminada correctamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar la asociación.']);
        }
    }
}
?>
