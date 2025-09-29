@extends ('adminsite.layout')
 
 @section('cabecera')
  @parent
 @stop

@section('ContenidoSite-01')

<div class="content-header">
 <ul class="nav-horizontal text-center">
  <li>
   <a href="/gestion/comercial"><i class="fa fa-list-ul"></i> Usuarios</a>
  </li>
  <li>
   <a href="/gestion/comercial/registro"><i class="fa fa-user-plus"></i> Registrar Datos Usuario</a>
  </li>
  <li class="active">
   <a href="/gestion/comercial/productos"><i class="fa fa-file-o"></i>Productos & servicios</a>
  </li>
  <li>
   <a href="/gestion/comercial/sectores"><i class="fa fa-file-o"></i>Sectores</a>
  </li>
  <li>
   <a href="/gestion/comercial/referidos"><i class="fa fa-file-o"></i>Referidos</a>
  </li>
   <li>
   <a href="/gestion/comercial/cantidades"><i class="fa fa-file-o"></i>Cantidades</a>
  </li>
 </ul>
</div>

<div class="container">
 <?php $status=Session::get('status'); ?>
 @if($status=='ok_create')
  <div class="alert alert-success">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Producto y/o servicio registrado con éxito</strong> CMS...
  </div>
 @endif

 @if($status=='ok_delete')
  <div class="alert alert-danger">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Producto y/o servicio eliminado con éxito</strong> CMS...
  </div>
 @endif

 @if($status=='ok_update')
  <div class="alert alert-warning">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Producto y/o servicio actualizada con éxito</strong> CMS...
  </div>
 @endif
</div>

<div class="container">
 <div class="container-fluid">
  <a href="/ge/products/create" class="btn btn-primary pull-right">Crear producto o servicio</a>
 </div>
 <br>
 
 <div class="block full">
  <div class="block-title">
   <h2><strong>Productos</strong> registrados</h2>
  </div>
            
  <div class="table-responsive">
   <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
    <thead>
     <tr>
      <th class="text-center">Nº</th>
      <th class="text-center">Producto</th>
      <th class="text-center">Creación</th>
      <th class="text-center">Actualización</th>
      <th class="text-center">Acciones</th>
     </tr>
    </thead>
    
    <tbody>
     @foreach($productos as $productos)
      <tr>
       <td class="text-center">{{$productos->id}}</td>
       <td class="text-center">{{$productos->producto}}</td>
       <td class="text-center">{{$productos->created_at}}</td>
       <td class="text-center">{{$productos->updated_at}}</td>
       <td class="text-center"><a href="{{ route('ge.products.edit', $productos->id) }}">
  <span id="tip" data-toggle="tooltip" data-placement="left"
        title="Editar producto y/o servicio" class="btn btn-primary">
    <i class="fa fa-pencil-square-o sidebar-nav-icon"></i>
  </span>
</a>
<form action="{{ route('ge.products.destroy', $productos->id) }}" 
      method="POST" 
      style="display:inline-block;" 
      onsubmit="return confirm('¿Está seguro que desea eliminar el registro?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Eliminar producto y/o servicio">
        <i class="hi hi-trash sidebar-nav-icon"></i>
    </button>
</form>
       
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