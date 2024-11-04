<?php

/**
 *
 * This file is part of mvc-rest-api for PHP.
 *
 */
namespace MVC;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class Controller, a port of MVC
 *
 * @author Mohammad Rahmani <rto1680@gmail.com>
 *
 * @package MVC
 */
class Controller {

    /**
     * Request Class.
     */
    public $request;

    /**
     * Response Class.
     */
    public $response;

	/**
	*  Construct
	*/
    public function __construct() {
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    /**
     * get Model
     * 
     * @param string $model
     * 
     * @return object
     */
    public function model($model) {
        $file = MODELS . ucfirst($model) . '.php';

		// check exists file
        if (file_exists($file)) {
            require_once $file;

            $model = 'Models' . str_replace('/', '', ucwords($model, '/'));
			// check class exists
            if (class_exists($model))
                return new $model;
            else 
                throw new Exception(sprintf('{ %s } this model class not found', $model));
        } else {
            throw new Exception(sprintf('{ %s } this model file not found', $file));
        }
    }

    private $secretKey = '123'; // Debe ser almacenada en un entorno seguro

    public function verifyToken()
    {
        $authHeader = $this->getAuthorizationHeader();
        
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            try {
                $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
                return $decoded;
            } catch (\Exception $e) {
                error_log("JWT Decoding Error: " . $e->getMessage()); // Agrega un log para depurar el error
                $this->response->sendStatus(401);
                $this->response->setContent([
                    'message' => 'Token inválido'
                ]);
                exit;
            }
        } else {
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Token no proporcionado'
            ]);
            exit;
        }
    }

    private function getAuthorizationHeader()
    {
        if (isset($_SERVER['Authorization'])) {
            return trim($_SERVER["Authorization"]);
        }
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim($_SERVER["HTTP_AUTHORIZATION"]);
        }
        if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            if (isset($requestHeaders['Authorization'])) {
                return trim($requestHeaders['Authorization']);
            }
        }
        return null;
    }




	
}
