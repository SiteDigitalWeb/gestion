<?php
Route::group(['middleware' => ['auth','administrador']], function (){
Route::prefix('ge')->group(function () {
Route::get('commercial', 'Sitedigitalweb\Gestion\Http\GestionController@index');
Route::get('commercial-list', 'Sitedigitalweb\Gestion\Http\GestionController@indexlist');
Route::get('register-user', 'Sitedigitalweb\Gestion\Http\GestionController@registro');
Route::resource('products', Sitedigitalweb\Gestion\Http\ProductoController::class)->names('ge.products');
Route::resource('referrals', \Sitedigitalweb\Gestion\Http\ReferidoController::class)->names('ge.referrals');
Route::resource('sectors', \Sitedigitalweb\Gestion\Http\SectorController::class)->names('ge.sectors');
Route::resource('quantities', \Sitedigitalweb\Gestion\Http\QuantityController::class)->names('ge.quantities');
Route::resource('embudo', \Sitedigitalweb\Gestion\Http\EmbudoController::class)->names('ge.embudo');


Route::get('proposal/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@propuesta');
Route::get('create-proposal/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@crearpropuesta');
Route::post('createproposal', 'Sitedigitalweb\Gestion\Http\GestionController@crearpropuestanew');
Route::get('create-product', 'Sitedigitalweb\Gestion\Http\GestionController@crearproductos');
Route::post('createges', 'Sitedigitalweb\Gestion\Http\GestionController@creaproduct');
Route::get('portfolio/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@portafolio');
Route::get('configuration/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@configuracion');
Route::post('update-configuration/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@updatecon');
Route::get('dashboard', 'Sitedigitalweb\Gestion\Http\GestionController@dashboard');


});
});

Route::post('gestion/registrar/usuario', 'Sitedigitalweb\Gestion\Http\GestionController@create');
Route::get('/ciudad/ajax-subcatweb',function(){

        $cat_id = Input::get('cat_id');
        $subcategories = Sitedigitalweb\Pagina\Cms_Pais::where('pais_id', '=', $cat_id)->get();
        return Response::json($subcategories);
});
Route::get('/ubicacionciudad/ajax-subcatweb', [\Sitedigitalweb\Pagina\Http\CityController::class, 'getCiudades']);

Route::get('/ubicacionciudads/ajax-subcatweb', function () {
    $cat_id = request()->get('cat_id');
    $subcategories = \Sitedigitalweb\Pagina\Cms_departamento::where('pais_id', $cat_id)->get();
    return response()->json($subcategories);
});
Route::get('/ubicacion/ajax-subcatweb',function(){

        $cat_id = Input::get('cat_id');
        $subcategories = DigitalsiteSaaS\Carrito\Tenant\Municipio::where('departamento_id', '=', $cat_id)->get();
        return Response::json($subcategories);
});


Route::post('/gestion/comercial/update-funel/{id}', [Sitedigitalweb\Gestion\Http\GestionController::class, 'updateFunel'])
    ->name('gestion.updateFunel');
Route::post('/gestion/comercial/cambiar-funel', [Sitedigitalweb\Gestion\Http\GestionController::class, 'cambiarFunel']);


Route::get('gestion/crear-pais', function(){
    return View::make('pagina::configuracion.crear-pais');
Route::post('gestion/crearpais', 'DigitalsiteSaaS\Pagina\Http\ConfiguracionController@crearpais'); 
Route::post('gestion/actualizarpais', 'DigitalsiteSaaS\Pagina\Http\ConfiguracionController@crearpais'); 






Route::get('gestion/comercial/dashboard', 'DigitalsiteSaaS\Gestion\Http\GestionController@dashboard');
Route::get('gestion/comercial/editar-propuesta/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarpropuesta');
Route::post('gestion/comercial/editarpropuesta/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarpropuestaa');


Route::get('gestion/comercial/editar/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@edito');

Route::get('gestion/comercial/editar-cantidades/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarcan');
Route::get('gestion/comercial/editar-motivos/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarmot');
Route::get('gestion/comercial/editar-funels/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarfunel');


Route::get('gestion/comercial/editar-product/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarproduct');

Route::get('gestion/comercial/editar-referido/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editreferido');


Route::get('gestion/comercial/editar-sector/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editsector');

Route::get('gestion/comercial/editar-producto/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editproducto');

Route::get('gestion/comercial/editar-recepcion/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editrecepcion');

Route::post('gestion/comercial/editarcantidad/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarcantidad');
Route::post('gestion/comercial/editarmotivo/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarmotivo');
Route::post('gestion/comercial/editarfunel/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarfunelsa');
Route::post('gestion/comercial/editarreferido/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarreferido');
Route::post('gestion/comercial/editarsector/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarsector');
Route::post('gestion/comercial/editarproducto/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarproducto');
Route::post('gestion/comercial/editarusuario/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarusuario');

Route::resource('gestion/comercial/editar-usuario', 'DigitalsiteSaaS\Gestion\Http\GestionController@edit');
Route::get('gestion/comercial/eliminar/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminar');
Route::get('gestion/comercial/eliminar-producto/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarproducto');
Route::get('gestion/comercial/eliminar-productopro/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarproductopro');
Route::get('gestion/comercial/eliminar-sector/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarsector');
Route::get('gestion/comercial/eliminar-referido/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarreferido');
Route::get('gestion/comercial/eliminar-cantidades/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarcantidad');
Route::get('gestion/comercial/eliminar-funels/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarfunel');

Route::get('gestion/comercial/crear-sector', 'DigitalsiteSaaS\Gestion\Http\GestionController@crearsector');
Route::get('gestion/comercial/crear-referido', 'DigitalsiteSaaS\Gestion\Http\GestionController@crearreferido');

Route::get('gestion/comercial/crear-motivo', 'DigitalsiteSaaS\Gestion\Http\GestionController@crearmotivo');


Route::post('gestion/registrar/referido', 'DigitalsiteSaaS\Gestion\Http\GestionController@registrarreferido');



Route::post('gestion/registrar/motivo', 'DigitalsiteSaaS\Gestion\Http\GestionController@registrarmotivo');


Route::get('gestion/comercial/motivos', 'DigitalsiteSaaS\Gestion\Http\GestionController@motivos');

Route::post('gestion/registrar/usuario', 'DigitalsiteSaaS\Gestion\Http\GestionController@create');
Route::post('gestion/usuariorecepcion', 'DigitalsiteSaaS\Gestion\Http\GestionController@createrecepcion');
});


Route::get('/validacion/nit', 'DigitalsiteSaaS\Gestion\Http\GestionController@valida');
Route::post('productos/updatepro/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@proupdate');

Route::group(['middleware' => ['auths','recepcion']], function (){
Route::get('gestion/comercial-recepcion', 'DigitalsiteSaaS\Gestion\Http\GestionController@recepcion');

Route::get('gestion/registro-recepcion', function(){
 $productos = DB::table('gestion_productos')->get();
 $sectores = DB::table('gestion_sector')->get();
  $referidos = DB::table('gestion_referidos')->get();
 $cantidades = DB::table('gestion_cantidad')->get();
  $paises = DB::table('paises')->orderBy('pais', 'ASC')->get();
 return View::make('gestion::registro-recepcion')->with('productos', $productos)->with('sectores', $sectores)->with('referidos', $referidos)->with('cantidades', $cantidades)->with('paises', $paises);
});





Route::get('gestion/comercial/editar-registrorec/{id}', function($id){
 $usuario = DB::table('gestion_usuarios')
 ->join('gestion_productos','gestion_usuarios.interes','=','gestion_productos.id')
 ->join('gestion_sector','gestion_usuarios.sector_id','=','gestion_sector.id')
 ->join('gestion_cantidad', 'gestion_cantidad.id', '=', 'gestion_usuarios.cantidad_id')
  ->join('gestion_referidos', 'gestion_referidos.id', '=', 'gestion_usuarios.referido_id')
 ->leftjoin('paises', 'paises.id', '=', 'gestion_usuarios.pais_id')
 ->leftjoin('departamentos', 'departamentos.id', '=', 'gestion_usuarios.ciudad_id')
 ->where('gestion_usuarios.id', '=', $id)->get();
 $productos = DB::table('gestion_productos')->get();
 $paises = DB::table('paises')->orderBy('pais', 'ASC')->get();
  $referidos = DB::table('gestion_referidos')->get();
 $sectores = DB::table('gestion_sector')->get();
  $cantidades = DB::table('gestion_cantidad')->get();
 return View::make('gestion::editar-registrorec')->with('productos', $productos)->with('sectores', $sectores)->with('usuario', $usuario)->with('paises', $paises)->with('referidos', $referidos)->with('cantidades', $cantidades);
});
Route::post('gestion/comercial/editar-usuariorec/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editrec');
});


