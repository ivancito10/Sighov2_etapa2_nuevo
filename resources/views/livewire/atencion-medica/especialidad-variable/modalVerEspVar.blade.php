<div>
    <x-small-ver-modal id="verEspVar" labelledby="verEspVarLabel" title="VER VARIABLES" >
         
         
        <div class="row"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <p><b style="font-size:20px;">     {{$nombre_especialidad}}</b>    </p> 
            </div>
        </div> 
        <div class="table-responsive">
            <table class="table tabla-celeste">
                <thead style="text-align:center;">
                    <tr>
                        <th rowspan="3"> NRO </th>
                        <th rowspan="3"> NOMBRE</th>
                        <th rowspan="3"> TIPO</th>  
                    </tr> 
                </thead>
                <tbody> 
                    @forelse ($verEspVariables as $item)
                        <tr>
                            @php 
                                $tipo = $item->especialidadVariableVariable->tipo; 
                                $map = [
                                    'integer' => 'NÚMERO ENTERO',
                                    'decimal' => 'NÚMERO DECIMAL',
                                    'varchar' => 'TEXTO',
                                    'boolean' => 'VERDADERO / FALSO',
                                ]; 
                                $textoTipo = $map[$tipo] ?? strtoupper($tipo);
                            @endphp

                            <td style="text-align:center;"> {{ $loop->iteration }} </td>
                            <td style="text-align:left;">
                                <b>{{ $item->especialidadVariableVariable->nombre }}  </b> </td>
                            <td style="text-align:left;"> {{ $textoTipo }}</td> 
                        </tr> 
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; padding: 20px;">
                                <em>Sin registros</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-small-ver-modal>
</div>


<script>
    window.addEventListener('modalAbrirEspVar', event => {
        $('#verEspVar').modal('show');
    });
  
       
</script>