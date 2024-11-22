<?php

use MVC\Controller;

class ControllersNotificaciones extends Controller
{
    public function cantidadDeNotificaciones()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Notificaciones');
            $data_list = $model->CantNotificaciones();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }

    public function allDataMenssages()
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Notificaciones');
            $data_list = $model->dataMensajes();

            // Enviar respuesta
            $this->response->sendStatus(200);
            $this->response->setContent($data_list);
        } catch (\Exception $e) {
            // Enviar respuesta de error
            $this->response->sendStatus(500);
            $this->response->setContent(['error' => $e->getMessage()]);
        }
    }


    public function actualizarVistoMensaje($param)
    {
        try {
            // Conectar con el modelo
            $model = $this->model('Notificaciones');
            $data_list = $model->mensajeVisto($param['id']);

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
