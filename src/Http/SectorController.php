<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectorController extends Controller
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
            ? \Sitedigitalweb\Gestion\Tenant\Cms_sector::class
            : \Sitedigitalweb\Gestion\Cms_sector::class;
    }

    // Listar sectores
    public function index()
    {
        $model = $this->resolveModel();
        $sectores = $model::all();
        return view('gestion::sectors.index', compact('sectores'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('gestion::sectors.create');
    }

    // Guardar nuevo sector
    public function store(Request $request)
    {
        $request->validate([
            'sectores' => 'required|string|max:255',
        ]);

        $model = $this->resolveModel();
        $model::create($request->only('sectores'));

        return redirect()->route('ge.sectors.index')->with('status', 'ok_create');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $model = $this->resolveModel();
        $sector = $model::findOrFail($id);

        return view('gestion::sectors.edit', compact('sector'));
    }

    // Actualizar sector
    public function update(Request $request, $id)
    {
        $request->validate([
            'sectores' => 'required|string|max:255',
        ]);

        $model = $this->resolveModel();
        $sector = $model::findOrFail($id);
        $sector->update($request->only('sectores'));

        return redirect()->route('ge.sectors.index')->with('status', 'ok_update');
    }

    // Eliminar sector
    public function destroy($id)
    {
        $model = $this->resolveModel();
        $sector = $model::findOrFail($id);
        $sector->delete();

        return redirect()->route('ge.sectors.index')->with('status', 'ok_delete');
    }
}
