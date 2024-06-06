<?php

// paths
$router->get('/', function() {
    echo '<div style="text-align: center;width: 350px;margin: 50px auto;font-size: 25px;padding: 50px;box-shadow: 0 0 10px #dedede;border-radius: 5px;">
        Welcome To The Library <br><br>
        <a href="https://github.com/afgprogrammer/php-books-library" title="afgprogrammer-github"> See Doc In Github </a>
    </div>';
});


$router->post('/add-person', 'Personas@addPerson'); //agrega registro para personas

$router->get('/personas', 'Personas@personas'); // Muestra registros de personas

$router->post('/subprocesos', 'Subprocesos@crearSubproceso'); // Guarda subprocesos

$router->get('/subprocesos/desactivados', 'Subprocesos@obtenerSubprocesosDesactivados'); // Muestra subprocesos desactivados

$router->get('/subprocesos', 'Subprocesos@obtenerSubprocesos');

$router->delete('/subprocesos/:id', 'Subprocesos@eliminarSubproceso');

$router->put('/subprocesos/:id/desactivar', 'Subprocesos@actualizarSubprocesoActivo');





$router->get('/xd', 'Prueba@index');

$router->post('/add-book', 'Books@addBook');   

// install system
$router->get('/install', 'System@index');

// books router
$router->get('/books', 'Books@books');
$router->get('/books/:page', 'Books@books');

// search books
$router->get('/books/title/:title', 'Books@searchBooksByTitle');
$router->get('/books/title/:title/:page', 'Books@searchBooksByTitle');

// Get All Books And Authors
$router->get('/all', 'Books@index');

$router->get('/books/isbn/:isbn', 'Books@searchBooksByISBN');

$router->get('/books/author/:author', 'Books@searchBooksByAuthors');
$router->get('/books/author/:author/:page', 'Books@searchBooksByAuthors');

// authors router
$router->get('/authors', 'Books@authors');
$router->get('/authors/:page', 'Books@authors');

// search author
$router->get('/authors/:author', 'Books@searchBooksByAuthors');
$router->get('/authors/:author/:page', 'Books@searchBooksByAuthors');
