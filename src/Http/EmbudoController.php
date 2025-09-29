<?php

namespace Sitedigitalweb\Gestion\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sitedigitalweb\Gestion\CmsEmbudo;

class EmbudoController extends Controller
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

    private function resolveModel()
    {
        return $this->tenantName
            ? \Sitedigitalweb\Gestion\Tenant\Cms_funel::class
            : \Sitedigitalweb\Gestion\Cms_funel::class;
    }

    public function index()
    {
    $model = $this->resolveModel();
    $funels = $model::all();
      return view('gestion::embudos.index', compact('funels'));
    }

    public function create()
    {
        return view('gestion::embudos.create');
    }

    public function store(Request $request)
{
    // Validaciones
    $request->validate([
        'funel' => 'required|string|max:255',
        'color' => 'required|string|max:7', // usualmente color es #FFFFFF
    ]);

    // Resuelve el modelo según tenant o base principal
    $model = $this->resolveModel();

    // Crea el registro
    $model::create([
        'funel' => $request->funel,
        'color' => $request->color,
    ]);

    // Redirecciona con mensaje de éxito
    return redirect('ge/embudo')->with('status', 'ok_create');
}

    public function edit($id)
    {
       $model = $this->resolveModel();
       $funels = $model::findOrFail($id); // devuelve 404 si no existe
       return view('gestion::embudos.edit', compact('funels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'funel' => 'required|string|max:255',
            'color' => 'required|string|max:1000'
        ]);
        $model = $this->resolveModel();
        $embudo = $model::findOrFail($id);

        $embudo->update([
        'funel' => $request->funel,
        'color' => $request->color,
         ]);

        return redirect('ge/embudo')->with('success', 'Producto creado correctamente.');
    }

    public function destroy($id)
    {
         $model = $this->resolveModel();
         $embudo = $model::findOrFail($id);
         $embudo->delete();

        return redirect('ge/embudo')->with('status', 'ok_delete');
    }
}
