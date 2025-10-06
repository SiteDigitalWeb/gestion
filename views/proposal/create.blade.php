@extends ('adminsite.layout')

@section('cabecera')
    @parent
    {{ Html::style('modulo-calendario/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css') }}
@stop

@section('ContenidoSite-01')
<style>
  #editor1, #editor2 {
    min-height: 300px; /* puedes subir a 400px, 500px, etc. */
  }
</style>
<div class="content-header">
 <ul class="nav-horizontal text-center">
  <li>
   <a href="/gestion/comercial"><i class="fa fa-list-ul"></i> Usuarios</a>
  </li>
  <li class="active">
   <a href="/gestion/comercial/registro"><i class="fa fa-user-plus"></i> Registrar Datos Usuario</a>
  </li>
  <li>
   <a href="/gestion/comercial/productos"><i class="fa fa-file-o"></i>Productos & Servicios</a>
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
 <div class="row">
  <div class="col-md-12">
   <div class="block">

    <div class="block-title">
     <h2><strong>Crear</strong> propuesta</h2>
    </div>
    
    {{ Form::open(['method' => 'POST','class' => 'form-horizontal','id' => 'defaultForm', 'url' => 'ge/proposal']) }}

    <div class="form-group">
     <label class="col-md-3 control-label">Estado Propuesta</label>
      <div class="col-md-9">
        {{ Form::select('tipo', [
          '1' => 'En Proceso',
          '2' => 'No Ganada',
          '3' => 'Ganada'
        ], null, ['class' => 'form-control']) }}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label">Cliente</label>
     <div class="col-md-9">
      <select name="cliente" class="form-control" required>
      <option value="" disabled selected>Seleccione un cliente</option>
      @foreach($clientes as $cliente)
       <option value="{{ $cliente->id }}">
          {{ $cliente->name }} {{ $cliente->last_name }} - <b>{{ $cliente->empresa }}</b>
        </option>
       @endforeach
      </select>
     </div>
    </div> 

    <div class="form-group">
     <label class="col-md-3 control-label">Visualización Tarifas</label>
       <div class="col-md-9">
         {{ Form::select('tarifas', [
           '1' => 'Visible',
           '2' => 'No Visible'
         ], null, ['class' => 'form-control']) }}
       </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label">Fecha Presentación</label>
       <div class="col-md-9 date" id="datetimepicker7">
        {{ Form::date('fecha', date('Y-m-d'), ['class' => 'form-control', 'placeholder'=>'Ingrese fecha presentación']) }}
       </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label">Asunto</label>
       <div class="col-md-9">
        {{ Form::text('asunto', '', ['class' => 'form-control','placeholder'=>'Ingrese asunto']) }}
       </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label">Presentación</label>
      <div class="col-md-9">
        {{ Form::textarea('presentacion', '', ['id' => 'editor1','placeholder'=>'Ingrese contenido']) }}
      </div>
    </div>

    {{ Form::hidden('utm_referido',Request::get('utm_referido'), ['class' => 'form-control','readonly' => 'readonly']) }}

    <div class="form-group">
     <label class="col-md-3 control-label">Términos y Condiciones</label>
      <div class="col-md-9">
        {{ Form::textarea('comentarios', '', ['id' => 'editor2','placeholder'=>'Ingrese contenido']) }}
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

{{-- CKEditor 5 --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor1'), {
      ckfinder: {
        uploadUrl: '/file-manager/ckeditor' // Ajusta según tu backend
      }
    })
    .catch(error => { console.error(error); });

  ClassicEditor
    .create(document.querySelector('#editor2'), {
      ckfinder: {
        uploadUrl: '/file-manager/ckeditor'
      }
    })
    .catch(error => { console.error(error); });
</script>
@stop