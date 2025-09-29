
@extends ('adminsite.layout')

@section('cabecera')
  @parent
  {{ Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css') }}
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
   <a href="{{ route('ge.products.index') }}"><i class="fa fa-file-o"></i> Productos & Servicios</a>
  </li>
  <li>
   <a href="/gestion/comercial/sectores"><i class="fa fa-file-o"></i> Sectores</a>
  </li>
  <li>
   <a href="/gestion/comercial/referidos"><i class="fa fa-file-o"></i> Referidos</a>
  </li>
  <li>
   <a href="/gestion/comercial/cantidades"><i class="fa fa-file-o"></i> Cantidades</a>
  </li>
 </ul>
</div>

<div class="container">
 <div class="row">
  <div class="col-md-12">
   <div class="block">

    <div class="block-title">
     <h2><strong>Editar</strong> producto</h2>
    </div>

    {{-- Formulario de edición --}}
    {{ Form::model($producto, [
        'route' => ['ge.products.update', $producto->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'id' => 'defaultForm'
    ]) }}

    <div class="form-group">
     <label class="col-md-3 control-label" for="producto">Producto</label>
      <div class="col-md-9">
       {{ Form::text('producto', null, [
           'class' => 'form-control',
           'placeholder' => 'Ingrese producto'
       ]) }}
      </div>
    </div>

    <div class="form-group form-actions">
     <div class="col-md-9 col-md-offset-3">
      <button type="submit" class="btn btn-sm btn-primary">
        <i class="fa fa-save"></i> Guardar cambios
      </button>
      <a href="{{ route('ge.products.index') }}" class="btn btn-sm btn-warning">
        <i class="fa fa-times"></i> Cancelar
      </a>
     </div>
    </div>

    {{ Form::close() }}
                                
   </div>
  </div>
 </div>
</div>

<footer>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 {{ Html::script('modulo-gestion/validaciones/crear-recursos.js') }}
 {{ Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js') }} 
</footer>
@stop


