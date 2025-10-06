@extends('adminsite.layout')

@section('cabecera')
  @parent
  {{ Html::style('modulo-calendario/css/bootstrap-datetimepicker.min.css') }}
  {{ Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css') }}
  <link rel="stylesheet" href="//harvesthq.github.io/chosen/chosen.css">
@stop

@section('ContenidoSite-01')
<div class="content-header">
  <ul class="nav-horizontal text-center">
    <li><a href="/gestion/comercial"><i class="fa fa-list-ul"></i> Usuarios</a></li>
    <li class="active"><a href="/gestion/comercial/registro"><i class="fa fa-user-plus"></i> Registrar Datos Usuario</a></li>
    <li><a href="/gestion/comercial/productos"><i class="fa fa-file-o"></i> Productos & Servicios</a></li>
    <li><a href="/gestion/comercial/sectores"><i class="fa fa-file-o"></i> Sectores</a></li>
    <li><a href="/gestion/comercial/referidos"><i class="fa fa-file-o"></i> Referidos</a></li>
    <li><a href="/gestion/comercial/cantidades"><i class="fa fa-file-o"></i> Cantidades</a></li>
  </ul>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="block">

        <div class="block-title">
          <h2><strong>Editar</strong> propuesta</h2>
        </div>

        {{-- Formulario de edición --}}
        {{ Form::model($propuesta, [
          'method' => 'PUT',
          'url'    => route('ge.proposal.update', $propuesta->id),
          'class'  => 'form-horizontal',
          'id'     => 'defaultForm'
        ]) }}

        {{-- Estado propuesta --}}
        <div class="form-group">
          <label class="col-md-3 control-label">Estado Propuesta</label>
          <div class="col-md-9">
            {{ Form::select('tipo', [
              '1' => 'En Proceso',
              '2' => 'No Ganada',
              '3' => 'Ganada'
            ], $propuesta->estado_propuesta, ['class' => 'form-control']) }}
          </div>
        </div>

        {{-- Motivo pérdida --}}
        <div class="form-group">
          <label class="col-md-3 control-label">Motivo Pérdida</label>
          <div class="col-md-9">
            <select name="motivos" class="form-control" id="motivos">
              <option value="{{ $propuesta->motivo_id }}">{{ $propuesta->motivo_nombre }}</option>
              @foreach($motivos as $motivo)
                <option value="{{ $motivo->id }}">{{ $motivo->motivo }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Tarifas --}}
        <div class="form-group">
          <label class="col-md-3 control-label">Visualización Tarifas</label>
          <div class="col-md-9">
            {{ Form::select('tarifas', [
              '1' => 'Visible',
              '2' => 'No Visible'
            ], $propuesta->tarifas, ['class' => 'form-control']) }}
          </div>
        </div>

        {{-- Fecha presentación --}}
        <div class="form-group">
          <label class="col-md-3 control-label">Fecha Presentación</label>
          <div class="col-md-9">
            {{ Form::date('fecha', $propuesta->fecha_presentacion, ['class' => 'form-control']) }}
          </div>
        </div>

        {{-- Asunto --}}
        <div class="form-group">
          <label class="col-md-3 control-label">Asunto</label>
          <div class="col-md-9">
            {{ Form::text('asunto', $propuesta->asunto, ['class' => 'form-control','placeholder'=>'Ingrese asunto']) }}
          </div>
        </div>

        <div class="form-group">
     <label class="col-md-3 control-label">Presentación</label>
      <div class="col-md-9">
        {{ Form::textarea('presentacion', $propuesta->presentacion, ['id' => 'editor1','placeholder'=>'Ingrese contenido']) }}
      </div>
    </div>



    <div class="form-group">
     <label class="col-md-3 control-label">Términos y Condiciones</label>
      <div class="col-md-9">
        {{ Form::textarea('comentarios', $propuesta->observaciones, ['id' => 'editor2','placeholder'=>'Ingrese contenido']) }}
      </div>
    </div>

       

        {{-- Cliente oculto --}}
        {{ Form::hidden('cliente', $propuesta->cms_user_id) }}
        {{ Form::hidden('identificador', $propuesta->identificador) }}

        {{-- Acciones --}}
        <div class="form-group form-actions">
          <div class="col-md-9 col-md-offset-3">
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="fa fa-check"></i> Guardar
            </button>
            <a href="{{ route('ge.proposal.index') }}" class="btn btn-sm btn-warning">
              <i class="fa fa-times"></i> Cancelar
            </a>
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
@endsection
