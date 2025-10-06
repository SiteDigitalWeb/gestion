
@extends ('adminsite.layout')

@section('cabecera')
    @parent
@stop

@section('ContenidoSite-01')

<div class="block full">
    <div class="block-title">
        <h2><strong>Configuración</strong> Empresa</h2>
    </div>

    {{-- Ajusta la ruta al update que tengas configurado --}}
    {{ Form::open(['url' => url('ge/configuration/1'), 'method' => 'PUT', 'files' => true, 'class' => 'form-horizontal']) }}

        @foreach($configuracion as $config)

            {{-- Empresa --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Empresa</label>
                <div class="col-md-9">
                    {{ Form::text('empresa', $config->empresa, [
                        'class' => 'form-control',
                        'placeholder' => 'Ingrese Empresa',
                        'required'
                    ]) }}
                </div>
            </div>

            {{-- Dirección --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Dirección</label>
                <div class="col-md-9">
                    {{ Form::text('direccion', $config->direccion, [
                        'class' => 'form-control',
                        'placeholder' => 'Ingrese Dirección',
                        'required'
                    ]) }}
                </div>
            </div>

            {{-- Teléfono --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Teléfono</label>
                <div class="col-md-9">
                    {{ Form::text('telefono', $config->telefono, [
                        'class' => 'form-control',
                        'placeholder' => 'Ingrese Teléfono',
                        'required'
                    ]) }}
                </div>
            </div>

            {{-- Sitio Web --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Sitio Web</label>
                <div class="col-md-9">
                    {{ Form::text('website', $config->website, [
                        'class' => 'form-control',
                        'placeholder' => 'Ingrese Sitio Web'
                    ]) }}
                </div>
            </div>

            {{-- Correo --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Correo</label>
                <div class="col-md-9">
                    {{ Form::email('correo', $config->correo, [
                        'class' => 'form-control',
                        'placeholder' => 'Ingrese Correo'
                    ]) }}
                </div>
            </div>

            {{-- Color Principal --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Color Principal</label>
                <div class="col-md-9">
                    {{ Form::color('color_principal', $config->color_principal, ['class' => 'form-control']) }}
                </div>
            </div>

            {{-- Color Secundario --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Color Secundario</label>
                <div class="col-md-9">
                    {{ Form::color('color_secundario', $config->color_secundario, ['class' => 'form-control']) }}
                </div>
            </div>

            {{-- Logo con preview --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Logo</label>
                <div class="col-md-9">
                    <input type="file" name="logo" id="logo" class="form-control" accept="image/*" onchange="previewImage(event, 'logoPreview')">
                    <img id="logoPreview" src="/{{ $config->logo }}" alt="Vista previa" style="margin-top:10px; max-height:150px; {{ $config->logo ? '' : 'display:none;' }}">
                </div>
            </div>

            {{-- Imagen 01 con preview --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Imagen 01</label>
                <div class="col-md-9">
                    <input type="file" name="imagen1" id="imagen1" class="form-control" accept="image/*" onchange="previewImage(event, 'img01Preview')">
                    <img id="img01Preview" src="/{{ $config->img_01 }}" alt="Vista previa" style="margin-top:10px; max-height:150px; {{ $config->img_01 ? '' : 'display:none;' }}">
                </div>
            </div>

            {{-- Imagen 02 con preview --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Imagen 02</label>
                <div class="col-md-9">
                    <input type="file" name="imagen2" id="imagen2" class="form-control" accept="image/*" onchange="previewImage(event, 'img02Preview')">
                    <img id="img02Preview" src="/{{ $config->img_02 }}" alt="Vista previa" style="margin-top:10px; max-height:150px; {{ $config->img_02 ? '' : 'display:none;' }}">
                </div>
            </div>

            {{-- Presentación --}}
            <div class="form-group">
                <label class="col-md-3 control-label">Presentación</label>
                <div class="col-md-9">
                    {{ Form::textarea('presentacion', $config->presentacion, [
                        'class' => 'form-control ckeditor',
                        'placeholder' => 'Ingrese Presentación'
                    ]) }}
                </div>
            </div>

        @endforeach

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