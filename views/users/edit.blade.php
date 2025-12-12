@extends ('adminsite.layout')

@section('cabecera')
    @parent
    {{ Html::style('modulo-calendario/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css') }}
@stop

@section('ContenidoSite-01')
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
                    <div class="block-options pull-right">
                    </div>
                    <h2><strong>Editar</strong> usuario</h2>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(!isset($usuario) || !$usuario)
                    <div class="alert alert-danger">
                        <p>Usuario no encontrado. Por favor, verifique el ID.</p>
                        <a href="{{ url()->previous() }}" class="btn btn-default">Volver</a>
                    </div>
                @else
                {{ Form::model($usuario, [
                    'route' => ['ge.commercial.update', $usuario->id],
                    'method' => 'PUT',
                    'class' => 'form-horizontal',
                    'id' => 'defaultForm'
                ]) }}

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-select">Tipo usuario</label>
                    <div class="col-md-9">
                        <select name="tipo" id="inputConcepto" class="form-control">
                            <option value="">Seleccionar tipo</option>
                            @foreach($funels as $funel)
                                <option value="{{ $funel->id }}" {{ old('funel_id', $usuario->funel_id) == $funel->id ? 'selected' : '' }}>
                                    {{ $funel->funel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Valor Propuesta</label>
                    <div class="col-md-9">
                        {{ Form::text('valor', old('valor', $usuario->valor ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese valor de la propuesta']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Fecha Inicio</label>
                    <div class="col-md-9 date" id="datetimepicker7">
                        {{ Form::text('fecha', old('fecha', $usuario->fecha ?? ''), ['class' => 'form-control', 'readonly' => 'readonly', 'placeholder' => 'Ingrese fecha inicio']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-text-input">Nombre</label>
                    <div class="col-md-9">
                        {{ Form::text('nombre', old('nombre', $usuario->name ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese nombre']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Apellido</label>
                    <div class="col-md-9">
                        {{ Form::text('apellido', old('apellido', $usuario->last_name ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese apellido']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Empresa</label>
                    <div class="col-md-9">
                        {{ Form::text('empresa', old('empresa', $usuario->empresa ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese empresa']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-password-input">Dirección</label>
                    <div class="col-md-9">
                        {{ Form::text('direccion', old('direccion', $usuario->address ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese dirección']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Nit o Documento</label>
                    <div class="col-md-9">
                        {{ Form::text('nit', old('nit', $usuario->nit ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese Nit o Documento']) }}
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-email-input">Email</label>
                    <div class="col-md-9">
                        {{ Form::email('email', old('email', $usuario->email ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese email']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-password-input">Número de contacto</label>
                    <div class="col-md-9">
                        {{ Form::text('numero', old('numero', $usuario->phone ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese teléfono fijo o célular']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-select">Pais</label>
                    <div class="col-md-9">
                        <select id="pais" name="pais" class="form-control" size="1">
                            <option value="">Seleccionar país</option>
                            @foreach($paises as $pais)
                                <option value="{{ $pais->id }}" {{ old('pais', $usuario->country_id) == $pais->id ? 'selected' : '' }}>
                                    {{ $pais->pais }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-text-input">Ciudad</label>
                    <div class="col-md-9">
                        <select name="ciudad" id="ciudad" class="form-control" size="1">
                            <option value="{{ $usuario->city_id ?? '' }}" selected>
                                {{ $usuario->city_id ? 'Cargando...' : 'Seleccione ciudad' }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-text-input">Producto de interés</label>
                    <div class="col-md-9">
                        <div id="output"></div>
                        <select multiple="multiple" data-placeholder="Seleccione productos..." name="interes[]" class="chosen-select form-control" id="interes">
                            @if(isset($productosa) && $productosa->count() > 0)
                                @foreach($productosa as $producto)
                                    <option value="{{ $producto->id }}" selected>{{ $producto->producto }}</option>
                                @endforeach
                            @endif
                            
                            @if(isset($productos) && $productos->count() > 0)
                                @foreach($productos as $productose)
                                    <option value="{{ $productose->id }}">{{ $productose->producto }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-select">Sector</label>
                    <div class="col-md-9">
                        <select name="sector" id="inputConcepto" class="form-control">
                            <option value="">Seleccionar sector</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}" {{ old('sector', $usuario->sector_id) == $sector->id ? 'selected' : '' }}>
                                    {{ $sector->sectores }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-select">Referido</label>
                    <div class="col-md-9">
                        <select name="referido" id="inputConcepto" class="form-control">
                            <option value="">Seleccionar referido</option>
                            @foreach($referidos as $referido)
                                <option value="{{ $referido->id }}" {{ old('referido', $usuario->referido_id) == $referido->id ? 'selected' : '' }}>
                                    {{ $referido->referidos }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-select"># Empleados</label>
                    <div class="col-md-9">
                        <select name="cantidad" id="inputConcepto" class="form-control">
                            <option value="">Seleccionar cantidad</option>
                            @foreach($cantidades as $cantidad)
                                <option value="{{ $cantidad->id }}" {{ old('cantidad', $usuario->cantidad_id) == $cantidad->id ? 'selected' : '' }}>
                                    {{ $cantidad->cantidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="example-password-input">Comentarios</label>
                    <div class="col-md-9">
                        {{ Form::textarea('comentarios', old('comentarios', $usuario->message ?? ''), ['class' => 'form-control', 'placeholder' => 'Ingrese comentarios', 'rows' => '4']) }}
                    </div>
                </div>
                @if(Auth::user()->id == 1)  
                <div class="form-group form-actions">
                    <div class="col-md-9 col-md-offset-3">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Actualizar</button>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Cancelar</a>
                    </div>
                </div>
                @else
                @endif
                
                {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#datetimepicker7').datetimepicker({
            pickTime: true,
            format: 'MM/DD/YYYY HH:mm'
        });
        
        // Cargar ciudades al iniciar si hay un país seleccionado
        let pais_id = $('#pais').val();
        if (pais_id) {
            cargarCiudades(pais_id, '{{ $usuario->city_id ?? "" }}');
        }
    });
    
    function cargarCiudades(pais_id, ciudad_seleccionada = '') {
        if (!pais_id) {
            $('#ciudad').empty().append('<option value="" disabled selected>Seleccione Ciudad</option>');
            return;
        }

        $.get('/ubicacionciudad/ajax-subcatweb?cat_id=' + pais_id, function (data) {
            $('#ciudad').empty();
            $('#ciudad').append('<option value="">Seleccionar ciudad</option>');

            $.each(data, function (index, subcatObj) {
                let selected = (subcatObj.id == ciudad_seleccionada) ? 'selected' : '';
                $('#ciudad').append('<option value="' + subcatObj.id + '" ' + selected + '>' + subcatObj.departamento + '</option>');
            });
        })
        .fail(function () {
            $('#ciudad').empty().append('<option value="" disabled selected>Error cargando ciudades</option>');
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#datetimepicker9').datetimepicker({
            pickTime: true,
            format: 'MM/DD/YYYY HH:mm'
        });
    });
</script>

<script type="text/javascript">
    function openKCFinder(field) {
        window.KCFinder = {
            callBack: function(url) {
                field.value = url;
                window.KCFinder = null;
            }
        };
        window.open('/vendors/kcfinder/browse.php?type=images&dir=files/public', 'kcfinder_textbox',
            'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
            'resizable=1, scrollbars=0, width=800, height=600');
    }
</script>
     
{{ Html::script('modulo-calendario/js/moment.min.js') }}
{{ Html::script('modulo-calendario/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('modulo-calendario/js/validator.js')}}
{{ Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js') }}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
{{ Html::script('modulo-gestion/validaciones/editar-usuario.js') }}
<script src="//harvesthq.github.io/chosen/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        document.getElementById('output').innerHTML = location.search;
        $(".chosen-select").chosen();
        
        // Evento para cambiar país
        $('#pais').on('change', function () {
            let pais_id = $(this).val();
            cargarCiudades(pais_id);
        });
    });
</script>
@stop