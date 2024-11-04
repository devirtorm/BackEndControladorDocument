<?php

use MVC\Controller;
use \Firebase\JWT\JWT; // Asegúrate de que la librería JWT esté importada
require 'vendor/autoload.php';


class ControllersLogin extends Controller
{
    private $secretKey = '123'; // Debe ser almacenada en un entorno seguro

    public function loginAction()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['email']) && isset($data['password'])) {
            $email = filter_var($data['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $model = $this->model('Login');
            $user = $model->getUserByEmail($email);

            if ($user && $password === $user['contrasenia']) { // Comparación directa sin encriptación

                $payload = [
                    'iss' => "http://localhost",  // Emisor del token
                    'aud' => "http://localhost",  // Audiencia del token
                    'iat' => time(),              // Hora en la que se emitió el token
                    'exp' => strtotime('+10 years'),  // Expiración del token en 10 años
                    'data' => [
                        'id_usuario' => $user['id_usuario'],
                        'email' => $user['correo'],
                        'fk_departamento' => $user['fk_departamento'],
                        'nombre_departamento' => $user['nombre_departamento'],
                        'nombre_rol' => $user['nombre_rol']
                    ]
                ];

                $jwt = JWT::encode($payload, $this->secretKey, 'HS256');

                $this->response->sendStatus(200);
                $this->response->setContent(([
                    'token' => $jwt,
                    'email' => $user['correo'],
                    'id_usuario' => $user['id_usuario'],
                    'contrasenia' => $user['contrasenia'],
                    'fk_departamento' => $user['fk_departamento'],
                    'nombre_departamento' => $user['nombre_departamento'],
                    'nombre_rol' => $user['nombre_rol']
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

