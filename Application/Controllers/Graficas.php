<?php

use MVC\Controller;

class ControllersGraficas extends Controller
{
    public function GraficaCantDocumentTipo()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Graficas');
            $data_list = $model->cantDocumentosTipo();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }


    public function GraficaCantDocumentDepartamento()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Graficas');
            $data_list = $model->cantDocumentosDepartamentoGrafica();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }


    public function TotalDocumentos()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Graficas');
            $data_list = $model->cantDocumentosCard();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }

    public function documentosSinRevisar()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Graficas');
            $data_list = $model->documentosPorRevisar();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }


    public function DocumentosSinAutorizar()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Graficas');
            $data_list = $model->documentosPorAutorizar();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }
}
?>