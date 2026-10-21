<div>
    <style>
        .small-box.bg-animated-info {
            background: linear-gradient(-45deg, #0d6efd, #17a2b8, #20aac9, #0a3c9a);
            background-size: 400% 400%;
            animation: gradientMove 8s ease infinite;
            color: #fff !important;
        }
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>

    <div class="row">
        <!-- Usuarios Titulares  -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-animated-info">
                <div class="inner">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_titulares_activos }}</h3>
                            <small><b>Activos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_titulares_inactivos }}</h3>
                            <small><b>Inactivos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_titulares_inactivos + $usuarios_titulares_activos }}</h3>
                            <small><b>TOTAL</b></small>
                        </div>
                    </div>
                    <h4 class="mt-2 mb-0" style="font-weight:700;">
                        Usuarios Titulares
                    </h4>
                </div>
                <div class="icon"><i class="fas fa-user-tie"></i></div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Usuarios beneficiarios  -->
        <div class="col-lg-3 col-md-6  col-12">
            <div class="small-box bg-animated-info">
                <div class="inner">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_beneficiarios_activos }}</h3>
                            <small><b>Activos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_beneficiarios_inactivos }}</h3>
                            <small><b>Inactivos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_beneficiarios_activos + $usuarios_beneficiarios_inactivos }}</h3>
                            <small><b>TOTAL</b></small>
                        </div>
                    </div>
                    <h4 class="mt-2 mb-0" style="font-weight:700;">
                        Usuarios Beneficiarios
                    </h4>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Usuarios estudiantes  -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-animated-info">
                <div class="inner">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_estudaintes_activos }}</h3>
                            <small><b>Activos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_estudaintes_inactivos }}</h3>
                            <small><b>Inactivos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_estudaintes_activos + $usuarios_estudaintes_inactivos }}</h3>
                            <small><b>TOTAL</b></small>
                        </div>
                    </div>
                    <h4 class="mt-2 mb-0" style="font-weight:700;">
                        Usuarios Estudiantes
                    </h4>
                </div>
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
         <!-- Usuarios internos y externos  -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="small-box bg-animated-info">
                <div class="inner">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_internos }}</h3>
                            <small><b>Internos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_externos }}</h3>
                            <small><b>Externos</b></small>
                        </div>
                        <div style="text-align:center;">
                            <h3>{{ $usuarios_internos + $usuarios_externos }}</h3>
                            <small><b>TOTAL</b></small>
                        </div>
                    </div>
                    <h4 class="mt-2 mb-0" style="font-weight:700;">
                        Usuarios Int. y Ext.
                    </h4>
                </div>
                <div class="icon"><i class="fas fa-user-plus"></i></div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
