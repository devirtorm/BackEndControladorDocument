<?php

// paths
$router->get('/', function() {
    echo '<div style="text-align: center;width: 350px;margin: 50px auto;font-size: 25px;padding: 50px;box-shadow: 0 0 10px #dedede;border-radius: 5px;">
        Welcome To The Library <br><br>
        <a href="https://github.com/afgprogrammer/php-books-library" title="afgprogrammer-github"> See Doc In Github </a>
    </div>';
});

//########################## RUTAS PARA PERSONAS #######################################

$router->post('/personas', 'Personas@addPerson'); //agrega registro para personas
$router->get('/personas', 'Personas@personas'); // Muestra registros de personas

//########################## RUTAS PARA SUBPROCESOS #######################################

$router->post('/subprocesos', 'Subprocesos@crearSubproceso'); // Crea nuevos subprocesos
$router->get('/subprocesos/desactivados', 'Subprocesos@obtenerSubprocesosDesactivados'); // Muestra subprocesos desactivados
$router->get('/subprocesos', 'Subprocesos@obtenerSubprocesos'); // Obtiene todos los subprocesos
$router->delete('/subprocesos/:id', 'Subprocesos@eliminarSubproceso'); // Elimina un subproceso
$router->put('/subprocesos/:id/desactivar', 'Subprocesos@desactivarSubproceso'); // Desactiva un subproceso
$router->put('/subprocesos/:id/activar', 'Subprocesos@activarSubproceso'); // Activa un subproceso
$router->put('/subprocesos/:id', 'Subprocesos@actualizarSubproceso'); // Actualiza datos de un subproceso
$router->get('/subprocesos/:id', 'Subprocesos@ObtenerSubproceso'); // Elimina una materia


//########################## RUTAS PARA PROCESOS #######################################

$router->post('/procesos', 'Procesos@crearProcesos'); // Crea nuevos subprocesos
$router->get('/procesos/desactivados', 'Procesos@obtenerProcesosDesactivados'); // Muestra subprocesos desactivados
$router->get('/procesos', 'Procesos@obtenerProcesos'); // Obtiene todos los subprocesos
$router->get('/proceso/:id', 'Areas@obtenerProceso'); // Muestra los datos de un proceso
$router->delete('/procesos/:id', 'Procesos@eliminarProceso'); // Elimina un subproceso
$router->put('/procesos/:id/desactivar', 'Procesos@desactivarProceso'); // Desactiva un subproceso
$router->put('/procesos/:id/activar', 'Procesos@activarProceso'); // Activa un subproceso
$router->put('/procesos/:id', 'Procesos@actualizarProceso'); // Actualiza datos de un subproceso
$router->get('/procesos-macroprocesos/:id', 'Procesos@ObtenerProcesoByMacroId'); // recibe el id perteneciente a macroproceso para buscar



//########################## RUTAS PARA AREA #######################################

$router->post('/areas', 'Areas@crearArea'); // Crea nuevas areas
$router->get('/areas', 'Areas@obtenerAreas'); // Muestra los registros de las areas
$router->get('/area/:id', 'Areas@obtenerArea'); // Muestra los datos de un area
$router->get('/areas/desactivadas', 'Areas@obtenerAreasDesactivadas'); // Muestra los registros de las areas desactivadas
$router->put('/areas/:id/desactivar', 'Areas@desactivarArea'); // Desactiva un area en especifico 
$router->put('/areas/:id/activar', 'Areas@activarArea'); // Activa un area en especifico
$router->put('/areas/:id', 'Areas@actualizarArea'); // Actualiza datos de un area
$router->delete('/areas/:id', 'Areas@eliminarArea'); // Elimina un area en especifico

//########################## RUTAS PARA TIPOS DE DOCUMENTOS #######################################

$router->post('/tipos-documentos', 'TiposDocumentos@crearTipoDocumento'); 
$router->get('/tipos-documentos', 'TiposDocumentos@obtenerTiposDocumentos'); 
//$router->get('/area/:id', 'Areas@obtenerArea'); // Muestra los datos de un area
$router->get('/tipos-documentos/desactivados', 'TiposDocumentos@obtenerTiposDocumentosDesactivados');
$router->put('/tipos-documentos/:id/desactivar', 'TiposDocumentos@desactivarTipoDocumento'); 
$router->put('/tipos-documentos/:id/activar', 'TiposDocumentos@activarTipoDocumento'); 
$router->put('/tipos-documentos/:id', 'TiposDocumentos@actualizarTipoDocumento'); 
$router->delete('/tipos-documentos/:id', 'TiposDocumentos@eliminarTipoDocumento');


//########################## RUTAS PARA DOCUMENTOS #######################################

$router->post('/documentos', 'Documentos@crearDocumento'); 
$router->get('/documentos', 'Documentos@obtenerDocumentos'); 
$router->get('/documentos/:id', 'Documentos@obtenerDocumento'); // Muestra los datos de un area
$router->get('/documentos-desactivados', 'Documentos@obtenerDocumentosDesactivados');
$router->put('/documentos/:id/desactivar', 'Documentos@desactivarDocumento'); 
$router->put('/documentos/:id/activar', 'Documentos@activarDocumento'); 
$router->post('/documentos/:id', 'Documentos@actualizarDocumento'); 
$router->delete('/documentos/:id', 'Documentos@eliminarDocumento');
$router->get('/documentos-procesos/:id', 'Documentos@obtenerDocumentoByProceso');


//########################## RUTAS PARA CATEGORIA #######################################

$router->post('/categorias', 'Categorias@crearCategoria'); 
$router->get('/categorias', 'Categorias@obtenerCategorias'); 
$router->get('/categoria/:id', 'Categorias@obtenerCategoria'); 
$router->get('/categorias/desactivadas', 'Categorias@obtenerCategoriasDesactivadas'); 
$router->put('/categorias/:id/desactivar', 'Categorias@desactivarCategoria'); 
$router->put('/categorias/:id/activar', 'Categorias@activarCategoria'); 
$router->put('/categorias/:id', 'Categorias@actualizarCategoria');  
$router->delete('/categorias/:id', 'Categorias@eliminarCategoria'); 



//########################## RUTAS PARA DEPARTAMENTOS  #######################################

$router->post('/departamentos', 'Departamentos@crearDepartamento'); // Crea nuevas areas
$router->get('/departamentos', 'Departamentos@obtenerDepartamentos'); // Muestra los registros de los departamentos
$router->get('/departamento/:id', 'Departamentos@obtenerDepartamento'); // Muestra los datos de un departamento
$router->get('/departamentos/desactivados', 'Departamentos@obtenerDepartamentosDesactivados'); // Muestra los registros de las areas
$router->put('/departamentos/:id/desactivar', 'Departamentos@desactivarDepartamento'); // Desactiva un departamento en especifico 
$router->put('/departamentos/:id/activar', 'Departamentos@activarDepartamento'); // Activa un departamento en especifico
$router->put('/departamentos/:id', 'Departamentos@actualizarDepartamento'); // Actualiza datos de un departamento
$router->delete('/departamentos/:id', 'Departamentos@eliminarDepartamentos'); // Elimina un area en especifico

//########################## RUTAS PARA DIRECCIONES #######################################

$router->get('/direcciones/activas', 'Direcciones@ObtenerDireccionesActivas'); // Obtiene todos los datos de todas las direcciones activas
$router->get('/direcciones/inactivas', 'Direcciones@ObtenerDireccionesInactivas'); // Obtiene todos los datos de todas las direcciones inactivas
$router->get('/direcciones', 'Direcciones@ObtenerDirecciones'); // Obtiene todos los datos de todas las direcciones
$router->get('/direcciones/:id', 'Direcciones@ObtenerDireccion'); // Obtiene datos de una direccion en especifico
$router->post('/direcciones', 'Direcciones@CrearDireccion'); // Crea nuevas direcciones
$router->post('/direcciones/:id', 'Direcciones@ActualizarDireccion'); // Actualiza datos de una direccion
$router->put('/direcciones/desactivar/:id', 'Direcciones@DesactivarDireccion'); // Desactiva una direccion
$router->put('/direcciones/activar/:id', 'Direcciones@ActivarDireccion'); // Activa una direccion
$router->delete('/direcciones/:id', 'Direcciones@EliminarDireccion'); // Elimina una direccion

//########################## RUTAS PARA CARRERAS #######################################

$router->get('/carreras/activas', 'Carreras@ObtenerCarrerasActivas'); // Obtiene todos los datos de todas las carreras activas
$router->get('/carreras/inactivas', 'Carreras@ObtenerCarrerasInactivas'); // Obtiene todos los datos de todas las carreras inactivas
$router->get('/carreras', 'Carreras@ObtenerCarreras'); // Obtiene todos los datos de todas las carreras
$router->get('/carreras/:id', 'Carreras@ObtenerCarrera'); // Obtiene datos de una carrera en especifico
$router->post('/carreras', 'Carreras@CrearCarrera'); // Crea nuevas carreras
$router->put('/carreras/:id', 'Carreras@ActualizarCarrera'); // Actualiza datos de una carrera
$router->put('/carreras/desactivar/:id', 'Carreras@DesactivarCarrera'); // Desactiva una carrera
$router->put('/carreras/activar/:id', 'Carreras@ActivarCarrera'); // Activa una carrera
$router->delete('/carreras/:id', 'Carreras@EliminarCarrera'); // Elimina una carrera

//########################## RUTAS PARA CUATRIMESTRES #######################################

$router->get('/cuatrimestres/activas', 'Cuatrimestres@ObtenerCuatrimestresActivos'); // Obtiene todos los datos de todas las cuatrimestres activas
$router->get('/cuatrimestres/inactivas', 'Cuatrimestres@ObtenerCuatrimestresInactivos'); // Obtiene todos los datos de todas las cuatrimestres inactivas
$router->get('/cuatrimestres', 'Cuatrimestres@ObtenerCuatrimestres'); // Obtiene todos los datos de todas las cuatrimestres
$router->get('/cuatrimestres/:id', 'Cuatrimestres@ObtenerCuatrimestre'); // Obtiene datos de una cuatrimestre en especifico
$router->post('/cuatrimestres', 'Cuatrimestres@CrearCuatrimestre'); // Crea nuevas cuatrimestres
$router->put('/cuatrimestres/:id', 'Cuatrimestres@ActualizarCuatrimestre'); // Actualiza datos de una cuatrimestre
$router->put('/cuatrimestres/desactivar/:id', 'Cuatrimestres@DesactivarCuatrimestre'); // Desactiva una cuatrimestre
$router->put('/cuatrimestres/activar/:id', 'Cuatrimestres@ActivarCuatrimestre'); // Activa una cuatrimestre
$router->delete('/cuatrimestres/:id', 'Cuatrimestres@EliminarCuatrimestre'); // Elimina una cuatrimestre

//########################## RUTAS PARA MATERIAS #######################################

$router->get('/materias/activas', 'Materias@ObtenerMateriasActivas'); // Obtiene todos los datos de todas las materias activas
$router->get('/materias/inactivas', 'Materias@ObtenerMateriasInactivas'); // Obtiene todos los datos de todas las materias inactivas
$router->get('/materias', 'Materias@ObtenerMaterias'); // Obtiene todos los datos de todas las materias
$router->get('/materias/:id', 'Materias@ObtenerMateria'); // Obtiene datos de una materia en especifico
$router->post('/materias', 'Materias@CrearMateria'); // Crea nuevas materias
$router->post('/materias/:id', 'Materias@ActualizarMateria'); // Actualiza datos de una materia
$router->put('/materias/desactivar/:id', 'Materias@DesactivarMateria'); // Desactiva una materia
$router->put('/materias/activar/:id', 'Materias@ActivarMateria'); // Activa una materia
$router->delete('/materias/:id', 'Materias@EliminarMateria'); // Elimina una materia

//########################## RUTAS PARA MACROPROCESOS #######################################
$router->get('/macroprocesos', 'Macroprocesos@obtenerMacroprocesos'); // Obtiene todos los datos de los macroprocesos activos


//########################## RUTA DE LOGIN #######################################

$router->post('/login', 'Login@loginAction');

//########################## RUTA DE PRUEBA #######################################

$router->get('/xd', 'Prueba@index');

//########################## RUTAS POR DEFAULT #######################################
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


/* GRÁFICAS */
$router->get('/graficas/documenttipo', 'Graficas@GraficaCantDocumentTipo'); 
$router->get('/graficas/documentostotales', 'Graficas@TotalDocumentos'); 
$router->get('/graficas/sinautorizar', 'Graficas@DocumentosSinAutorizar'); 

$router->get('/graficas/sinrevisar', 'Graficas@documentosSinRevisar'); 
$router->get('/graficas/departamentodoc', 'Graficas@GraficaCantDocumentDepartamento'); 

