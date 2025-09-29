<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferidoController extends Controller
{
    protected $tenantName = null;

    public function __construct()
    {
        // Inicializa tenant si existe
        if (!session()->has('cart')) session()->put('cart', []);
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
            ? \Sitedigitalweb\Gestion\Tenant\Cms_referido::class
            : \Sitedigitalweb\Gestion\Cms_referido::class;
    }

    // Listar referidos
    public function index()
    {
        $model = $this->resolveModel();
        $referidos = $model::all();
        return view('gestion::referidos.index', compact('referidos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('gestion::referidos.create');
    }

    // Guardar nuevo referido
    public function store(Request $request)
    {
        $request->validate([
            'referidos' => 'required|string|max:255',
        ]);

        $model = $this->resolveModel();
        $model::create($request->only('referidos'));

        return redirect()->route('ge.referrals.index')->with('status', 'ok_create');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $model = $this->resolveModel();
        $referido = $model::findOrFail($id);
        return view('gestion::referidos.edit', compact('referido'));
    }

    // Actualizar referido
    public function update(Request $request, $id)
    {
        $request->validate([
            'referidos' => 'required|string|max:255',
        ]);

        $model = $this->resolveModel();
        $referido = $model::findOrFail($id);
        $referido->update($request->only('referidos'));

        return redirect()->route('ge.referrals.index')->with('status', 'ok_update');
    }

    // Eliminar referido
    public function destroy($id)
    {
        $model = $this->resolveModel();
        $referido = $model::findOrFail($id);
        $referido->delete();

        return redirect()->route('ge.referrals.index')->with('status', 'ok_delete');
    }
}
