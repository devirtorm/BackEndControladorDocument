<?php 

use MVC\Controller;

class ControllersPrueba  extends Controller {

    public function index() {

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent('jotocabron');
    }

}
