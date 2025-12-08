<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Str;
class UserController extends Controller
{
    protected $tenantName = null;

    public function __construct()
    {
        if (!session()->has('cart')) {
            session()->put('cart', []);
        }

        $hostname = app(\Hyn\Tenancy\Environment::class)->hostname();
        if ($hostname) {
            $fqdn = $hostname->fqdn;
            $this->tenantName = explode(".", $fqdn)[0];
        }
    }

    // Resolver el modelo según tenant o base principal
    private function resolveModel($modelName)
    {
    $namespace = $this->tenantName
        ? "Sitedigitalweb\\Gestion\\Tenant\\"
        : "Sitedigitalweb\\Gestion\\";

    return $namespace . $modelName;
    }
     private function resolveModelA($modelName)
    {
    $namespace = $this->tenantName
        ? "Sitedigitalweb\\Pagina\\Tenant\\"
        : "Sitedigitalweb\\Pagina\\";

    return $namespace . $modelName;
    }

    // Listado de productos
    public function index()
    {
    // Resolver modelos dinámicamente
    $gestionModel = $this->resolveModel('Cms_gestion');
    $funelModel   = $this->resolveModel('Cms_funel');

    // Traer usuarios (solo columnas necesarias)
    $usuarios = $gestionModel::select('id','name','last_name','empresa','email','phone','funel_id','created_at')
        ->latest('created_at')
        ->get();

    // Traer funels (con color)
    $funels = $funelModel::select('id','funel','color')
        ->orderBy('id')
        ->get();

    return view('gestion::users.index', compact('usuarios','funels'));
    }

  
public function indexlist() {
    $gestionModel = $this->resolveModel('Cms_gestion');
    
    // Solo lo esencial para la primera carga
    $usuarios = $gestionModel::select([
            'id', 
            'name', 
            'email', 
            'phone',
            'funel_id',
            'sector_id',
            'created_at'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Cargar el resto cuando sea necesario (puedes moverlo a otra función)
    $sectorModel = $this->resolveModel('Cms_sector');
    $productoModel = $this->resolveModel('Cms_producto');
    $referidoModel = $this->resolveModel('Cms_referido');
    $funelModel = $this->resolveModel('Cms_funel');
    $pageModel = $this->resolveModelA('Page');
    
    $sectores = $sectorModel::select(['id', 'sectores'])->get();
    $productos = $productoModel::select(['id', 'producto'])->get();
    $referidos = $referidoModel::select(['id', 'referidos'])->get();
    $funels = $funelModel::select(['id', 'funel', 'color'])->get();
    $interes = $pageModel::select(['id', 'title'])->get();
    
    return view('gestion::users.indexlist', compact('usuarios','sectores','productos','referidos','funels','interes'));
}

    // Crear producto
    public function create()
    {

    $productos  = ($this->resolveModel('Cms_producto'))::all();
    $sectores   = ($this->resolveModel('Cms_sector'))::all();
    $referidos  = ($this->resolveModel('Cms_referido'))::all();
    $cantidades = ($this->resolveModel('Cms_cantidad'))::all();
    $funels     = ($this->resolveModel('Cms_funel'))::all();
    $paises     = ($this->resolveModel('Cms_pais'))::orderBy('pais', 'ASC')->get();

    return view('gestion::user.create', compact(
        'productos',
        'sectores',
        'referidos',
        'cantidades',
        'paises',
        'funels'
    ));

    }

    // Guardar producto
    public function store(Request $request)
    {
         // Obtener datos del request
    $interes = request()->get('interes');
    $fechaInput = request()->get('fecha');

    // Manejo de fecha (si no viene, se usa la actual)
    $fecha = $fechaInput 
        ? date('Y-m-d', strtotime($fechaInput)) 
        : date('Y-m-d');

    $mes_lead = date('M', strtotime($fecha));

    // Procesar intereses en formato string limpio
    $data = json_encode($interes, true);
    $onlyconsonants = str_replace(['"', '[', ']'], '', $data);

    // Resolver modelo según tenant
    $gestionClass = $this->resolveModel('Cms_gestion');
    $gestion = new $gestionClass;

    // Asignar valores
    $gestion->tipo        = request()->get('tipo');
    $gestion->fecha       = $fecha;
    $gestion->mes         = $mes_lead;
    $gestion->name        = request()->get('nombre');
    $gestion->last_name   = request()->get('apellido');
    $gestion->empresa     = request()->get('empresa');
    $gestion->address     = request()->get('direccion');
    $gestion->slug        = Str::slug(request()->get('empresa'));
    $gestion->nit         = request()->get('nit');
    $gestion->email       = request()->get('email');
    $gestion->phone       = request()->get('numero');
    $gestion->interes     = $onlyconsonants;
    $gestion->sector_id   = request()->get('sector');
    $gestion->cantidad_id = request()->get('cantidad');
    $gestion->referido_id = request()->get('referido');
    $gestion->message     = request()->get('comentarios');
    $gestion->country_id  = request()->get('pais');
    $gestion->city_id     = request()->get('ciudad');
    $gestion->funel_id    = request()->get('tipo'); // OJO: revisa si realmente debe ser `tipo`

    // Guardar
    $gestion->save();

    return redirect('/ge/commercial')->with('status', 'ok_create');
    }

    // Mostrar producto individual
    public function show($id)
    {
        $model = $this->resolveModel();
        $producto = $model::findOrFail($id);
        return view('gestion::products.show', compact('producto'));
    }

   public function edit($id)
{
    try {
        // Resolver modelos
        $gestionClass = $this->resolveModel('Cms_gestion');
        
        // Obtener el usuario principal
        $usuario = $gestionClass::findOrFail($id);
        
        // Resolver otros modelos
        $sectorClass    = $this->resolveModel('Cms_sector');
        $referidoClass  = $this->resolveModel('Cms_referido');
        $cantidadClass  = $this->resolveModel('Cms_cantidad');
        $productoClass  = $this->resolveModel('Cms_producto');
        $funelClass     = $this->resolveModel('Cms_funel');
        $paisesClass    = $this->resolveModel('Cms_pais');
        
        // Catálogos básicos
        $sectores   = $sectorClass::all();
        $referidos  = $referidoClass::all();
        $cantidades = $cantidadClass::all();
        $funels     = $funelClass::all();
        $paises     = $paisesClass::orderBy('pais', 'ASC')->get();
        
        // Procesar intereses
        $productosa = collect(); 
        $productos  = collect();
        $id_str     = [];
        
        if ($usuario->interes) {
            $id_str = explode(',', $usuario->interes);
            $productosa = $productoClass::whereIn('id', $id_str)->get();
            $productos = $productoClass::whereNotIn('id', $id_str)->get();
        }
        
        return view('gestion::users.edit', compact(
            'usuario', 'productos', 'productosa', 'sectores',
            'id_str', 'funels', 'referidos', 'cantidades', 'paises'
        ));
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        abort(404, 'Usuario no encontrado');
    }
}

    // Actualizar producto
    public function update(Request $request, $id)
    {
       // Resolver modelo de gestión
    $gestionClass = $this->resolveModel('Cms_gestion');

    // Procesar intereses
    $interes = Input::get('interes');
    $data = json_encode($interes, true);
    $onlyconsonants = str_replace(['"', '[', ']'], '', $data);

    // Buscar el usuario por ID
    $gestion = $gestionClass::findOrFail($id);

    // Asignación de campos
    $gestion->tipo        = Input::get('tipo');
    $gestion->fecha       = date('Y-m-d', strtotime(Input::get('fecha')));
    $gestion->name        = Input::get('nombre');
    $gestion->last_name   = Input::get('apellido');
    $gestion->empresa     = Input::get('empresa');
    $gestion->address     = Input::get('direccion');
    $gestion->slug        = Str::slug($gestion->empresa);
    $gestion->nit         = Input::get('nit');
    $gestion->email       = Input::get('email');
    $gestion->phone       = Input::get('numero');
    $gestion->interes     = $onlyconsonants;
    $gestion->sector_id   = Input::get('sector') ?? '1';
    $gestion->cantidad_id = Input::get('cantidad') ?? '1';
    $gestion->referido_id = Input::get('referido') ?? '1';
    $gestion->message     = Input::get('comentarios');
    $gestion->country_id  = Input::get('pais') ?? '1';
    $gestion->city_id     = Input::get('ciudad') ?? '1';
    $gestion->funel_id    = Input::get('tipo') ?? '1';

    $gestion->save();

    return redirect('/ge/commercial-list')->with('status', 'ok_update');
    }

    public function destroy($id)
{
    // Resolver el modelo Cms_user
    $userClass = $this->resolveModel('Cms_gestion');

    // Buscar el usuario por ID o lanzar 404
    $usuario = $userClass::findOrFail($id);

    // Eliminar
    $usuario->delete();

    // Redirigir con mensaje de confirmación
    return redirect('/ge/commercial-list')->with('status', 'ok_delete');
}
}
