<?php

use MVC\Controller;

class ControllersCarreraDocumentos extends Controller
{
    public function ObtenerCarreraDocumentos()
    {
        $model = $this->model('CarreraDocumentos');
        $data_list = $model->CarreraDocumentos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarreraDocumentosActivos()
    {
        $model = $this->model('CarreraDocumentos');
        $data_list = $model->CarreraDocumentosActivos();

        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function ObtenerCarreraDocumentosInactivos()
    {
        $model = $this->model('CarreraDocumentos');
        $data_list = $model->CarreraDocumentosInactivos();

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

        $model = $this->model('CarreraDocumentos');
        $carreraDocumento = $model->CarreraDocumento($id);

        if ($carreraDocumento) {
            $this->response->sendStatus(200);
            $this->response->setContent($carreraDocumento);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'Documento no encontrado']);
        }
    }

    public function ObtenerCarreraDocumentosPorCarrera($fk_carrera)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $fk_carrera = end($segments);
        $fk_carrera = intval($fk_carrera);

        if ($fk_carrera === 0) {
            echo json_encode(['message' => 'Error: ID de carrera inválido.']);
            return;
        }

        $model = $this->model('CarreraDocumentos');
        $documentos = $model->CarreraDocumentosPorCarrera($fk_carrera);

        if (!empty($documentos)) {
            $this->response->sendStatus(200);
            $this->response->setContent($documentos);
        } else {
            $this->response->sendStatus(404);
            $this->response->setContent(['error' => 'No se encontraron documentos para esta carrera']);
        }
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
                echo json_encode(['message' => 'Documento asociado correctamente a la carrera.']);
            } else {
                echo json_encode(['message' => 'Error al asociar el documento a la carrera.']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos son inválidos o incompletos.']);
        }
    }

    public function ActualizarCarreraDocumento($id)
    {
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

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
        $segments = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $id = end($segments);
        $id = intval($id);

        if ($id === 0) {
            echo json_encode(['message' => 'Error: ID inválido.']);
            return;
        }

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
