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

    public function ObtenerCarreraDocumento($id)
    {
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
