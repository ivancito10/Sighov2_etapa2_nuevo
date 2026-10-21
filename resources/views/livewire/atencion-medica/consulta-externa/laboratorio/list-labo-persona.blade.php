<div>
    @if($id_persona) 
        <div class="card-body pt-2 pr-0 pl-0">
            @livewire('atencion-medica.consulta-externa.laboratorio.laboratorio-resultados-table' , ['id_persona' => $id_persona])
    
        </div>
    @endif
</div>
 
 

