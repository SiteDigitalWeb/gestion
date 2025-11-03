@extends ('adminsite.layout')

@section('cabocera')
    @parent
@stop

@section('ContenidoSite-01')

<div class="block full">
    <div class="block-title">
        <h2><strong>Configuración</strong> Empresa</h2>
    </div>

    {{-- Mostrar mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Errores encontrados:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CORRECCIÓN: Usar la ruta resource correcta --}}
    @foreach($configuracion as $config)
        {{ Form::model($config, [
            'route' => ['ge.configuration.update', $config->id], 
            'method' => 'PUT', 
            'files' => true, 
            'class' => 'form-horizontal'
        ]) }}
    @endforeach

        {{-- Empresa --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Empresa</label>
            <div class="col-md-9">
                {{ Form::text('empresa', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese Empresa'
                ]) }}
                @error('empresa')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Dirección --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Dirección</label>
            <div class="col-md-9">
                {{ Form::text('direccion', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese Dirección'
                ]) }}
                @error('direccion')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Teléfono --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Teléfono</label>
            <div class="col-md-9">
                {{ Form::text('telefono', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese Teléfono'
                ]) }}
                @error('telefono')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Sitio Web --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Sitio Web</label>
            <div class="col-md-9">
                {{ Form::text('website', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese Sitio Web'
                ]) }}
                @error('website')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Correo --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Correo</label>
            <div class="col-md-9">
                {{ Form::email('correo', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese Correo'
                ]) }}
                @error('correo')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Color Principal --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Color Principal</label>
            <div class="col-md-9">
                {{ Form::color('color_principal', null, ['class' => 'form-control']) }}
                @error('color_principal')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Color Secundario --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Color Secundario</label>
            <div class="col-md-9">
                {{ Form::color('color_secundario', null, ['class' => 'form-control']) }}
                @error('color_secundario')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Logo con preview --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Logo</label>
            <div class="col-md-9">
                <input type="file" name="logo" id="logo" class="form-control" accept="image/*" onchange="previewImage(event, 'logoPreview')">
                @if($config->logo)
                    <div class="mt-2">
                        <p><strong>Logo actual:</strong></p>
                        <img src="{{ asset($config->logo) }}" alt="Logo actual" style="max-height: 100px;" class="img-thumbnail">
                    </div>
                @endif
                <img id="logoPreview" src="#" alt="Vista previa" style="margin-top:10px; max-height:150px; display:none;">
                @error('logo')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Imagen 01 con preview --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Imagen 01</label>
            <div class="col-md-9">
                <input type="file" name="imagen1" id="imagen1" class="form-control" accept="image/*" onchange="previewImage(event, 'img01Preview')">
                @if($config->img_01)
                    <div class="mt-2">
                        <p><strong>Imagen 01 actual:</strong></p>
                        <img src="{{ asset($config->img_01) }}" alt="Imagen 01 actual" style="max-height: 100px;" class="img-thumbnail">
                    </div>
                @endif
                <img id="img01Preview" src="#" alt="Vista previa" style="margin-top:10px; max-height:150px; display:none;">
                @error('imagen1')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Imagen 02 con preview --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Imagen 02</label>
            <div class="col-md-9">
                <input type="file" name="imagen2" id="imagen2" class="form-control" accept="image/*" onchange="previewImage(event, 'img02Preview')">
                @if($config->img_02)
                    <div class="mt-2">
                        <p><strong>Imagen 02 actual:</strong></p>
                        <img src="{{ asset($config->img_02) }}" alt="Imagen 02 actual" style="max-height: 100px;" class="img-thumbnail">
                    </div>
                @endif
                <img id="img02Preview" src="#" alt="Vista previa" style="margin-top:10px; max-height:150px; display:none;">
                @error('imagen2')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Presentación --}}
        <div class="form-group">
            <label class="col-md-3 control-label">Presentación</label>
            <div class="col-md-9">
                {{ Form::textarea('presentacion', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese Presentación',
                    'rows' => 5
                ]) }}
                @error('presentacion')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Botones --}}
        <div class="form-group form-actions">
            <div class="col-md-9 col-md-offset-3">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-save"></i> Guardar
                </button>
                <button type="reset" class="btn btn-sm btn-warning">
                    <i class="fa fa-repeat"></i> Cancelar
                </button>
            </div>
        </div>

    {{ Form::close() }}
</div>

{{-- Script para previsualizar imágenes --}}
<script>
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "#";
        preview.style.display = 'none';
    }
}
</script>

@stop