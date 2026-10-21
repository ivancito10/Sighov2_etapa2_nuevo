<div>
    <x-ver-modal id="modalVerCuenta" labelledby="modalVerCuentalLabel" title=" LISTA CUENTAS CONTABLES">

        <div class="row">
            <div class ="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div style="height: 400px; overflow-y: auto;">
                    <table class="tabla-celeste" style="width: 100%;">
                        <thead>
                            <tr>
                                <th> #</th>
                                <th> NRO. DE CUENTA</th>
                                <th> DESCRIPCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datos as $index => $data)
                                <tr>
                                    <td scope="row" style="text-align:center;">{{ $index + 1 }}</td>
                                    <td style="text-align:left;">{{ $data['codigo'] }}</td>
                                    <td style="text-align:left;">{{ $data['nombre'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </x-ver-modal>
</div>

<script>
    document.addEventListener('verCuentaContable', function() {
        $('#modalVerCuenta').modal('show');
    });
</script>
