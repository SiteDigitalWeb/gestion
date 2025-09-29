@extends('adminsite.layout')

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
  <li><a href="/gestion/comercial/cantidades">Cantidades</a></li>
  <li class="active"><a href="/gestion/comercial/embudos">Embudos</a></li>
 </ul>
</div>

<div class="container">
 <div class="row">
  <div class="col-md-12">
   <div class="block">
    <div class="block-title">
     <h2><strong>Editar</strong> embudo</h2>
    </div>

    {{-- Formulario de edición --}}
    {{ Form::model($funels, [
        'route' => ['ge.embudo.update', $funels->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'id' => 'defaultForm'
    ]) }}

    <div class="form-group">
     <label class="col-md-3 control-label">Nombre del embudo</label>
      <div class="col-md-9">
       {{ Form::text('funel', null, ['class'=>'form-control','placeholder'=>'Ingrese nombre del embudo']) }}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label">Color</label>
      <div class="col-md-9">
       {{ Form::color('color', null, ['class'=>'form-control']) }}
      </div>
    </div>

    <div class="form-group form-actions">
     <div class="col-md-9 col-md-offset-3">
      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Actualizar</button>
      <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Cancelar</button>
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
