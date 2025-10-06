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
   <a href="/ge/quantities/create"><i class="fa fa-list-ul"></i>Crear Cantidades</a>
  </li>
  
 </ul>
</div>

<div class="container">
 <?php $status=Session::get('status'); ?>
 @if($status=='ok_create')
  <div class="alert alert-success">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Cantidad registrada con éxito</strong> CMS...
  </div>
 @endif

 @if($status=='ok_delete')
  <div class="alert alert-danger">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Cantidad eliminada con éxito</strong> CMS...
  </div>
 @endif

 @if($status=='ok_update')
  <div class="alert alert-warning">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Cantidad actualizada con éxito</strong> CMS...
  </div>
 @endif
</div>

<div class="container">
 <div class="container-fluid">
   <a href="{{ route('ge.quantities.create') }}" class="btn btn-primary pull-right">Crear cantidad</a>
 </div>
 <br>
 
 <div class="block full">
  <div class="block-title">
   <h2><strong>Cantidades</strong> registradas</h2>
  </div>
            
  <div class="table-responsive">
   <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
    <thead>
     <tr>
      <th class="text-center">Nº</th>
      <th class="text-center">Referido</th>
      <th class="text-center">Creación</th>
      <th class="text-center">Actualización</th>
      <th class="text-center">Acciones</th>
     </tr>
    </thead>
    
    <tbody>
     @foreach($cantidades as $cantidades)
      <tr>
       <td class="text-center">{{$cantidades->id}}</td>
       <td class="text-center">{{$cantidades->cantidad}}</td>
       <td class="text-center">{{$cantidades->created_at}}</td>
       <td class="text-center">{{$cantidades->updated_at}}</td>
       <td class="text-center">
         <a href="{{ route('ge.quantities.edit', $cantidades->id) }}" class="btn btn-primary" title="Editar referido">
      <i class="fa fa-pencil-square-o sidebar-nav-icon"></i>
    </a>

    {{-- Eliminar --}}
    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['ge.quantities.destroy', $cantidades->id],
        'style' => 'display:inline',
        'onsubmit' => "return confirm('¿Está seguro que desea eliminar este referido?')"
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

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

@stop