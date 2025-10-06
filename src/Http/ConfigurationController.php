<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
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
    private function resolveModel()
    {
        return $this->tenantName
            ? \Sitedigitalweb\Gestion\Tenant\Cms_config::class
            : \Sitedigitalweb\Gestion\Cms_config::class;
    }


    public function index()
    {
    $configModel = $this->resolveModel('Cms_config');
    $configuracion = $configModel::where('id',1)->get();

    return view('gestion::configuration.index', compact('configuracion'));
    }
    // Crear producto
    public function create()
    {
        return view('gestion::products.create');
    }

    // Guardar producto
    public function store(Request $request)
    {
          // Resolver modelo dinámicamente según tenant
    $productoModel = $this->resolveModel('Cms_product');

    // Crear nueva instancia
    $gestion = new $productoModel();

    // Asignar valores desde la request (con fallback en caso de null)
    $gestion->iva          = request('iva', 0);
    $gestion->identificador = request('identificador');
    $gestion->posti        = request('cantidad', 0);
    $gestion->precio       = request('precio', 0);
    $gestion->producto     = request('producto');
    $gestion->descripcion  = request('descripcion');
    $gestion->propuesta_id = request('propuesta');
    $gestion->moneda       = request('moneda', 'COP'); // valor por defecto

    // Calcular valores derivados
    $subtotal   = $gestion->posti * $gestion->precio;
    $iva        = ($subtotal * $gestion->iva) / 100;
    $total      = $subtotal + $iva;

    $gestion->valor_subtotal = $subtotal;
    $gestion->valor_iva      = $iva;
    $gestion->valor_total    = $total;

    // Guardar en BD
    $gestion->save();

    // Redirigir con mensaje
    return redirect()
        ->to('ge/product/'.$gestion->identificador.'?id='.$gestion->propuesta_id)
        ->with('status', 'ok_create');
    }


    public function show($id)
{
    $productoModel = $this->resolveModel('Cms_product');

    $productos = $productoModel::where('identificador', $id)->get();

    return view('gestion::product.show', compact('productos'));
}


    // Editar producto
    public function edit($id)
    {
         // Resolver modelo dinámico
    $productModel = $this->resolveModel('Cms_Product');

    // Buscar producto por ID
    $productos = $productModel::findOrFail($id);

    // Retornar vista con un solo registro
    return view('gestion::product.edit', compact('productos'));
    }

    // Actualizar producto
    public function update(Request $request)
{
// Resolver el modelo dinámicamente
$configModel = $this->tenantName
? \Sitedigitalweb\Gestion\Tenant\Config::class
: \Sitedigitalweb\Gestion\Cms_config::class;

// Siempre actualizar el registro con ID = 1
$gestion = $configModel::findOrFail(1);

// Validación
$request->validate([
    'empresa'           => 'nullable|string|max:255',
    'direccion'         => 'nullable|string|max:255',
    'telefono'          => 'nullable|string|max:50',
    'correo'            => 'nullable|email|max:255',
    'website'           => 'nullable|url|max:255',
    'logo'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'imagen1'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'imagen2'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'presentacion'      => 'nullable|string',
    'color_principal'   => 'nullable|string|max:50',
    'color_secundario'  => 'nullable|string|max:50',
]);

// Carpeta de destino
$destinationPath = public_path('graptemplate');
if (!file_exists($destinationPath)) {
    mkdir($destinationPath, 0777, true);
}

// Subida de imágenes
if ($request->hasFile('logo')) {
    $fileName = time().'_'.$request->file('logo')->getClientOriginalName();
    $request->file('logo')->move($destinationPath, $fileName);
    $gestion->logo = 'graptemplate/' . $fileName;
}

if ($request->hasFile('imagen1')) {
    $fileName = time().'_'.$request->file('imagen1')->getClientOriginalName();
    $request->file('imagen1')->move($destinationPath, $fileName);
    $gestion->img_01 = 'graptemplate/' . $fileName;
}

if ($request->hasFile('imagen2')) {
    $fileName = time().'_'.$request->file('imagen2')->getClientOriginalName();
    $request->file('imagen2')->move($destinationPath, $fileName);
    $gestion->img_02 = 'graptemplate/' . $fileName;
}

// Actualizar campos de texto
$gestion->fill($request->only([
    'empresa',
    'direccion',
    'telefono',
    'correo',
    'website',
    'presentacion',
    'color_principal',
    'color_secundario',
]));

// Guardar
$gestion->save();

return redirect()->to("ge/configuration")
                 ->with('status', 'ok_update');


}


    // Eliminar producto
    public function destroy($id)
    {
        $model = $this->resolveModel();
        $producto = $model::findOrFail($id);
        $producto->delete();
        return redirect()
        ->to("ge/product/{$producto->identificador}?id={$producto->propuesta_id}")
        ->with('status', 'ok_delete');
      
    }
      }