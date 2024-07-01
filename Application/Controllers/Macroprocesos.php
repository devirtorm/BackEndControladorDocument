<?php

use MVC\Controller;

class ControllersMacroprocesos extends Controller
{

    public function obtenerMacroprocesos()
    {

        // Connect to database
        $model = $this->model('Macroprocesos');

        $data_list = $model->macroprocesos(1);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

}