<div>
                <x-big-component id="permissionsModal" labelledby="permissionsModalLabel" title="EDITAR DATOS DEL TITULAR"
                saveAction="confirmSavePermiso()">
                <div>
                    <h1>aqui{{ $idUsuario }}</h1>
                    <div>
                        <h4>Mostrar los permisos del rol</h4>
                        <button wire:click="verPermiso({{ $idUsuario }})">Mostrar</button>
                    </div>
                    {{-- <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Guard Name</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td>
                                        <button wire:click="assignPermission({{ $permission->id }})"
                                            class="btn btn-primary">Assign</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>
               
            </x-big-component>
</div>
