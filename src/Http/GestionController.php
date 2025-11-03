<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sitedigitalweb\Gestion\Cms_gestion;
use Sitedigitalweb\Pagina\Page;
use Sitedigitalweb\Gestion\Cms_producto;
use Sitedigitalweb\Gestion\Cms_product;
use Sitedigitalweb\Gestion\Cms_sector;
use Sitedigitalweb\Gestion\Cms_referido;
use Sitedigitalweb\Gestion\Cms_cantidad;
use Sitedigitalweb\Gestion\Cms_propuesta;
use Sitedigitalweb\Gestion\Cms_funel;
use Sitedigitalweb\Gestion\Cms_motivo;
use Sitedigitalweb\Gestion\Cms_config;
use Input;
use DB;
use Illuminate\Support\Str;
use Mail;
use App\Mail\Productos;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Repositories\HostnameRepository;
use Hyn\Tenancy\Repositories\WebsiteRepository;
use Sitedigitalweb\Carrito\Cms_pais;
use Sitedigitalweb\Pagina\Cms_paiscon;

class GestionController extends Controller
{

  protected $tenantName = null;


public function __construct()
{
if(!session()->has('cart')) session()->has('cart', array());
$hostname = app(\Hyn\Tenancy\Environment::class)->hostname();
        if ($hostname){
            $fqdn = $hostname->fqdn;
            $this->tenantName = explode(".", $fqdn)[0];
        }

}

/**
 * Obtener el modelo Cms_gestion según el contexto (tenant o central)
 */
private function getGestionModel()
{
    $website = app(\Hyn\Tenancy\Environment::class)->website();
    
    if ($website) {
        return new \Sitedigitalweb\Gestion\Tenant\Cms_gestion;
    }
    
    return new \Sitedigitalweb\Gestion\Cms_gestion;
}


   public function index()
    {
        // Traer usuarios (solo columnas necesarias) y funels (con color)
        $usuarios = Cms_gestion::select('id','name','last_name','empresa','email','phone','funel_id','created_at')
                      ->orderBy('created_at','desc')
                      ->get();

        $funels = Cms_funel::select('id','funel','color')
                  ->orderBy('id')
                  ->get();

        return view('gestion::index', compact('usuarios','funels'));
    }

 public function indexlist(){
  if(!$this->tenantName){  
  $usuarios = Cms_gestion::all();
  $sectores = Cms_sector::all();
  $productos = Cms_producto::all();
  $referidos = Cms_referido::all();
  $funels = Cms_funel::all();
  $interes = Page::all();
}else{
  $interes = \DigitalsiteSaaS\Pagina\Tenant\Page::all();
  $usuarios = \DigitalsiteSaaS\Gestion\Tenant\Cms_gestion::orderBy('created_at', 'desc')->get();
  $sectores = \DigitalsiteSaaS\Gestion\Tenant\Cms_sector::all();
  $referidos = \DigitalsiteSaaS\Gestion\Tenant\Cms_referido::all();
  $productos = \DigitalsiteSaaS\Gestion\Tenant\Cms_producto::all();
  $funels = \DigitalsiteSaaS\Gestion\Tenant\Cms_funel::all();
}

  return view('gestion::index-list')->with('usuarios', $usuarios)->with('sectores', $sectores)->with('productos', $productos)->with('referidos', $referidos)->with('funels', $funels)->with('interes', $interes);
 }


public function updateFunel(Request $request, $id)
{
    try {
        $usuario = $this->getGestionModel()::findOrFail($id);
        $usuario->funel_id = $request->input('funel_id');
        $usuario->save();

        return response()->json(['success' => true, 'usuario' => $usuario]);
    } catch (\Exception $e) {
        \Log::error("Error actualizando funel: " . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

    public function cambiarFunel(Request $request)
{
    $usuario = \Sitedigitalweb\Gestion\Cms_gestion::findOrFail($request->usuario_id);
    $usuario->funel_id = $request->funel_id;
    $usuario->save();

    return response()->json(['success' => true]);
}

 public function crearproductos(){
  return view('gestion::crear-productos');
 }

 public function registro(){
   if(!$this->tenantName){
 $productos = Cms_producto::all();
 $sectores = Cms_sector::all();
 $referidos = Cms_referido::all();
 $cantidades = Cms_cantidad::all();
 $paises = Cms_paiscon::orderBy('pais', 'ASC')->get();
 $funels = Cms_funel::all();
}else{
 $productos = \DigitalsiteSaaS\Gestion\Tenant\Producto::all();
 $sectores = \DigitalsiteSaaS\Gestion\Tenant\Sector::all();
 $referidos = \DigitalsiteSaaS\Gestion\Tenant\Referido::all();
 $cantidades = \DigitalsiteSaaS\Gestion\Tenant\Cms_cantidad::all();
 $funels = \DigitalsiteSaaS\Gestion\Tenant\Cms_funel::all();
 $paises = \DigitalsiteSaaS\Carrito\Tenant\Pais::orderBy('pais', 'ASC')->get();

}
 return view('gestion::registrar')->with('productos', $productos)->with('sectores', $sectores)->with('referidos', $referidos)->with('cantidades', $cantidades)->with('paises', $paises)->with('funels', $funels);
 }

 public function registrarproductos() {
  if(!$this->tenantName){
  $gestion = new Cms_producto; 
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_producto;  
  }
  $gestion->producto = Input::get('producto');
  $gestion->save();
  return Redirect('ge/productos')->with('status', 'ok_create');
 }

 public function create() {
  $interes = Input::get('interes');
  if(Input::get('fecha') == ''){
  $fecha = date('Y-m-d', strtotime(Input::get('fecha')));
  $mes_lead = date('M', strtotime($fecha));
  }else{
  $fecha = date('Y-m-d', strtotime(Input::get('fecha')));
  $mes_lead = date('M', strtotime($fecha));
  }
  $data = json_encode($interes, true);
  $vowels = array('"', '[', ']');
  $onlyconsonants = str_replace($vowels, '', $data);
  if(!$this->tenantName){
  $gestion = new Cms_gestion;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_gestion;  
  }
  $gestion->tipo = Input::get('tipo');
  $gestion->fecha = $fecha;
  $gestion->mes = $mes_lead;
  $gestion->name = Input::get('nombre');
  $gestion->last_name = Input::get('apellido');
  $gestion->empresa = Input::get('empresa');
  $gestion->address = Input::get('direccion');
  $gestion->slug = Str::slug($gestion->empresa);
  $gestion->nit = Input::get('nit');
  $gestion->email = Input:: get ('email');
  $gestion->phone = Input:: get ('numero');
  $gestion->interes = $onlyconsonants;
  $gestion->sector_id = Input:: get ('sector');
  $gestion->cantidad_id = Input:: get ('cantidad');
  $gestion->referido_id = Input:: get ('referido');
  $gestion->message = Input:: get ('comentarios');
  $gestion->country_id = Input:: get ('pais');
  $gestion->city_id = Input:: get ('ciudad');
  $gestion->funel_id = Input:: get ('tipo');
  $gestion->save();
  /*Mail::to(Input::get('email'))
  ->bcc('pruebas@hotmail.com')
  ->send(new Productos($gestion->slug));*/
  return Redirect('/ge/commercial')->with('status', 'ok_create');
 }

public function editarusuario($id){
  $interes = Input::get('interes');
  $data = json_encode($interes, true);
  $vowels = array('"', '[', ']');
  $onlyconsonants = str_replace($vowels, '', $data);
  if(!$this->tenantName){
  $gestion = Gestion::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Gestion::find($id); 
  }
  $gestion->tipo = Input::get('tipo');
  $gestion->fecha = date('Y-m-d', strtotime(Input::get('fecha')));
  $gestion->valor = Input::get('valor');
  $gestion->nombre = Input::get('nombre');
  $gestion->apellido = Input::get('apellido');
  $gestion->empresa = Input::get('empresa');
  $gestion->direccion = Input::get('direccion');
  $gestion->slug = Str::slug($gestion->empresa);
  $gestion->nit = Input::get('nit');
  $gestion->email = Input:: get ('email');
  $gestion->numero = Input:: get ('numero');
  $gestion->interes = $onlyconsonants;
  $gestion->sector_id = Input:: get ('sector');
  $gestion->cantidad_id = Input:: get ('cantidad');
  $gestion->referido_id = Input:: get ('referido');
  $gestion->comentarios = Input:: get ('comentarios');
  $gestion->pais_id = Input:: get ('pais');
  $gestion->ciudad_id = Input:: get ('ciudad');
  $gestion->save();
  return Redirect('/gestion/comercial')->with('status', 'ok_update');
 }

    public function crearfunel() {
  return view('gestion::crear-funel');
 }

 public function  updatecon($id){
  if(!$this->tenantName){
  $gestion = Cms_config::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Config::find($id); 
  }
  $gestion->empresa = Input::get('empresa');
  $gestion->direccion = Input::get('direccion');
  $gestion->telefono = Input::get('telefono');
  $gestion->correo = Input::get('correo');
  $gestion->website = Input::get('website');
  $gestion->logo = Input::get('logo');
  $gestion->img_01 = Input::get('imagen1');
  $gestion->img_02 = Input::get('imagen2');
  $gestion->presentacion = Input::get('presentacion');
  $gestion->color_principal = Input:: get ('color_principal');
  $gestion->color_secundario = Input:: get ('color_secundario');
  $gestion->save();
  return Redirect('/ge/configuration/1')->with('status', 'ok_update');
 }


 public function crearsector() {
  return view('gestion::crear-sector');
 }


  public function createrecepcion() {
  if(!$this->tenantName){
  $gestion = new Gestion;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Gestion;  
  }
  $gestion->nombre = Input::get('nombre');
  $gestion->apellido = Input::get('apellido');
  $gestion->empresa = Input::get('empresa');
  $gestion->direccion = Input::get('direccion');
  $gestion->slug = Str::slug($gestion->empresa);
  $gestion->nit = Input::get('nit');
  $gestion->email = Input:: get ('email');
  $gestion->numero = Input:: get ('numero');
  $gestion->interes = Input:: get ('interes');
  $gestion->sector_id = Input:: get ('sector');
  $gestion->cantidad_id = Input:: get ('cantidad');
  $gestion->referido_id = Input:: get ('utm_crm');
  $gestion->comentarios = Input:: get ('comentarios');
  $gestion->pais_id = Input:: get ('pais');
  $gestion->ciudad_id = Input:: get ('ciudad');
  $gestion->save();
  Mail::to(Input::get('email'))
  ->bcc('pruebas@hotmail.com')
  ->send(new Productos($gestion->slug));
  return Redirect('/gestion/comercial-recepcion')->with('status', 'ok_create');
 }



  public function registrarcantidad() {
    if(!$this->tenantName){
  $gestion = new Cms_cantidad;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_cantidad;  
  }
  $gestion->cantidad = Input::get('cantidad');
  $gestion->save();
  return Redirect('ge/quantities')->with('status', 'ok_create');
 }


  public function registrarmotivo() {
  if(!$this->tenantName){
  $gestion = new Motivo;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Motivo;  
  }
  $gestion->motivo = Input::get('motivo');
  $gestion->save();
  return Redirect('gestion/comercial/motivos')->with('status', 'ok_create');
 }

   public function registrarfunel() {
  if(!$this->tenantName){
  $gestion = new Cms_funel;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_funel;  
  }
  $gestion->funel = Input::get('funel');
  $gestion->color = Input::get('color');
  $gestion->save();
  return Redirect('/ge/embudo')->with('status', 'ok_create');
 }





  public function registrarsector() {
  if(!$this->tenantName){
  $gestion = new Cms_sector;
  }else{
   $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_sector; 
  }
  $gestion->sectores = Input::get('sector');
  $gestion->save();
  return Redirect('ge/sectors')->with('status', 'ok_create');
 }

 public function registrarreferido() {
   if(!$this->tenantName){
  $gestion = new Referido;
  }else{
    $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Referido;
  }
  $gestion->referidos = Input::get('referido');
  $gestion->save();
  return Redirect('/gestion/comercial/referidos')->with('status', 'ok_create');
 }

 public function configuracion($id) {
  if(!$this->tenantName){
    $configuracion = Cms_config::where('id','=',$id)->get();;
   }else{
    $configuracion = \Sitedigitalweb\Gestion\Tenant\Cms_config::where('id','=',$id)->get();
   }
  return view('gestion::configuracion')->with('configuracion', $configuracion);
 }

public function crearreferido() {
  return view('gestion::crear-referido');
 }

 public function crearcantidad() {
  return view('gestion::crear-cantidad');
 }

  public function crearmotivo() {
  return view('gestion::crear-motivo');
 }

  public function crearproducto($id) {

   if(!$this->tenantName){
    $productos = Cms_product::where('identificador','=',$id)->get();;
    }else{
    $productos = \DigitalsiteSaaS\Gestion\Tenant\Cms_product::where('identificador','=',$id)->get();
   }

  return view('gestion::crear-producto')->with('productos', $productos);
 }







 public function creaproduct() {
  if(!$this->tenantName){
  $gestion = new Cms_product;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_product;  
  }
  $gestion->iva = Input::get('iva');
  $gestion->identificador = Input::get('identificador');
  $gestion->posti = Input::get('cantidad');
  $gestion->precio = Input::get('precio');
  $gestion->producto = Input::get('producto');
  $gestion->descripcion = Input::get('descripcion');
  $gestion->propuesta_id = Input::get('propuesta');
  $gestion->moneda = Input::get('moneda');
  $gestion->valor_subtotal = $gestion->posti*$gestion->precio;
  $gestion->valor_iva = $gestion->valor_subtotal*$gestion->iva /100;
  $gestion->valor_total = $gestion->valor_subtotal+$gestion->valor_iva;
  $gestion->save();
  return Redirect('ge/create-product/'.$gestion->identificador.'?id='.$gestion->propuesta_id)->with('status', 'ok_create');
 }


 public function proupdate($id) {
  if(!$this->tenantName){
  $gestion = Product::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Product::find($id);
  }
  $gestion->iva = Input::get('iva');
  $gestion->identificador = Input::get('identificador');
  $gestion->posti = Input::get('cantidad');
  $gestion->precio = Input::get('precio');
  $gestion->producto = Input::get('producto');
  $gestion->descripcion = Input::get('descripcion');
  $gestion->propuesta_id = Input::get('propuesta_id');
  $gestion->moneda = Input::get('moneda');
  $gestion->valor_subtotal = $gestion->posti*$gestion->precio;
  $gestion->valor_iva = $gestion->valor_subtotal*$gestion->iva /100;
  $gestion->valor_total = $gestion->valor_subtotal+$gestion->valor_iva;
  $gestion->save();
  return Redirect('gestion/comercial/crear-producto/'.$gestion->identificador.'?id='.$gestion->propuesta_id)->with('status', 'ok_create');
 }


 public function productos(){
  if(!$this->tenantName){
  $productos = Cms_producto::all();
  }else{
     $productos = \Sitedigitalweb\Gestion\Tenant\Cms_producto::all();
  }
  return view('gestion::productos')->with('productos', $productos);
 }

 public function sectores(){
  if(!$this->tenantName){
  $sectores = Cms_sector::all();
  }else{
  $sectores = \DigitalsiteSaaS\Gestion\Tenant\Cms_sector::all(); 
  }
  return view('gestion::sectores')->with('sectores', $sectores);
 }

 public function cantidades(){
  if(!$this->tenantName){
  $cantidades = Cms_cantidad::all();
  }else{
    $cantidades = \DigitalsiteSaaS\Gestion\Tenant\Cms_cantidad::all();
  }
  return view('gestion::cantidades')->with('cantidades', $cantidades);
 }

 public function motivos(){
  if(!$this->tenantName){
  $motivos = Motivo::all();
  }else{
  $motivos = \DigitalsiteSaaS\Gestion\Tenant\Motivo::all();
  }
  return view('gestion::motivos')->with('motivos', $motivos);
 }

  public function funel(){
  if(!$this->tenantName){
  $funels = Cms_funel::all();
  }else{
  $funels = \DigitalsiteSaaS\Gestion\Tenant\Cms_funel::all();
  }
  return view('gestion::funels')->with('funels', $funels);
 }


 public function referidos(){
  if(!$this->tenantName){
  $referidos = Cms_referido::all();
  }else{
   $referidos = \DigitalsiteSaaS\Gestion\Tenant\Cms_referido::all(); 
  }
  return view('gestion::referidos')->with('referidos', $referidos);
 }


 public function dashboard(){

 $min_price = Input::has('min_price') ? Input::get('min_price') : 0;
 $max_price = Input::has('max_price') ? Input::get('max_price') : 100000;

$datoa = date('Y-m-d', strtotime($min_price));
$datob = date('Y-m-d', strtotime($max_price));

$total_usuarios = Cms_gestion::WhereBetween('fecha', array($datoa, $datob))->count();

$total_propuestas = Cms_product::all()
->sum('precio');


$total_proceso = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))
->leftjoin('cms_products','cms_propuestas.id','=','cms_products.propuesta_id')
->where('estado_propuesta','=','1')->where('moneda','=','1')->sum('precio');

$total_perdidas = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))
->leftjoin('cms_products','cms_propuestas.id','=','cms_products.propuesta_id')
->where('estado_propuesta','=','2')->where('moneda','=','1')->sum('precio');


$total_ganadas = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))
->leftjoin('cms_products','cms_propuestas.id','=','cms_products.propuesta_id')
->where('estado_propuesta','=','3')->where('moneda','=','1')->sum('precio');

$total_procesousd = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))
->leftjoin('cms_products','cms_propuestas.id','=','cms_products.propuesta_id')
->where('estado_propuesta','=','1')->where('moneda','=','2')->sum('precio');

$total_perdidasusd = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))
->leftjoin('cms_products','cms_propuestas.id','=','cms_products.propuesta_id')
->where('estado_propuesta','=','2')->where('moneda','=','2')->sum('precio');


$total_ganadasusd = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))
->leftjoin('cms_products','cms_propuestas.id','=','cms_products.propuesta_id')
->where('estado_propuesta','=','3')->where('moneda','=','2')->sum('precio');

$estado_usuario = Cms_gestion::whereBetween('fecha', array($datoa, $datob))
->leftjoin('cms_funel','cms_users.tipo','=','cms_funel.id')
->select('tipo','funel','color')
->selectRaw('count(tipo) as tipo_sum')
->groupBy('tipo')
->get();


$productos = Cms_gestion::whereBetween('fecha', array($datoa, $datob))
->leftjoin('pages','cms_users.interes','=','pages.id')
->select('page')
->selectRaw('count(page) as pages_sum')
->groupBy('page')
->orderBy('pages_sum', 'desc')
->get();

$sectores = Cms_gestion::whereBetween('fecha', array($datoa, $datob))
->leftjoin('cms_sector','cms_users.sector_id','=','cms_sector.id')
->select('sectores')
->selectRaw('count(sectores) as sectores_sum')
->groupBy('sectores')
->orderBy('sectores', 'desc')
->get();

$referidos = Cms_gestion::whereBetween('fecha', array($datoa, $datob))->leftjoin('cms_referidos','cms_users.referido_id','=','cms_referidos.id')
->select('referidos')
->selectRaw('count(referidos) as referidos_sum')
->groupBy('referidos')
->orderBy('referidos_sum', 'desc')
->get();

$cantidades = Cms_gestion::whereBetween('fecha', array($datoa, $datob))->leftjoin('cms_cantidad','cms_usuarios.cantidad_id','=','cms_cantidad.id')
->select('cantidad')
->selectRaw('count(cantidad) as cantidad_sum')
->groupBy('cantidad')
->orderBy('cantidad_sum', 'desc')
->get();

$ciudades = Cms_gestion::whereBetween('fecha', array($datoa, $datob))->leftjoin('departamentos','cms_usuarios.ciudad_id','=','departamentos.id')
->select('departamento')
->selectRaw('count(departamento) as ciudad_sum')
->groupBy('departamento')
->orderBy('ciudad_sum', 'desc')
->get();


$meses_lead = Cms_gestion::whereBetween('fecha', array($datoa, $datob))
->groupBy('mes')
->count();



$total_usuarios = Cms_gestion::whereBetween('fecha', array($datoa, $datob))->count();


$medios = Cms_propuesta::whereBetween('fecha_presentacion', array($datoa, $datob))->leftjoin('cms_referidos','cms_referidos.id','=','cms_propuestas.referido_id')
  ->leftjoin('cms_products','cms_products.propuesta_id','=','cms_propuestas.id')
  ->select(DB::raw('sum(precio) as precio'),
  DB::raw('referidos as referido'))
  ->orderBy('valor_propuesta', 'desc')
  ->groupBy('cms_referidos.id')
  ->get();


return view('gestion::dashboard')->with('total_usuarios', $total_usuarios)->with('total_perdidas', $total_perdidas)->with('estado_usuario', $estado_usuario)->with('productos', $productos)->with('referidos', $referidos)->with('ciudades', $ciudades)->with('total_propuestas', $total_propuestas)->with('total_proceso', $total_proceso)->with('total_ganadas', $total_ganadas)->with('cantidades', $cantidades)->with('medios', $medios)->with('sectores', $sectores)->with('total_perdidasusd', $total_perdidasusd)->with('total_procesousd', $total_procesousd)->with('total_ganadasusd', $total_ganadasusd);
 }

 


 public function edito($id){
   if(!$this->tenantName){
 $usuarios = Gestion::join('gestion_productos','gestion_usuarios.interes','=','gestion_productos.id')
 ->join('gestion_sector','gestion_usuarios.sector','=','gestion_sector.id')
 ->where('gestion_usuarios.id', '=', $id)->get();
 $productos = Producto::all();
 $sectores = Sector::all();
}else{
$usuarios = \DigitalsiteSaaS\Gestion\Tenant\Gestion::join('gestion_productos','gestion_usuarios.interes','=','gestion_productos.id')
 ->join('gestion_sector','gestion_usuarios.sector','=','gestion_sector.id')
 ->where('gestion_usuarios.id', '=', $id)->get();
 $productos = \DigitalsiteSaaS\Gestion\Tenant\Producto::all();
 $sectores = \DigitalsiteSaaS\Gestion\Tenant\Sector::all();

}
 return view('gestion::editar-registro')->with('productos', $productos)->with('sectores', $sectores)->with('usuarios', $usuarios);
}


 public function editarcan($id){
 if(!$this->tenantName){
 $cantidad = Cantidad::where('id', '=', $id)->get();
 }else{
 $cantidad = \DigitalsiteSaaS\Gestion\Tenant\Cantidad::where('id', '=', $id)->get();
 }
 return view('gestion::editar-cantidad')->with('cantidad', $cantidad);
}

 public function editarmot($id){
 if(!$this->tenantName){
 $motivo = Motivo::where('id', '=', $id)->get();
 }else{
 $motivo = \DigitalsiteSaaS\Gestion\Tenant\Motivo::where('id', '=', $id)->get();
 }
 return view('gestion::editar-motivo')->with('motivo', $motivo);
}

 public function editarfunel($id){
 if(!$this->tenantName){
 $funels = Funel::where('id', '=', $id)->get();
 }else{
 $funels = \DigitalsiteSaaS\Gestion\Tenant\Funel::where('id', '=', $id)->get();
 }
 return view('gestion::editar-funel')->with('funels', $funels);
}

 public function propuesta($id){
    if(!$this->tenantName){
        $propuesta = Cms_propuesta::leftJoin(
        'cms_productos','cms_propuestas.producto_servicio','=','cms_productos.id'
    )
    ->where('cms_propuestas.cms_user_id', $id)
    ->select(
        'cms_propuestas.id as propuesta_id',
        'cms_propuestas.fecha_presentacion',
        'cms_propuestas.asunto',
        'cms_propuestas.valor_propuesta',
        'cms_propuestas.producto_servicio',
        'cms_propuestas.presentacion',
        'cms_propuestas.tarifas',
        'cms_propuestas.identificador',
        'cms_propuestas.observaciones',
        'cms_propuestas.estado_propuesta',
        'cms_propuestas.referido_id',
        'cms_propuestas.cms_user_id',
        'cms_propuestas.motivo_id',
        'cms_propuestas.created_at',
        'cms_propuestas.updated_at',
        'cms_productos.producto'
    )
    ->get();

        foreach($propuesta as $propuestas){
            $items = str_replace('"', '', $propuestas->producto_servicio);
            $productos = Cms_producto::whereIn('id', [1, 2])->get();
        }
    } else {
        $propuesta = \Sitedigitalweb\Gestion\Tenant\Cms_propuesta::where(
                'cms_propuestas.cms_user_id', $id
            )
            ->get();

        foreach($propuesta as $propuestas){
            $items = str_replace('"', '', $propuestas->producto_servicio);
            $productos = \Sitedigitalweb\Gestion\Tenant\Cms_producto::whereIn('id', [1, 2])->get();
        }
    }

    return view('gestion::propuesta')->with('propuesta', $propuesta);
}



 public function editarpropuesta($id){
 if(!$this->tenantName){
 $propuesta = Propuesta::leftjoin('gestion_productos','gestion_propuestas.producto_servicio','=','gestion_productos.id')
 ->get();

 $intereses = Gestion::where('id','=',$id)->get();
 foreach ($intereses as $interes){
 $ideman = $interes->interes;
 $id_str = explode(',', $ideman);
 $productosa = Producto::whereIn('id', $id_str)->get();
 $productos = Producto::whereNotIn('id',$id_str)->get();

 }
 }else{
 $motivos = \DigitalsiteSaaS\Gestion\Tenant\Motivo::all();


  $propuesta = \DigitalsiteSaaS\Gestion\Tenant\Propuesta::leftjoin('gestion_motivo','gestion_propuestas.motivo_id','=','gestion_motivo.id')->where('gestion_propuestas.id','=',$id)
 ->get();


 foreach ($propuesta as $propuesta){

 $intereses = \DigitalsiteSaaS\Gestion\Tenant\Gestion::where('id','=',$propuesta->gestion_usuario_id)->get();
 }

 foreach ($intereses as $interes)
 $ideman = $interes->interes;
 $id_str = explode(',', $ideman);
 $productosa = \DigitalsiteSaaS\Gestion\Tenant\Producto::whereIn('id', $id_str)->get();
 $productos = \DigitalsiteSaaS\Gestion\Tenant\Producto::whereNotIn('id',$id_str)->get();

 }

 return view('gestion::editar-propuesta')->with('propuesta', $propuesta)->with('productos', $productos)->with('productosa', $productosa)->with('motivos', $motivos);
}

public function crearpropuesta($id){
 if(!$this->tenantName){
 $productos = Cms_producto::all(); 
 $motivos = Cms_motivo::all(); 
 }else{
 $productos = \DigitalsiteSaaS\Gestion\Tenant\Cms_producto::all(); 
 $motivos = \DigitalsiteSaaS\Gestion\Tenant\Cms_motivo::all(); 
 }
 return view('gestion::crear-propuesta')->with('productos', $productos)->with('motivos', $motivos);
}

 public function crearpropuestanew() {
  if(!$this->tenantName){
  $gestion = new Cms_propuesta;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Cms_propuesta;  
  }
  $gestion->estado_propuesta = Input::get('tipo');
  $gestion->valor_propuesta = Input::get('valor');
  $gestion->fecha_presentacion = Input::get('fecha');
  $gestion->asunto = Input::get('asunto');
  $gestion->presentacion = Input::get('presentacion');
  $gestion->tarifas = Input::get('tarifas');
  $gestion->identificador = Str::random(12);
  $gestion->producto_servicio = Input::get('intereses');
  $gestion->observaciones = Input::get('comentarios');
  $gestion->referido_id = Input::get('utm_referido');
  $gestion->cms_user_id = Input::get('cliente');
  $gestion->motivo_id = Input::get('motivos');
  $gestion->save();
  return Redirect('/ge/proposal/'.$gestion->cms_user_id)->with('status', 'ok_create');
 }

 public function portafolio($id){
 if(!$this->tenantName){
$empresa = Cms_propuesta::join('cms_users','cms_users.id', '=','cms_propuestas.cms_user_id')->where('cms_propuestas.identificador','=',$id)->get();
 $configuracion = Cms_config::where('id','=', 1)->get();
 $propuesta = Cms_product::where('identificador', '=', $id)->get();
 $subtotal = Cms_product::where('identificador', '=', $id)->sum('valor_subtotal');
 $iva = Cms_product::where('identificador', '=', $id)->sum('valor_iva');

 }else{
 $empresa = \DigitalsiteSaaS\Gestion\Tenant\Cms_propuesta::join('gestion_usuarios','gestion_usuarios.id', '=','gestion_propuestas.gestion_usuario_id')->where('gestion_propuestas.identificador','=',$id)->get();
 $configuracion = \DigitalsiteSaaS\Gestion\Tenant\Cms_config::where('id','=', 1)->get();
 $propuesta = \DigitalsiteSaaS\Gestion\Tenant\Cms_product::where('identificador', '=', $id)->get();
 $subtotal = \DigitalsiteSaaS\Gestion\Tenant\Cms_product::where('identificador', '=', $id)->sum('valor_subtotal');
 $iva = \DigitalsiteSaaS\Gestion\Tenant\Cms_product::where('identificador', '=', $id)->sum('valor_iva');
 }
  

 return view('gestion::portafolio', compact('empresa','configuracion','propuesta','subtotal','iva'));
}



public function editreferido($id){
 if(!$this->tenantName){
 $referido = Referido::where('id', '=', $id)->get();
 }else{
 $referido = \DigitalsiteSaaS\Gestion\Tenant\Referido::where('id', '=', $id)->get();
 }
 return view('gestion::editar-referido')->with('referido', $referido);
}

public function editsector($id){
 if(!$this->tenantName){
 $sector = Sector::where('id', '=', $id)->get();
 }else{
 $sector = \DigitalsiteSaaS\Gestion\Tenant\Sector::where('id', '=', $id)->get();
 }
 return view('gestion::editar-sector')->with('sector', $sector);
}

public function editproducto($id){
 if(!$this->tenantName){
 $producto = Producto::where('id', '=', $id)->get();
 }else{
 $producto = \DigitalsiteSaaS\Gestion\Tenant\Producto::where('id', '=', $id)->get();
 }
 return view('gestion::editar-producto')->with('producto', $producto);
}


public function valida(){
 if(!$this->tenantName){
 $user = Gestion::where('nit', Input::get('nit'))->count();
 }else{
 $user = \DigitalsiteSaaS\Gestion\Tenant\Gestion::where('nit', Input::get('nit'))->count();
 }
 if($user > 0) {
        $isAvailable = FALSE;
    } else {
        $isAvailable = TRUE;
    }
    echo json_encode(
            array(
                'valid' => $isAvailable
            )); 
 
}


public function editarproduct($id){
 if(!$this->tenantName){
 $productos = Product::where('id', "=", $id)->get();
 }else{
 $productos = \DigitalsiteSaaS\Gestion\Tenant\Product::where('id', "=", $id)->get();
 }
 return view('gestion::editar_product')->with('productos', $productos);
}



public function editrecepcion($id){
 if(!$this->tenantName){
 $usuario = Gestion::join('gestion_cantidad', 'gestion_cantidad.id', '=', 'gestion_usuarios.cantidad_id')
 ->leftjoin('gestion_referidos', 'gestion_referidos.id', '=', 'gestion_usuarios.referido_id')
 ->leftjoin('gestion_sector', 'gestion_sector.id', '=', 'gestion_usuarios.sector_id')
 ->leftjoin('gestion_productos', 'gestion_productos.id', '=', 'gestion_usuarios.interes')
 ->leftjoin('gestion_funel', 'gestion_funel.id', '=', 'gestion_usuarios.tipo')
 ->leftjoin('paises', 'paises.id', '=', 'gestion_usuarios.pais_id')
 ->leftjoin('departamentos', 'departamentos.id', '=', 'gestion_usuarios.ciudad_id')
 ->where('gestion_usuarios.id', '=', $id)->get();
 $sectores = Sector::all();
 $referidos = Referido::all();
 $cantidades = Cantidad::all();
 $paises = DB::table('paises')->orderBy('pais', 'ASC')->get();
 $intereses = Gestion::where('id','=',$id)->get();
 foreach ($intereses as $interes){
 $ideman = $interes->interes;
 $id_str = explode(',', $ideman);
 $productosa = Producto::whereIn('id', $id_str)->get();
 $productos = Producto::whereNotIn('id',$id_str)->get();
 $funels = Funel::all();

 }
 }else{
 $usuario = \DigitalsiteSaaS\Gestion\Tenant\Gestion::join('gestion_cantidad', 'gestion_cantidad.id', '=', 'gestion_usuarios.cantidad_id')
 ->leftjoin('gestion_referidos', 'gestion_referidos.id', '=', 'gestion_usuarios.referido_id')
 ->leftjoin('gestion_sector', 'gestion_sector.id', '=', 'gestion_usuarios.sector_id')
 ->leftjoin('gestion_productos', 'gestion_productos.id', '=', 'gestion_usuarios.interes')
 ->leftjoin('paises', 'paises.id', '=', 'gestion_usuarios.pais_id')
 ->leftjoin('gestion_funel', 'gestion_funel.id', '=', 'gestion_usuarios.tipo')
 ->leftjoin('departamentos', 'departamentos.id', '=', 'gestion_usuarios.ciudad_id')
 ->where('gestion_usuarios.id', '=', $id)->get();
 $sectores = \DigitalsiteSaaS\Gestion\Tenant\Sector::all();
 $referidos = \DigitalsiteSaaS\Gestion\Tenant\Referido::all();
 $cantidades = \DigitalsiteSaaS\Gestion\Tenant\Cantidad::all();
 $funels = \DigitalsiteSaaS\Gestion\Tenant\Funel::all();
 $paises = DB::table('paises')->orderBy('pais', 'ASC')->get();
 $intereses = \DigitalsiteSaaS\Gestion\Tenant\Gestion::where('id','=',$id)->get();
 foreach ($intereses as $interes){
 $ideman = $interes->interes;
 $id_str = explode(',', $ideman);
 $productosa = \DigitalsiteSaaS\Gestion\Tenant\Producto::whereIn('id', $id_str)->get();
 $productos = \DigitalsiteSaaS\Gestion\Tenant\Producto::whereNotIn('id',$id_str)->get();
 }
}
 return view('gestion::editar-usuario')->with('usuario', $usuario)->with('productos', $productos)->with('productosa', $productosa)->with('sectores', $sectores)->with('id_str', $id_str)->with('funels', $funels)->with('referidos', $referidos)->with('cantidades', $cantidades)->with('paises', $paises);
}





 public function recepcion(){
  if(!$this->tenantName){
  $usuarios = Gestion::all();
  $sectores = Sector::all();
  $productos = Producto::all();
  }else{
   $usuarios = \DigitalsiteSaaS\Gestion\Tenant\Gestion::all();
  $sectores = \DigitalsiteSaaS\Gestion\Tenant\Sector::all();
  $productos = \DigitalsiteSaaS\Gestion\Tenant\Producto::all(); 
  }
  return view('gestion::index-recepcion')->with('usuarios', $usuarios)->with('sectores', $sectores)->with('productos', $productos);
 }

 public function registrarecepcion() {
  if(!$this->tenantName){
  $gestion = new Gestion;
  }else{
  $gestion = new \DigitalsiteSaaS\Gestion\Tenant\Gestion;  
  }
  $gestion->nombre = Input::get('nombre');
  $gestion->apellido = Input::get('apellido');
  $gestion->empresa = Input::get('empresa');
  $gestion->nit = Input::get('nit');
  $gestion->email = Input:: get ('email');
  $gestion->numero = Input:: get ('numero');
  $gestion->interes = Input:: get ('interes');
  $gestion->interes = Input:: get ('sector');
  $gestion->save();
  return Redirect('/gestion/comercial')->with('status', 'ok_create');
 }

 public function edit($id){
  if(!$this->tenantName){
  $gestion = Gestion::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Gestion::find($id);  
  }
  $gestion->nombre = Input::get('nombre');
  $gestion->apellido = Input::get('apellido');
  $gestion->empresa = Input::get('empresa');
  $gestion->nit = Input::get('nit');
  $gestion->email = Input:: get ('email');
  $gestion->numero = Input:: get ('numero');
  $gestion->interes = Input:: get ('interes');
  $gestion->sector = Input:: get ('sector');
  $gestion->save();
  return Redirect('/gestion/comercial')->with('status', 'ok_update');
 }


  public function editarcantidad($id){
  if(!$this->tenantName){
  $gestion = Cantidad::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Cantidad::find($id);
  }    
  $gestion->cantidad = Input::get('cantidad');
  $gestion->save();
  return Redirect('/gestion/comercial/cantidades')->with('status', 'ok_update');
 }


  public function editarmotivo($id){
  if(!$this->tenantName){
  $gestion = Motivo::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Motivo::find($id);
  }    
  $gestion->motivo = Input::get('motivo');
  $gestion->save();
  return Redirect('/gestion/comercial/motivos')->with('status', 'ok_update');
 }

  public function editarfunelsa($id){
  if(!$this->tenantName){
  $gestion = Funel::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Funel::find($id);
  }    
  $gestion->funel = Input::get('funel');
  $gestion->color = Input::get('color');
  $gestion->save();
  return Redirect('/gestion/comercial/funel')->with('status', 'ok_update');
 }

  public function editarpropuestaa($id){
  $interes = Input::get('interes');
  $data = json_encode($interes, true);
  $vowels = array('"', '[', ']');
  $onlyconsonants = str_replace($vowels, '', $data);
  if(!$this->tenantName){
  $propuesta = Propuesta::find($id);
  }else{
  $propuesta = \DigitalsiteSaaS\Gestion\Tenant\Propuesta::find($id);
  }
  $propuesta->estado_propuesta = Input::get('tipo');
  $propuesta->valor_propuesta = Input::get('valor');
  $propuesta->fecha_presentacion = Input::get('fecha');
  $propuesta->tarifas = Input::get('tarifas');
  $propuesta->identificador = Input::get('identificador');
  $propuesta->asunto = Input::get('asunto');
  $propuesta->presentacion = Input::get('presentacion');
  $propuesta->producto_servicio = $onlyconsonants;
  $propuesta->observaciones = Input::get('comentarios');
  $propuesta->gestion_usuario_id = Input::get('cliente');
  $propuesta->motivo_id = Input::get('motivos');
  $propuesta->save();
  return Redirect('gestion/comercial/propuesta/'.$propuesta->gestion_usuario_id)->with('status', 'ok_update');
 }

 public function editarreferido($id){
  if(!$this->tenantName){
  $gestion = Referido::find($id); 
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Referido::find($id);   
  }
  $gestion->referidos = Input::get('referido');
  $gestion->save();
  return Redirect('/gestion/comercial/referidos')->with('status', 'ok_update');
 }

 public function editarsector($id){
  if(!$this->tenantName){
  $gestion = Sector::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Sector::find($id);  
  }
  $gestion->sectores = Input::get('sector');
  $gestion->save();
  return Redirect('/gestion/comercial/sectores')->with('status', 'ok_update');
 }

 public function editarproducto($id){
  if(!$this->tenantName){
  $gestion = Producto::find($id);
  }else{
     $gestion = \DigitalsiteSaaS\Gestion\Tenant\Producto::find($id);
  }
  $gestion->producto = Input::get('producto');
  $gestion->save();
  return Redirect('/gestion/comercial/productos')->with('status', 'ok_update');
 }

  public function editrec($id){
    if(!$this->tenantName){
  $gestion = Gestion::find($id);
  }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Gestion::find($id);   
  }
  $gestion->nombre = Input::get('nombre');
  $gestion->apellido = Input::get('apellido');
  $gestion->empresa = Input::get('empresa');
  $gestion->direccion = Input::get('direccion');
  $gestion->nit = Input::get('nit');
  $gestion->email = Input:: get ('email');
  $gestion->numero = Input:: get ('numero');
  $gestion->interes = Input:: get ('interes');
  $gestion->sector_id = Input:: get ('sector');
  $gestion->cantidad_id = Input:: get ('cantidad');
  $gestion->referido_id = Input:: get ('utm_crm');
  $gestion->comentarios = Input:: get ('comentarios');
  $gestion->pais_id = Input:: get ('pais');
  $gestion->ciudad_id = Input:: get ('ciudad');
  $gestion->save();
  return Redirect('/gestion/comercial-recepcion')->with('status', 'ok_update');
 }

  public function eliminar($id){
    if(!$this->tenantName){
   $gestion = Gestion::find($id);
 }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Gestion::find($id);
 }
   $gestion->delete();
 
  return Redirect('/gestion/comercial')->with('status', 'ok_delete');
 }

 public function eliminarproducto($id){
   if(!$this->tenantName){
   $gestion = Producto::find($id);
   }else{
 $gestion = \DigitalsiteSaaS\Gestion\Tenant\Producto::find($id);
   }
   $gestion->delete();
  return Redirect('/gestion/comercial/productos')->with('status', 'ok_delete');
 }

  public function eliminarproductopro($id){
   if(!$this->tenantName){
   $gestion = Product::find($id);
   }else{
  $gestion = \DigitalsiteSaaS\Gestion\Tenant\Product::find($id);

   }

   
   $gestion->delete();
  return Redirect('/gestion/comercial/crear-producto/'.$gestion->propuesta_id)->with('status', 'ok_delete');
 }

 public function eliminarsector($id){
   if(!$this->tenantName){
   $gestion = Sector::find($id);
   }else{
    $gestion = \DigitalsiteSaaS\Gestion\Tenant\Sector::find($id);
   }
   $gestion->delete();
  return Redirect('/gestion/comercial/sectores')->with('status', 'ok_delete');
 }

 public function eliminarreferido($id){
   if(!$this->tenantName){
   $gestion = Referido::find($id);
   }else{
   $gestion = \DigitalsiteSaaS\Gestion\Tenant\Referido::find($id); 
   }
   $gestion->delete();
  return Redirect('/gestion/comercial/referidos')->with('status', 'ok_delete');
 }

 public function eliminarcantidad($id){
   if(!$this->tenantName){
   $gestion = Cantidad::find($id);
   }else{
    $gestion = \DigitalsiteSaaS\Gestion\Tenant\Cantidad::find($id);
   }
   $gestion->delete();
  return Redirect('/gestion/comercial/cantidades')->with('status', 'ok_delete');
 }

  public function eliminarfunel($id){
   if(!$this->tenantName){
   $gestion = Funel::find($id);
   }else{
    $gestion = \DigitalsiteSaaS\Gestion\Tenant\Funel::find($id);
   }
   $gestion->delete();
  return Redirect('/gestion/comercial/funel')->with('status', 'ok_delete');
 }

}




