@extends ('adminsite.layout')

@section('cabecera')
  @parent
  {{ Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css') }}
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
   <a href="/ge/products"><i class="fa fa-list-ul"></i>Productos / Servicio</a>
  </li>
  
 </ul>
</div>

<div class="container">
 <div class="row">
  <div class="col-md-12">
   <div class="block">

    <div class="block-title">
     <div class="block-options pull-right">
     </div>
     <h2><strong>Crear</strong> producto</h2>
    </div>
    
    {{ Form::open([
    'url' => 'ge/products',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'defaultForm'
]) }}

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-email-input">Producto</label>
      <div class="col-md-9">
       {{Form::text('producto', '', array('class' => 'form-control','placeholder'=>'Ingrese producto'))}}
      </div>
    </div>

    <div class="form-group form-actions">
     <div class="col-md-9 col-md-offset-3">
      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Crear</button>
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