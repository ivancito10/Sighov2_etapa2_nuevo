<div>
    <div class="row d-flex justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card card-outline card-primary shadow">
                <div class="card-header text-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-print"></i> ¿En qué parte de la hoja desea imprimir?
                    </h3>
                </div>
                
                <div class="card-body bg-light d-flex flex-column align-items-center">
                    <p class="text-muted text-center text-sm mb-3">
                        Haga clic sobre la mitad de la hoja donde se encuentra su talonario físico.
                    </p>

                    <div class="position-relative bg-white shadow-sm border rounded" style="width: 240px; height: 320px; padding: 6px;">
                        
                        <div class="d-flex flex-column h-100">
                            
                            <div wire:click="seleccionarSector('superior')" 
                                 class="flex-grow-1 border m-1 d-flex flex-column align-items-center justify-content-center rounded {{ $sectorActivo === 'superior' ? 'bg-primary text-white shadow font-weight-bold' : 'bg-hover-light text-muted' }}" 
                                 style="cursor: pointer; transition: 0.2s;">
                                <i class="fas fa-arrow-up mb-2" style="font-size: 1.2rem;"></i>
                                <span class="text-xs text-center font-weight-bold">PARTE SUPERIOR</span>
                            </div>

                            <div wire:click="seleccionarSector('inferior')" 
                                 class="flex-grow-1 border m-1 d-flex flex-column align-items-center justify-content-center rounded {{ $sectorActivo === 'inferior' ? 'bg-primary text-white shadow font-weight-bold' : 'bg-hover-light text-muted' }}" 
                                 style="cursor: pointer; transition: 0.2s;">
                                <i class="fas fa-arrow-down mb-2" style="font-size: 1.2rem;"></i>
                                <span class="text-xs text-center font-weight-bold">PARTE INFERIOR</span>
                            </div>

                        </div>
                    </div>

                </div>
                
                <div class="card-footer bg-white border-top-0">
                    <button wire:click="exportarPdf" class="btn btn-success btn-lg btn-block shadow-sm">
                        <i class="fas fa-print"></i> Generar e Imprimir PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-hover-light:hover {
            background-color: #f8f9fa !important;
            border-color: #007bff !important;
            color: #007bff !important;
        }
    </style>
</div>