@extends ('adminsite.layout')
 
 @section('cabecera')
  @parent
 @stop

@section('ContenidoSite-01')

<<div class="content-header">
 <ul class="nav-horizontal text-center">
  <li>
   <a href="/ge/register-user"><i class="fa fa-user-plus"></i> Registrar Datos Usuario</a>
  </li>
 <li>
   <a href="/ge/commercial"><i class="fa fa-list-ul"></i> Usuarios</a>
  </li>
   <li>
   <a href="/users/import"><i class="fa fa-list-ul"></i> Exportar/Importar</a>
  </li>
 </ul>
</div>


<div class="container">
 <?php $status=Session::get('status'); ?>
 @if($status=='ok_create')
  <div class="alert alert-success">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Usuario registrado con éxito</strong> CMS...
  </div>
 @endif

 @if($status=='ok_delete')
  <div class="alert alert-danger">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Usuario eliminado con éxito</strong> CMS...
  </div>
 @endif

 @if($status=='ok_update')
  <div class="alert alert-warning">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <strong>Usuario actualizado con éxito</strong> CMS...
  </div>
 @endif
</div>

<div class="container">


 
 <div class="block full">
  <div class="block-title">
   <h2><strong>Prospectos</strong> registrados</h2>
  </div>
            
  <div class="table-responsive">
   
<table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
 <thead>
  <tr>
   <th class="text-center">Nombres y Apellidos</th>
   <th class="text-center">Estado</th>
   <th class="text-center">Email</th>
   <th class="text-center">Creación</th>
   <th class="text-center">Acciones</th>
  </tr>
 </thead>
 <tbody>
 @foreach($usuarios as $usuariosa)
 <tr>
  <td class="text-center">{{$usuariosa->name}} {{$usuariosa->last_name}}</td>
  @foreach($funels as $funelsa)
   @if($usuariosa->funel_id == $funelsa->id)
    <td><span class="badge" style="background:{{$funelsa->color}}">{{$funelsa->funel}} {{$usuariosa->funel_id}}{{$funelsa->id}}</span></td>
   @endif
  @endforeach
  <td>{{$usuariosa->email}}</td>
  <td>{{$usuariosa->created_at}}</td>
  <td class="text-center">

        <a href="<?=URL::to('ge/proposal');?>/{{$usuariosa->id}}?utm_referido={{$usuariosa->referido_id}}&utm_fecha={{$usuariosa->fecha}}"><span  id="tip" data-toggle="tooltip" data-placement="left" title="Ver Porpuestas" class="btn btn-warning"><i class="fa fa-book sidebar-nav-icon"></i></span></a>

         <a href="https://api.whatsapp.com/send?phone=+57{{$usuariosa->phone}}&text=¿Hola cómo estás? 🖐 Bienvenido, Mi nombre es Samuel Martinez 👦, voy a asesorarte el día de hoy.
¡Dime cómo puedo ayudarte!" target="_blank"><span  id="tip" data-toggle="tooltip" data-placement="top" title="Contactar por Whatsapp" class="btn btn-success"><i class="fa fa-whatsapp sidebar-nav-icon"></i></span></a>

     
<a href="{{ route('ge.commercial.edit', $usuariosa->id) }}" class="btn btn-primary" title="Editar sector">
          <i class="fa fa-pencil-square-o sidebar-nav-icon"></i>
        </a>

        
        {{-- Eliminar --}}
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['ge.commercial.destroy', $usuariosa->id],
            'style' => 'display:inline',
            'onsubmit' => "return confirm('¿Está seguro que desea eliminar este sector?')"
        ]) !!}
          <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
            <i class="fa fa-trash"></i>
          </button>
        {!! Form::close() !!}
       </td>
      </tr>
     @endforeach

      
    </tbody>
   </table>
  </div>
 </div>
</div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

  <script src="/adminsite/js/pages/tablesDatatables.js"></script>
  <script>$(function(){ TablesDatatables.init(); });</script>

@stop