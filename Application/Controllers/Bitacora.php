<?php

use MVC\Controller;

class ControllersBitacora extends Controller
{
   
    public function getBitacora()
    {
        // Connect to database
        $model = $this->model('Bitacora');

        $data_list = $model->obtenerDatosBitacora();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }
}

?>