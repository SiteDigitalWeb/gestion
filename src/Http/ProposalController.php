<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Str;
class ProposalController extends Controller
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
    
    public function index()
    {
    // resolver clases según tenant
    $propuestaClass = $this->resolveModel('Cms_propuesta');
    $productoClass  = $this->resolveModel('Cms_producto');

    // 1) Traer propuestas (con join solo en entorno no-tenant si lo necesitas)
    if (!$this->tenantName) {
        // usamos query builder del modelo (query() devuelve Builder)
        $query = $propuestaClass::query()
            ->leftJoin('cms_productos', 'cms_propuestas.producto_servicio', '=', 'cms_productos.id')
            ->select(
                'cms_propuestas.*',
                'cms_productos.producto as producto_nombre' // si quieres info de producto en la misma fila
            );

        $propuestas = $query->get();
    } else {
        // en tenant evitamos join (puedes adaptarlo si la estructura tenant usa otras tablas)
        $propuestas = $propuestaClass::all();
    }

    // 2) Extraer todos los IDs de producto (parsear campo producto_servicio)
    $allIds = [];
    foreach ($propuestas as $prop) {
        $ids = $this->parseProductoServiceIds($prop->producto_servicio ?? '');
        $prop->producto_ids = $ids; // guardar ids para cada propuesta
        $allIds = array_merge($allIds, $ids);
    }
    $allIds = array_unique($allIds);

    // 3) Traer todos los productos relacionados con una sola consulta
    $productsMap = $allIds
        ? $productoClass::whereIn('id', $allIds)->get()->keyBy('id')
        : collect();

    // 4) Adjuntar objetos producto a cada propuesta
    foreach ($propuestas as $prop) {
        $prop->productos = collect();
        foreach ($prop->producto_ids as $pid) {
            if ($productsMap->has($pid)) {
                $prop->productos->push($productsMap->get($pid));
            }
        }
    }
    // 5) Enviar a la vista
    return view('gestion::proposal.index', compact('propuestas'));
    }

    private function parseProductoServiceIds($raw)
    {
    // intentar json_decode primero
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        return array_map('intval', array_values(array_filter($decoded, function($v){
            return $v !== null && $v !== '';
        })));
    }

    // limpiar caracteres no numéricos salvo coma
    $clean = preg_replace('/[^\d,]/', '', $raw);
    if ($clean === '') return [];

    $parts = array_filter(explode(',', $clean));
    return array_map('intval', $parts);
    }


    // Crear producto
    public function create()
    {
    // Resolver modelos dinámicamente
    $productoClass = $this->resolveModel('Cms_producto');
    $motivoClass   = $this->resolveModel('Cms_motivo');
    $clienteClass   = $this->resolveModel('Cms_gestion');

    // Consultar datos
    $productos = $productoClass::all();
    $motivos   = $motivoClass::all();
    $clientes   = $clienteClass::all();

    // Pasar a la vista
    return view('gestion::proposal.create', compact('productos', 'motivos', 'clientes'));
    }

    // Guardar producto
    public function store(Request $request)
    {
    // Obtener datos del request
    // Resolver el modelo según tenant
    $model = $this->resolveModel('Cms_propuesta');
    $gestion = new $model();

    // Asignación masiva en lugar de Input::get()
    $gestion->estado_propuesta   = $request->input('tipo');
    $gestion->valor_propuesta    = $request->input('valor');
    $gestion->fecha_presentacion = $request->input('fecha');
    $gestion->asunto             = $request->input('asunto');
    $gestion->presentacion       = $request->input('presentacion');
    $gestion->tarifas            = $request->input('tarifas');
    $gestion->identificador      = Str::random(12);
    $gestion->producto_servicio  = $request->input('intereses');
    $gestion->observaciones      = $request->input('comentarios');
    $gestion->referido_id        = $request->input('utm_referido');
    $gestion->cms_user_id        = $request->input('cliente');
    $gestion->motivo_id          = $request->input('motivos');

    $gestion->save();
 
    return redirect('/ge/proposal')->with('status', 'ok_create');
    }

    // Mostrar producto individual
    public function show($id)
    {
        $model = $this->resolveModel();
        $producto = $model::findOrFail($id);
        return view('gestion::products.show', compact('producto'));
    }

    // Editar producto
   public function edit($id)
{
    // Resolver modelos según tenant
    $propuestaModel = $this->resolveModel('Cms_propuesta');
    $gestionModel   = $this->resolveModel('Cms_gestion');
    $productoModel  = $this->resolveModel('Cms_producto');
    $motivoModel    = $this->resolveModel('Cms_motivo');

    // Obtener propuesta con su motivo (LEFT JOIN porque puede ser null)
    $propuesta = $propuestaModel::query()
        ->leftJoin('cms_motivo', 'cms_propuestas.motivo_id', '=', 'cms_motivo.id')
        ->where('cms_propuestas.id', $id)
        ->select('cms_propuestas.*', 'cms_motivo.motivo as motivo_nombre')
        ->firstOrFail();

    // Buscar gestión asociada a la propuesta
    $gestion = $gestionModel::find($propuesta->cms_user_id);

    // Obtener intereses del usuario (si existen)
    $intereses = $gestion ? array_filter(explode(',', $gestion->interes ?? '')) : [];

    // Productos seleccionados y no seleccionados
    $productosSeleccionados = $productoModel::whereIn('id', $intereses)->get();
    $productosDisponibles   = $productoModel::when($intereses, function ($query) use ($intereses) {
        return $query->whereNotIn('id', $intereses);
    })->get();

    // Motivos disponibles
    $motivos = $motivoModel::all();

    return view('gestion::proposal.edit', [
        'propuesta'  => $propuesta,
        'productos'  => $productosDisponibles,
        'productosa' => $productosSeleccionados,
        'motivos'    => $motivos,
    ]);
}


    // Actualizar producto
    public function update(Request $request, $id)
    {
      $request = request();

    // Procesar intereses (interes[])
    $intereses = $request->input('interes', []);
    $productoServicio = is_array($intereses) ? implode(',', $intereses) : '';

    // Resolver modelo según tenant
    $propuestaModel = $this->tenantName
        ? \Sitedigitalweb\Gestion\Tenant\Cms_propuesta::class
        : \Sitedigitalweb\Gestion\Cms_propuesta::class;

    $propuesta = $propuestaModel::findOrFail($id);

    // Actualizar campos
    $propuesta->fill([
        'estado_propuesta'   => $request->input('tipo'),
        'valor_propuesta'    => $request->input('valor'),
        'fecha_presentacion' => $request->input('fecha'),
        'tarifas'            => $request->input('tarifas'),
        'identificador'      => $request->input('identificador'),
        'asunto'             => $request->input('asunto'),
        'presentacion'       => $request->input('presentacion'),
        'producto_servicio'  => $productoServicio,
        'observaciones'      => $request->input('comentarios'),
        'cms_user_id' => $request->input('cliente'),
        'motivo_id'          => $request->input('motivos'),
    ]);

    $propuesta->save();


    return redirect('/ge/proposal')->with('status', 'ok_update');
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