@extends ('adminsite.layout')
 
@section('cabecera')
  @parent
  <link rel="stylesheet" href="/validaciones/dist/css/bootstrapValidator.css"/>
  <script type="text/javascript" src="/validaciones/vendor/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="/validaciones/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/validaciones/dist/js/bootstrapValidator.js"></script>
@stop

@section('ContenidoSite-01')
<div class="content-header">
  <ul class="nav-horizontal text-center">
     <li class="active">
   <a href="/ge/commercial"><i class="fa fa-list-ul"></i> Propuestas</a>
  </li>
  </ul>
</div>

<div class="container">
  @if(session('status')=='ok_create')
    <div class="alert alert-success"><strong>Producto registrado con éxito</strong></div>
  @endif
  @if(session('status')=='ok_delete')
    <div class="alert alert-danger"><strong>Producto eliminado con éxito</strong></div>
  @endif
  @if(session('status')=='ok_update')
    <div class="alert alert-warning"><strong>Producto actualizado con éxito</strong></div>
  @endif
</div>









<div class="container">
  
<div class="block">
                                    <!-- Normal Form Title -->
                                    <div class="block-title">
                                        <div class="block-options pull-right">
                                            <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default toggle-bordered enable-tooltip" data-toggle="button" title="Toggles .form-bordered class">No Borders</a>
                                        </div>
                                        <h2><strong>Editar</strong> producto</h2>
                                    </div>
                                    <!-- END Normal Form Title -->

                                    <!-- Normal Form Content -->
                                      {{ Form::open([
    'route' => ['ge.product.update', $productos->id],
    'method' => 'PUT',
    'class' => 'form-horizontal',
    'id' => 'defaultForm'
]) }}
                
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="control-label" for="example-email-input">Producto</label>
                                           {{ Form::text('producto', $productos->producto, ['class' => 'form-control','placeholder'=>'Ingrese nombre producto']) }}
                                          </div>
                                        </div>
                                       </div>

                                      
                                       <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                         <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="control-label" for="example-email-input">Cantidad</label>
                                           {{ Form::text('cantidad', $productos->posti, ['class' => 'form-control','placeholder'=>'Ingrese cantidad']) }}
                                          </div>
                                        </div>

                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                          <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="control-label" for="example-email-input">Precio</label>
                                           {{ Form::text('precio', $productos->precio, ['class' => 'form-control','placeholder'=>'Ingrese precio']) }}
                                          </div>
                                        </div>
                                        </div>

                                         <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                          <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="control-label" for="example-email-input">Iva</label>
                                                      {{ Form::text('iva', $productos->iva, ['class' => 'form-control','placeholder'=>'Ingrese IVA']) }}

                                          </div>
                                        </div>
                                      </div>


                                      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                          <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="control-label" for="example-email-input">Moneda</label>
                                             {{ Form::select('moneda', [
                $productos->moneda => $productos->moneda,
                '1' => 'COP',
                '2' => 'USD'
            ], null, ['class' => 'form-control']) }}
                                          </div>
                                        </div>
                                      </div>


                                       </br>




                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                             <div class="form-group">
     <label class="col-md-3 control-label">Términos y Condiciones</label>
      <div class="col-md-12">
        {{ Form::textarea('descripcion', $productos->descripcion, ['id'=>'editor','class'=>'form-control','placeholder'=>'Ingrese descripción']) }}
      </div>
    </div>

                                        </div>

                                        {{Form::hidden('identificador',  $productos->identificador, array('class' => 'form-control','placeholder'=>'Ingrese descripción'))}}

                                        {{Form::hidden('propuesta',  $productos->propuesta_id, array('class' => 'form-control','placeholder'=>'Ingrese descripción'))}}
                                       <br>
                                      
                                        <div class="form-group form-actions">
                                           <div class="col-lg-12">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-user"></i> Editar</button>
                                            <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                        </div>
                                      </div>
                                    {{Form::close()}}
                                    <!-- END Normal Form Content -->
                                </div>


</div>


<script src="/adminsite/js/pages/tablesDatatables.js"></script>
<script>$(function(){ TablesDatatables.init(); });</script>

{{-- CKEditor 5 --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'), {
      ckfinder: {
        uploadUrl: '/file-manager/ckeditor'
      }
    })
    .catch(error => { console.error(error); });
</script>
@stop

