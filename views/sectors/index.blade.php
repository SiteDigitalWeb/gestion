@extends ('adminsite.layout')

@section('cabecera')
@parent
@stop

@section('ContenidoSite-01')

<div class="content-header">
 <ul class="nav-horizontal text-center">
  <li>
   <a href="/ge/register-user"><i class="fa fa-user-plus"></i> Registrar Datos Usuario</a>
  </li>
  <li>
   <a href="/ge/commercial"><i class="fa fa-list-ul"></i> Usuarios</a>
  </li>
   <li>
   <a href="/ge/sectors/create"><i class="fa fa-list-ul"></i>Crear Sector</a>
  </li>
  
 </ul>
</div>

<div class="container">
 @if(session('status') == 'ok_create')
  <div class="alert alert-success">
   <button type="button" class="close" data-dismiss="alert">&times;</button>
   <strong>Sector registrado con éxito</strong> CMS...
  </div>
 @endif

 @if(session('status') == 'ok_delete')
  <div class="alert alert-danger">
   <button type="button" class="close" data-dismiss="alert">&times;</button>
   <strong>Sector eliminado con éxito</strong> CMS...
  </div>
 @endif

 @if(session('status') == 'ok_update')
  <div class="alert alert-warning">
   <button type="button" class="close" data-dismiss="alert">&times;</button>
   <strong>Sector actualizado con éxito</strong> CMS...
  </div>
 @endif
</div>

<div class="container">
 <div class="container-fluid">
  <a href="{{ route('ge.sectors.create') }}" class="btn btn-primary pull-right">Crear sector</a>
 </div>
 <br>

 <div class="block full">
  <div class="block-title">
   <h2><strong>Sectores</strong> registrados</h2>
  </div>

  <div class="table-responsive">
   <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
    <thead>
     <tr>
      <th class="text-center">Nº</th>
      <th class="text-center">Sector</th>
      <th class="text-center">Creación</th>
      <th class="text-center">Actualización</th>
      <th class="text-center">Acciones</th>
     </tr>
    </thead>

    <tbody>
     @foreach($sectores as $sector)
      <tr>
       <td class="text-center">{{ $sector->id }}</td>
       <td class="text-center">{{ $sector->sectores }}</td>
       <td class="text-center">{{ $sector->created_at }}</td>
       <td class="text-center">{{ $sector->updated_at }}</td>
       <td class="text-center">
        {{-- Editar --}}
        <a href="{{ route('ge.sectors.edit', $sector->id) }}" class="btn btn-primary" title="Editar sector">
          <i class="fa fa-pencil-square-o sidebar-nav-icon"></i>
        </a>

        {{-- Eliminar --}}
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['ge.sectors.destroy', $sector->id],
            'style' => 'display:inline',
            'onsubmit' => "return confirm('¿Está seguro que desea eliminar este sector?')"
        ]) !!}
          <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
            <i class="fa fa-trash"></i>
          </button>
        {!! Form::close() !!}
       </td>
      </tr>
     @endforeach
    </tbody>
   </table>
  </div>
 </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

@stop
