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
     <h2><strong>Crear</strong> usuario</h2>
    </div>
    @foreach($usuario as $usuario)
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
         <option value="{{$usuario->tipo}}">{{$usuario->funel}}</option>
         @foreach($funels as $funels)
          <option value="{{$funels->id}}">{{$funels->funel}}</option>
         @endforeach
       </select>
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-email-input">Valor Propuesta</label>
       <div class="col-md-9">
        {{Form::text('valor', $usuario->valor, array('class' => 'form-control','placeholder'=>'Ingrese valor de la propuesta','value'=>'0'))}}
       </div>
    </div>

    <div class="form-group">
         <label class="col-md-3 control-label" for="example-email-input">Fecha Inicio</label>
          <div class="col-md-9 date" id="datetimepicker7">
           {{Form::text('fecha', $usuario->fecha, array('class' => 'form-control','readonly' => 'readonly','placeholder'=>'Ingrese fecha inicio'))}}
          </div>
        </div>


    <div class="form-group">
     <label class="col-md-3 control-label" for="example-text-input">Nombre</label>
      <div class="col-md-9">
       {{Form::text('nombre', $usuario->name, array('class' => 'form-control','placeholder'=>'Ingrese nombre'))}}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-email-input">Apellido</label>
      <div class="col-md-9">
       {{Form::text('apellido', $usuario->last_name, array('class' => 'form-control','placeholder'=>'Ingrese apellido'))}}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-email-input">Empresa</label>
      <div class="col-md-9">
       {{Form::text('empresa', $usuario->empresa, array('class' => 'form-control','placeholder'=>'Ingrese empresa'))}}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-password-input">Dirección</label>
      <div class="col-md-9">
       {{Form::text('direccion', $usuario->address, array('class' => 'form-control','placeholder'=>'Ingrese dirección'))}}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-email-input">Nit o Documento</label>
      <div class="col-md-9">
       {{Form::text('nit', $usuario->nit, array('class' => 'form-control','placeholder'=>'Ingrese Nit o Documento'))}}
      </div>
    </div>
    
    <div class="form-group">
     <label class="col-md-3 control-label" for="example-email-input">Email</label>
      <div class="col-md-9">
       {{Form::text('email', $usuario->email, array('class' => 'form-control','placeholder'=>'Ingrese email'))}}
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-password-input">Número de contacto</label>
      <div class="col-md-9">
       {{Form::text('numero', $usuario->phone, array('class' => 'form-control','placeholder'=>'Ingrese teléfono fijo o célular'))}}
      </div>
    </div>

      
    <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-select">Pais</label>
                                            <div class="col-md-9">
                                               <select id="pais" name="pais" class="form-control" size="1">
                                              <option value="{{$usuario->pais_id}}" selected>{{$usuario->pais}}</option>
                                             @foreach($paises as $paises)
                                              <option value="{{$paises->id}}">{{$paises->pais}}</option>
                                             @endforeach
                                                </select>
                                            </div>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-text-input">Ciudad</label>
                                            <div class="col-md-9">
                                               <select name="ciudad" id="ciudad" class="form-control" size="1">
                                                <option value="{{$usuario->city_id}}" selected>{{$usuario->departamento}}</option>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>






    <div class="form-group">
     <label class="col-md-3 control-label" for="example-text-input">Producto de intéres</label>
      <div class="col-md-9">
       <div id="output"></div>
        <select multiple="multiple" data-placeholder="Seleccione roles..." name="interes[]" multiple class="chosen-select form-control" id="interes">
         @foreach($productosa as $producto)
          <option value="{{$producto->id}}" selected>{{$producto->producto}}</option>
         @endforeach

         @foreach($productos as $productose)
         
        
          <option value="{{$productose->id}}">{{$productose->producto}}</option>
       
          
         @endforeach
        </select>
       </div>
      </div>

   <div class="form-group">
     <label class="col-md-3 control-label" for="example-select">Sector</label>
      <div class="col-md-9">
       <select name="sector" id="inputConcepto" class="form-control">
         <option value="{{$usuario->sector_id}}">{{$usuario->sectores}}</option>
         @foreach($sectores as $sectores)
          <option value="{{$sectores->id}}">{{$sectores->sectores}}</option>
         @endforeach
       </select>
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-select">Referido</label>
      <div class="col-md-9">
       <select name="referido" id="inputConcepto" class="form-control">
         <option value="{{$usuario->referido_id}}">{{$usuario->referidos}}</option>
         @foreach($referidos as $referidos)
          <option value="{{$referidos->id}}">{{$referidos->referidos}}</option>
         @endforeach
       </select>
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-select"># Empleados</label>
      <div class="col-md-9">
       <select name="cantidad" id="inputConcepto" class="form-control">
         <option value="{{$usuario->cantidad_id}}">{{$usuario->cantidad}}</option>
         @foreach($cantidades as $cantidades)
          <option value="{{$cantidades->id}}">{{$cantidades->cantidad}}</option>
         @endforeach
       </select>
      </div>
    </div>

    <div class="form-group">
     <label class="col-md-3 control-label" for="example-password-input">Comentarios</label>
      <div class="col-md-9">
       {{Form::textarea('comentarios', $usuario->comentarios, array('class' => 'form-control','placeholder'=>'Ingrese comentarios'))}}
      </div>
    </div>


    <div class="form-group form-actions">
     <div class="col-md-9 col-md-offset-3">
      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Crear</button>
      <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Cancelar</button>
     </div>
    </div>
    
    {{ Form::close() }}
      @endforeach                          
   </div>
  </div>
 </div>
</div>


<footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>   
  <script type="text/javascript">
   $(document).ready(function(){
   $('#datetimepicker7').datetimepicker({
      pickTime: true,
      format: 'MM/DD/YYYY HH:mm'
   });});
  </script>
  
  <script type="text/javascript">
   $(document).ready(function(){
   $('#datetimepicker9').datetimepicker({
      pickTime: true,
      format: 'MM/DD/YYYY HH:mm'
   });});
  </script>

  <script type="text/javascript">
   function openKCFinder(field) {
   window.KCFinder = {
   callBack: function(url) {
            field.value = url;
            window.KCFinder = null;}
    };
    window.open('/vendors/kcfinder/browse.php?type=images&dir=files/public', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600');}
  </script>
     
     
  {{ Html::script('modulo-calendario/js/moment.min.js') }}
  {{ Html::script('modulo-calendario/js/bootstrap-datetimepicker.min.js') }}
  {{ Html::script('modulo-calendario/js/validator.js')}}
  {{ Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js') }} 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

 
 {{ Html::script('modulo-gestion/validaciones/editar-usuario.js') }}
 {{ Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js') }} 


     <script src="//harvesthq.github.io/chosen/chosen.jquery.js"></script>

  <script type="text/javascript"></script>
    <script type="text/javascript">
document.getElementById('output').innerHTML = location.search;
$(".chosen-select").chosen();
</script>


  
<script type="text/javascript">
$(document).ready(function () {
    $('#pais').on('change', function () {
        let pais_id = $(this).val();

        if (!pais_id) {
            $('#ciudad').empty().append('<option value="" disabled selected>Seleccione Ciudad</option>');
            return;
        }

        $.get('/ubicacionciudad/ajax-subcatweb?cat_id=' + pais_id, function (data) {
            $('#ciudad').empty().append('<option value="" disabled selected>Seleccione Ciudad</option>');

            $.each(data, function (index, subcatObj) {
                $('#ciudad').append('<option value="' + subcatObj.id + '">' + subcatObj.departamento + '</option>');
            });
        })
        .fail(function () {
            $('#ciudad').empty().append('<option value="" disabled selected>Error cargando ciudades</option>');
        });
    });
});
</script>
 
</footer>
@stop