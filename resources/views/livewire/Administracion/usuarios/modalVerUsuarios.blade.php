<x-ver-modal id="verModalUsuario" labelledby="verModalUsuarioLabel" title=" VISTA DE USUARIO ">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <p style="background-color:ORANGE ; color:white; text-align:center;"> <b>DATOS USUARIO</b>
                    </p>
                    <div class="row">
                        <div class="col-lg-4" align="center">
                            <span align="center">
                                @if ($this->fotografia == null)
                                    <img src="{{ asset('images/default/user_default.jpg') }}" class="img-thumbnail"
                                        alt="..." width="170" height="230">
                                @else
                                    <img src="data:image/jpg;base64,{{ $this->fotografia }}" class="img-thumbnail"
                                        alt="..." width="170" height="230">
                                @endif

                                <br><b></b><em>{{ $this->v_edad }} Años</em></b>
                                <br><b style="color: green;"><em>ACTIVO</em></b>
                            </span>
                        </div>
                        <div class="col-lg-8">

                            <p><b>Nombre Usuario:</b> <em>{{ $this->v_name }}</em><br>
                                <b>Usuario:</b> <em>{{ $this->v_email }}</em><br>
                                <b>Nombre Persona:</b> <em>{{ $this->v_usuario_personar }}</em>
                                <br>
                                <b>Nro CI:</b> <em>{{ $this->v_ci }}
                                    {{ $this->v_complemento }}</em> <br>
                                <b>Fecha Nacimiento:</b> <em>{{ $this->v_fecha_nacimiento }}</em> <br>
                                <b>Número de Matrícula:</b> <em>{{ $this->v_matricula }}</em>
                            </p>


                        </div>
                        <div class="col-lg-12">
                            <div class="shadow p-3 mb-5 bg-white rounded">
                                <b>Datos de seguimiento:</b>
                                @if ($this->sessiones_c != 0)
                                    <p class="font-weight-lighter user-select-none" align="right">

                                    <table class="table table-hover">
                                        <tr>
                                            <td><b>Ultima fecha de inicio de sesión:</b> </td>
                                            <td><em>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-clock-history"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                                                        <path
                                                            d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                        <path
                                                            d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                    </svg>

                                                    {{ \Carbon\Carbon::parse($this->sesiones->login_at)->translatedFormat('l d \d\e F \d\e Y H:i') }}

                                                </em></td>
                                        </tr>
                                        <tr>
                                            <td><b>Ultima fecha de cierre de sessión:</b> </td>
                                            <td><em>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-clock-history"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                                                        <path
                                                            d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                        <path
                                                            d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($this->sesiones->logout_at)->translatedFormat('l d \d\e F \d\e Y H:i') }}

                                                </em></td>
                                        </tr>
                                        <tr>
                                            <td><b>IP conección:</b> </td>
                                            <td><em>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-pc-display-horizontal" viewBox="0 0 16 16">
                                                        <path
                                                            d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5M12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0m2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0M1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25" />
                                                    </svg>
                                                    {{ $this->sesiones->ip_address }}</em></td>
                                        </tr>
                                        <tr>
                                            <td><b>Equipo de conección:</b> </td>
                                            <td><em>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-pc-display-horizontal" viewBox="0 0 16 16">
                                                        <path
                                                            d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5M12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0m2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0M1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25" />
                                                    </svg>
                                                    {{ $this->sesiones->device_name }}</em></td>
                                        </tr>
                                    </table>
                                @else
                                    <p class="text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                            <path
                                                d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                        </svg>
                                        <b><em>No hay sesiones registradas. El usuario aun no a iniciado
                                                sessión.</em></b>
                                    </p>


                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12" align="right">
                            <span align="right">
                                <footer class="blockquote-footer">
                                    <p class="card-text"><b>Registrado por:</b>
                                        <em>{{ $this->v_usuario_registrador }}</em> <br>
                                        <b>Fecha de registro:</b>
                                        <em>{{ $this->v_fecha_registro }}</em>
                                    </p>
                                </footer>
                            </span>
                        </div>



                    </div>
                </div>
                <div class="col-lg-6">
                    <p style="background-color:skyblue ; color:white; text-align:center;"> <b>ROLES
                            ASIGNADOS</b>
                    </p>
                    <blockquote class="blockquote mb-0">

                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Rol</th>
                            </thead>
                            @foreach ($this->v_roles_asignados as $key => $roles)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td> {{ $roles }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-bookmark-check-fill" viewBox="0 0 16 16"
                                            style="color: green;">
                                            <path fill-rule="evenodd"
                                                d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5m8.854-9.646a.5.5 0 0 0-.708-.708L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </blockquote>
                </div>
            </div>


        </div>
    </div>




</x-ver-modal>

<script>
    window.addEventListener('modalVerUsuarios', event => {
        $('#verModalUsuario').modal('show');
    });
</script>
