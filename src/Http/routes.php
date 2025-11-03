<?php
Route::group(['middleware' => ['auth','administrador']], function (){
Route::prefix('ge')->group(function () {

Route::get('register-user', 'Sitedigitalweb\Gestion\Http\GestionController@registro');

Route::resource('commercial', Sitedigitalweb\Gestion\Http\UserController::class)->names('ge.commercial');
Route::get('commercial-list', [Sitedigitalweb\Gestion\Http\UserController::class, 'indexlist']);
Route::resource('products', Sitedigitalweb\Gestion\Http\ProductoController::class)->names('ge.products');
Route::resource('referrals', \Sitedigitalweb\Gestion\Http\ReferidoController::class)->names('ge.referrals');
Route::resource('sectors', \Sitedigitalweb\Gestion\Http\SectorController::class)->names('ge.sectors');
Route::resource('quantities', \Sitedigitalweb\Gestion\Http\QuantityController::class)->names('ge.quantities');
Route::resource('embudo', \Sitedigitalweb\Gestion\Http\EmbudoController::class)->names('ge.embudo');
Route::resource('proposal', \Sitedigitalweb\Gestion\Http\ProposalController::class)->names('ge.proposal');
Route::resource('product', \Sitedigitalweb\Gestion\Http\ProductController::class)->names('ge.product');
Route::resource('configuration', \Sitedigitalweb\Gestion\Http\ConfigurationController::class)->names('ge.configuration');


Route::get('portfolio/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@portafolio');

Route::post('update-configuration/{id}', 'Sitedigitalweb\Gestion\Http\GestionController@updatecon');

Route::get('dashboard', 'Sitedigitalweb\Gestion\Http\GestionController@dashboard');

Route::get('gestion/comercial/editar-propuesta/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarpropuesta');

});
});


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






Route::get('gestion/comercial/dashboard', 'DigitalsiteSaaS\Gestion\Http\GestionController@dashboard');

Route::post('gestion/comercial/editarpropuesta/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarpropuestaa');


Route::get('gestion/comercial/editar-motivos/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarmot');


Route::get('gestion/comercial/editar-product/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarproduct');





Route::post('gestion/comercial/editarmotivo/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@editarmotivo');




Route::get('gestion/comercial/eliminar-producto/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarproducto');
Route::get('gestion/comercial/eliminar-productopro/{id}', 'DigitalsiteSaaS\Gestion\Http\GestionController@eliminarproductopro');



Route::get('gestion/comercial/crear-motivo', 'DigitalsiteSaaS\Gestion\Http\GestionController@crearmotivo');





Route::post('gestion/registrar/motivo', 'DigitalsiteSaaS\Gestion\Http\GestionController@registrarmotivo');


Route::get('gestion/comercial/motivos', 'DigitalsiteSaaS\Gestion\Http\GestionController@motivos');



});




