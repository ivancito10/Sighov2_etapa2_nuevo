<div>
    <x-big-component id="editarFechaVencimientoModal" labelledby="editarFechaVencimientoModalLabel"
        title="MODIFICAR FECHA DE VENCIMIENTO" saveAction="guardarFecha()">
        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="cabecera">
                        <b>DETALLE DEL INGRESO</b>
                    </div>
                </div>
            </div>
            <div style="height: 0.5rem;"></div>
            <table class="tabla-ploma" style="width: 100%">
                <tr>
                    <td><b>FECHA DE INGRESO:</b> {{ $fecha_ingreso_mostrar }} </td>
                    <td><b>TIPO DE INGRESO:</b> {{ $tipo_proceso_editar }} </td>
                    <td><b>COD. INGRESO:</b> {{ $nro_ingreso_literal_editar }} </td>
                </tr>
            </table>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class ="col-lg-12 col-md-12 col-sm-12 col-12">
                <div style="max-height: 300px; overflow-y: auto; scrollbar-width: thin;">
                    <table class="table tabla-azul-oscuro">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CÓDIGO</th>
                                <th>MEDICAMENTO</th>
                                <th>FORMA FARMACEUTICA</th>
                                <th>CONCENTRACIÓN</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO UNITARIO</th>
                                <th>TOTAL</th>
                                <th>MARCA O PAIS ORIGEN</th>
                                <th>FECHA VENCIMIENTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($medicamentosIngresoArray)
                                @foreach ($medicamentosIngresoArray as $index => $medicamentoIngreso)
                                    <tr>
                                        <td scope="row">{{ $index + 1 }}</td>
                                        <td>{{ $medicamentoIngreso['codigo_medicamento'] }}</td>
                                        <td>{{ $medicamentoIngreso['nombre'] }}</td>
                                        <td>{{ $medicamentoIngreso['forma_farmaceutica'] }}</td>
                                        <td>{{ $medicamentoIngreso['concentracion'] }}</td>
                                        <td>{{ $medicamentoIngreso['cantidad'] }}</td>

                                        <td>
                                            {{ 'Bs ' . number_format($medicamentoIngreso['precio_unitario'], 2, ',', '.') }}
                                        </td>

                                        <td>
                                            {{ 'Bs ' . number_format($medicamentoIngreso['precio_total'], 2, ',', '.') }}
                                        </td>

                                        <td>{{ $medicamentoIngreso['pais_marca'] }}</td>
                                        <td style="min-width: 150px;">
                                            <input type="date" class="form-control form-control-sm"
                                                min = "{{ $fecha_min_mes }}"
                                                wire:model="medicamentosIngresoArray.{{ $index }}.fecha_vencimiento">
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" style="text-align: center;">No hay medicamentos disponibles.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-big-component>
</div>

<script>
    window.addEventListener('modalEditarFechaVencimiento', event => {
        $('#editarFechaVencimientoModal').modal('show');
    });

    function guardarFecha() {
        Swal.fire({
            title: '¿Estás seguro de guardar?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('editarFechaVencimientoFunction');
                //$('#editarFechaVencimientoModal').modal('hide');
            }
        });


    }


    /*     document.addEventListener('editadoFecha', function() {
            Swal.fire({
                title: "Guardado!",
                text: "El ingreso fue registrado exitosamente.",
                icon: "success",
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            $('#editarFechaVencimientoModal').modal('hide');
        }); */


    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('editadoFecha', function() {
            $('#editarFechaVencimientoModal').modal('hide');
            Swal.fire({
                title: "Ingreso actualizado",
                text: "Las fechas fueron modificadas satisfactoriamente",
                icon: "success"
            });
        });
    });
</script>
