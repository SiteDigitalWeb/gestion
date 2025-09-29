<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductoController extends Controller
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
            ? \Sitedigitalweb\Gestion\Tenant\Cms_producto::class
            : \Sitedigitalweb\Gestion\Cms_producto::class;
    }

    // Listado de productos
    public function index()
    {
        $model = $this->resolveModel();
        $productos = $model::all();
        return view('gestion::products.index', compact('productos'));
    }

    // Crear producto
    public function create()
    {
        return view('gestion::products.create');
    }

    // Guardar producto
    public function store(Request $request)
    {
        $request->validate([
            'producto'      => 'required|string|max:255',
            'identificador' => 'nullable|string|max:100',
        ]);

        $model = $this->resolveModel();
        $model::create([
            'producto'      => $request->producto,
            'identificador' => $request->identificador,
        ]);

        return redirect()->route('ge.products.index')->with('status', 'ok_create');
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
        $model = $this->resolveModel();
        $producto = $model::findOrFail($id);
        return view('gestion::products.edit', compact('producto'));
    }

    // Actualizar producto
    public function update(Request $request, $id)
    {
        $request->validate([
            'producto'      => 'required|string|max:255',
            'identificador' => 'nullable|string|max:100',
        ]);

        $model = $this->resolveModel();
        $producto = $model::findOrFail($id);

        $producto->update([
            'producto'      => $request->producto,
            'identificador' => $request->identificador,
        ]);

        return redirect()->route('ge.products.index')->with('status', 'ok_update');
    }

    // Eliminar producto
    public function destroy($id)
    {
        $model = $this->resolveModel();
        $producto = $model::findOrFail($id);
        $producto->delete();

        return redirect()->route('ge.products.index')->with('status', 'ok_delete');
    }
}

