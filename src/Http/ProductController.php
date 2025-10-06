<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
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
            ? \Sitedigitalweb\Gestion\Tenant\Cms_product::class
            : \Sitedigitalweb\Gestion\Cms_product::class;
    }

    // Listado de productos
    public function index()
    {
     return view('gestion::product.index');
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
    public function update(Request $request, $id)
    {
         // Selección del modelo según tenant
    $productClass = $this->tenantName 
        ? \Sitedigitalweb\Gestion\Tenant\Cms_product::class 
        : \Sitedigitalweb\Gestion\Cms_product::class;

    $gestion = $productClass::findOrFail($id);

    // Asignación masiva con fillable en el modelo
    $gestion->fill([
        'iva'           => $request->input('iva'),
        'identificador' => $request->input('identificador'),
        'posti'         => $request->input('cantidad'),
        'precio'        => $request->input('precio'),
        'producto'      => $request->input('producto'),
        'descripcion'   => $request->input('descripcion'),
        'propuesta_id'  => $request->input('propuesta'),
        'moneda'        => $request->input('moneda'),
    ]);

    // Cálculos
    $gestion->valor_subtotal = $gestion->posti * $gestion->precio;
    $gestion->valor_iva      = ($gestion->valor_subtotal * $gestion->iva) / 100;
    $gestion->valor_total    = $gestion->valor_subtotal + $gestion->valor_iva;

    $gestion->save();

    return redirect()
        ->to("ge/product/{$request->input('identificador')}?id={$request->input('propuesta')}")
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