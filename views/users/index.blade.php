@extends('adminsite.layout')

@section('cabecera')
  @parent
  <style>
    .kanban-board { display:flex; gap:16px; align-items:flex-start; overflow-x:auto; padding-bottom:10px; }
    .kanban-column { background:#f7f7f9; border-radius:6px; padding:8px; min-width:300px; max-width:360px; box-shadow:0 1px 4px rgba(0,0,0,0.05); }
    .kanban-column h3 { margin:0 0 8px; padding:8px; border-radius:4px; color:#fff; text-align:center; }
    .kanban-list { min-height:120px; max-height:70vh; overflow:auto; padding:6px; }
    .kanban-item { background:#fff; border:1px solid #e6e6e6; border-radius:4px; padding:8px; margin-bottom:8px; cursor:grab; }
    .kanban-item .meta { font-size:12px; color:#666; }
    .kanban-item.dragging { opacity:0.6; }
  </style>
@stop

@section('ContenidoSite-01')

<div class="content-header">
 <ul class="nav-horizontal text-center">
  <li>
   <a href="/ge/register-user"><i class="fa fa-user-plus"></i> Registrar Datos Usuario</a>
  </li>
<li>
   <a href="/ge/commercial-list"><i class="fa fa-list-ul"></i> Lista Usuarios</a>
  </li>
 </ul>
</div>

<div class="container mt-4">


  <div class="kanban-board">
    @foreach($funels as $funel)
      <div class="kanban-column" data-funel-id="{{ $funel->id }}">
        <h3 style="background: {{ $funel->color }}">{{ $funel->funel }}</h3>

        <div class="kanban-list" id="list-{{ $funel->id }}">
          @foreach($usuarios->where('funel_id', $funel->id) as $usuario)
            <div class="kanban-item" data-id="{{ $usuario->id }}">
              <strong>{{ $usuario->name }} {{ $usuario->last_name }}</strong>
              <div class="meta">{{ $usuario->empresa }} — {{ $usuario->email }} <br> {{ $usuario->phone }}</div>
              <div class="mt-1 text-right">
                <a href="{{ route('ge.commercial.edit', $usuario->id) }}" class="btn btn-xs btn-primary">Editar</a>
                <a href="https://api.whatsapp.com/send?phone=+57{{ $usuario->phone }}" target="_blank" class="btn btn-xs btn-success">WA</a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</div>
@stop


<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.kanban-list').forEach(function(listEl) {
    new Sortable(listEl, {
      group: 'kanban-usuarios',
      animation: 150,
      ghostClass: 'dragging',
      onEnd: function (evt) {
        let userId = evt.item.dataset.id;
        let newFunelId = evt.to.closest('.kanban-column').dataset.funelId;

        fetch("{{ url('/gestion/comercial/update-funel') }}/" + userId, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ funel_id: newFunelId })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            console.log('✅ Usuario actualizado en BD');
          } else {
            alert('⚠️ No se pudo actualizar el estado');
          }
        })
        .catch(err => {
          console.error('❌ Error al actualizar:', err);
          alert('No se pudo actualizar en el servidor.');
          // revertir movimiento si falla
          evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
        });
      }
    });
  });
});
</script>

