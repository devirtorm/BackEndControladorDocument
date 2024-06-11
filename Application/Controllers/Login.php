<?php

use MVC\Controller;


class ControllersLogin extends Controller
{
    public function loginAction()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['email']) && isset($data['password'])) {
            $email = filter_var($data['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
            $model = $this->model('Login');
            $user = $model->getUserByEmail($email);

            if ($user && $password === $user['contrasenia']) {
              
            // Devolver el correo electrónico y el ID de la persona en la respuesta JSON
            $this->response->sendStatus(200);
            $this->response->setContent(json_encode([
                'email' => $user['correo'],
                'id_persona' => $user['id_persona']
            ]));
            } else {
                $this->response->sendStatus(401);
                $this->response->setContent(json_encode(['message' => 'Credenciales inválidas']));
            }
        } else {
            $this->response->sendStatus(400);
            $this->response->setContent(json_encode(['message' => 'Datos incompletos']));
        }
    }
}
