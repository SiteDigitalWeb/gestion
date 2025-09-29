@extends ('adminsite.layout')

@section('cabecera')
  @parent
  {{ Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css') }}
@stop

@section('ContenidoSite-01')
<div class="content-header">
 <ul class="nav-horizontal text-center">
  <li><a href="/gestion/comercial">Usuarios</a></li>
  <li><a href="/gestion/comercial/registro">Registrar Datos Usuario</a></li>
  <li><a href="/gestion/comercial/productos">Productos & Servicios</a></li>
  <li><a href="/gestion/comercial/sectores">Sectores</a></li>
  <li><a href="/gestion/comercial/referidos">Referidos</a></li>
  <li class="active"><a href="/gestion/comercial/cantidades">Cantidades</a></li>
 </ul>
</div>

<div class="container">
 <div class="row">
  <div class="col-md-12">
   <div class="block">

    <div class="block-title">
     <h2><strong>Editar</strong> cantidad</h2>
    </div>

    {{-- Formulario de edición --}}
    {{ Form::model($cantidad, [
        'route' => ['ge.quantities.update', $cantidad->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'id' => 'defaultForm'
    ]) }}

    <div class="form-group">
     <label class="col-md-3 control-label">Cantidad empleados</label>
      <div class="col-md-9">
       {{ Form::text('cantidad', null, ['class' => 'form-control','placeholder'=>'Ingrese cantidad']) }}
      </div>
    </div>

    <div class="form-group form-actions">
     <div class="col-md-9 col-md-offset-3">
      <button type="submit" class="btn btn-sm btn-primary">
       <i class="fa fa-angle-right"></i> Editar
      </button>
      <button type="reset" class="btn btn-sm btn-warning">
       <i class="fa fa-repeat"></i> Cancelar
      </button>
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
