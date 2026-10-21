<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
    <label for="ci">CARNET </label>
    <input type="number" class="form-control" disabled placeholder=" C.I" wire:model.live="ci" name="ci" id="ci"
        oninput="this.value = this.value.toUpperCase()">
</div>
<div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
    <label for="complemento">COMP. </label>
    <input type="text" class="form-control" disabled placeholder=" Complemento" wire:model.live="complemento"
        name="complemento" id="complemento" oninput="this.value = this.value.toUpperCase()">
</div>
<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12' wire:ignore>
    <label for="zona">TIPO DOC.</label>
    <select wire:model.live="tipo_doc" name="zona" id="zonas-select"
        class="form-control select2 @error('tipo_doc') is-invalid @enderror" style="width: 100%;" tabindex="-1"
        required>
        <option selected="selected">Seleccione.. </option>
        <option value="0">Nacional</option>
        <option value="1">Extranjero</option>
    </select>
</div>
<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
    <label for="zona">EXPEDIDO.</label>
    <select wire:model.live="expedido_ci" name="zona" id="zonas-select"
        class="form-control select2 @error('expedido_ci') is-invalid @enderror" style="width: 100%;" tabindex="-1"
        required>
        <option selected="selected">Seleccione.. </option>
        @foreach ($this->departamento as $dep)
            <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
        @endforeach
    </select>
</div>

<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
    <label for="nombre">NOMBRES </label>
    <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder=" Nombre"
        wire:model.live="nombre" name="nombre" id="nombre" oninput="this.value = this.value.toUpperCase()" wire:change='GenerarMatricula()'>
</div>
<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
    <label for="ap_paterno">PRIMER APELLIDO </label>
    <input type="text" class="form-control" placeholder=" Paterno" wire:model.live="ap_paterno" name="ap_paterno"
        id="ap_paterno" oninput="this.value = this.value.toUpperCase()" wire:change='GenerarMatricula()'>
</div>
<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
    <label for="ap_materno">SEGUNDO APELLIDO </label>
    <input type="text" class="form-control" placeholder=" Materno" wire:model.live="ap_materno" name="ap_materno"
        id="ap_materno" oninput="this.value = this.value.toUpperCase()" wire:change='GenerarMatricula()'>
</div>
<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
    <label for="fecha_nacimiento">FECHA NAC. </label>
    <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
        placeholder=" Fecha Nacimiento" wire:model.live="fecha_nacimiento" name="fecha_nacimiento" id="fecha_nacimiento"
        oninput="this.value = this.value.toUpperCase()" wire:change='GenerarMatricula()'>
</div>
<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
    <label for="zona">DEP. NAC.</label>
    <select wire:model.live="dep_nacimiento" name="zona" id="zonas-select"
        class="form-control select2 @error('dep_nacimiento') is-invalid @enderror" style="width: 100%;" tabindex="-1"
        required wire:change='ObtenerProvincia()'>
        <option selected="selected">Seleccione.. </option>
        @foreach ($this->departamento as $dep)
            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
        @endforeach
    </select>
</div>
<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
    <label for="zona">PRO. NAC.</label>
    <select wire:model.live="pro_nacimiento" name="zona" id="zonas-select"
        class="form-control select2 @error('pro_nacimiento') is-invalid @enderror" style="width: 100%;" tabindex="-1"
        required wire:change='ObtenerMunicipio()'>
        <option selected="selected">Seleccione.. </option>
        @if ($this->provincias)
            @foreach ($this->provincias as $pro)
                <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
            @endforeach
        @endif
    </select>
</div>
<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
    <label for="zona">MUN. NAC.</label>
    <select wire:model.live="mun_nacimiento" name="zona" id="zonas-select"
        class="form-control select2 @error('mun_nacimiento') is-invalid @enderror" style="width: 100%;"
        tabindex="-1" required>
        <option selected="selected">Seleccione.. </option>
        @if ($this->municipios)
            @foreach ($this->municipios as $mun)
                <option value="{{ $mun->id }}">{{ $mun->nombre }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
    <label for="sexo"> SEXO</label>
    <div class="col-sm-6 d-flex align-items-center">
        <label class="mr-2">M</label>
        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            <input wire:model="sexo" name="sexo" type="checkbox" class="custom-control-input" id="customSwitch2"
                wire:change="GenerarMatricula">
            <label class="custom-control-label" for="customSwitch2"
                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
        </div>
        <label class="ml-2">F</label>
    </div>
</div>

<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
    <label for="matricula"> MATRICULA</label>
    <input class="form-control" type="text" wire:model.live="matricula" disabled>
</div>
