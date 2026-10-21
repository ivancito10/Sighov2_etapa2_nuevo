<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <!-- Fila de logos -->
        <tr>
            <th colspan="1" style="text-align: left; background-color: #F2F2F2;border:none;">
                <img src="{{ public_path('images/logo_fondo_blanco.png') }}" class="logo" width="60%" height="60%">
            </th>
            <th colspan="6" style="text-align: center; font-size: 13px; background-color: #F2F2F2">
                <strong>CIERRE DE GESTIÓN - {{ $año }} </strong><br>
                <strong>SEGURO SOCIAL UNIVERSITARIO</strong><br>
                <strong>FARMACIA</strong>

            </th>
            <th colspan="2" style="text-align: right; background-color: #F2F2F2;border:none;">
                <img src="{{ public_path('images/logo_fondo_blanco.png') }}" class="logo" width="60%"
                    height="60%">
            </th>
        </tr>

        <!-- Fila de cabeceras -->
        <tr style="background-color: #912b2bff;">
            <th colspan="5" style="text-align: left;">
                <b>USUARIO:</b> {{ $nombre_usuario }}
            </th>
            <th colspan="2" style="text-align: left;">
                <b>C.I.: </b>{{ $ci_persona }}
            </th>
            <th colspan="2" style="text-align: left;">
                <b>FECHA:</b>
                {{ \Carbon\Carbon::parse($fecha_registro)->Format('d /m / Y') }}
            </th>
        </tr>
        <tr>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>NRO</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>MEDICAMENTO</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>CÓDIGO</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>FORMA FARMACÉUTICA</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>RECUENTO BIN CARD</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>RECUENTO FÍSICO</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>DIFERENCIAS</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>CANTIDAD BAJAS</b></th>
            <th style="text-align: left; background-color: #F2F2F2;  "><b>INVENTARIADOR</b></th>

        </tr>
    </thead>

    <tbody>
        @foreach ($cierre_gestion as $i => $cierre)
            <tr>
                <td class="celda">{{ $i + 1 }}</td>
                <td class="celda">{{ $cierre->medicamentoCierreGestion->nombre }} </td>
                <td class="celda">{{ $cierre->medicamentoCierreGestion->codigo_medicamento }} </td>
                <td class="celda">{{ $cierre->medicamentoCierreGestion->forma_farmaceutica }} </td>
                <td class="celda">{{ $cierre->cantidad_sistema }} </td>
                <td class="celda">{{ $cierre->cantidad_fisica }} </td>
                <td class="celda">{{ $cierre->diferencia }} </td>
                <td class="celda">{{ $cierre->cantidad_bajas }} </td>
                <td class="celda">{{ $cierre->usuarioCreador->name }} </td>
            </tr>
        @endforeach

    </tbody>
</table>
