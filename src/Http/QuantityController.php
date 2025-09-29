<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuantityController extends Controller
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

    // Resolver modelo según tenant o global
    private function resolveModel()
    {
        return $this->tenantName
            ? \Sitedigitalweb\Gestion\Tenant\Cms_cantidad::class
            : \Sitedigitalweb\Gestion\Cms_cantidad::class;
    }

    // Listar cantidades
    public function index()
    {
        $model = $this->resolveModel();
        $cantidades = $model::all();
        return view('gestion::quantities.index', compact('cantidades'));
    }

    // Crear cantidad
    public function create()
    {
        return view('gestion::quantities.create');
    }

    // Guardar cantidad
    public function store(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|string|max:255',
        ]);

        $model = $this->resolveModel();
        $model::create([
            'cantidad' => $request->cantidad
        ]);

        return redirect()->route('ge.quantities.index')->with('status', 'ok_create');
    }

    // Editar cantidad
    public function edit($id)
    {
        $model = $this->resolveModel();
        $cantidad = $model::findOrFail($id);
        return view('gestion::quantities.edit', compact('cantidad'));
    }

    // Actualizar cantidad
    public function update(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|string|max:255',
        ]);

        $model = $this->resolveModel();
        $cantidad = $model::findOrFail($id);
        $cantidad->update([
            'cantidad' => $request->cantidad
        ]);

        return redirect()->route('ge.quantities.index')->with('status', 'ok_update');
    }

    // Eliminar cantidad
    public function destroy($id)
    {
        $model = $this->resolveModel();
        $cantidad = $model::findOrFail($id);
        $cantidad->delete();

        return redirect()->route('ge.quantities.index')->with('status', 'ok_delete');
    }
}
