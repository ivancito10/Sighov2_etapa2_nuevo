<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'SIGHO v2',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b >SIGHO</b> v2',
    'logo_img' => 'vendor/adminlte/dist/img/ssu.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logosighov2.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 600,
            'height' => 400,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-default',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-default elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => true,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'Buscar',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'text' => 'Notificaciones',
            'url' => 'admin/notificaciones/index',
            'icon' => 'fas fa-fw fa-bell',
        ],
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Buscar',
        ],
        [
            'text' => 'Panel Principal',
            'url' => '/dashboard',
            // 'can' => 'manage-blog',
        ],
        // [
        //     'text' => 'pages',
        //     'url' => 'admin/pages',
        //     'icon' => 'far fa-fw fa-file',
        //     'label' => 4,
        //     'label_color' => 'success',
        // ],
        ['header' => 'MENÚ'],
        [
            'text' => 'Administración',
            'url' => '',
            'icon' => 'fas fa-fw fa fa-cogs text-info ui-draggable',
            'submenu' => [
                [
                    'text' => 'Roles y Permisos',
                    'url' => 'admin/RolesPermisos/index',
                    'icon' => 'nav-icon far fa fa-unlock-alt text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Usuarios',
                    'url' => 'admin/Usuarios/index',
                    'icon' => 'nav-icon far fa fa-user-secret text-default',
                    'can' => 'menu.usuarios',
                ],
                [
                    'text' => 'Formalización Código',
                    'url' => 'admin/formalizacion/codigo',
                    'icon' => 'nav-icon fas fa-grimace text-default',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Adm. Vista Lab.',
                    'url' => 'admin/laboratorio/vista_laboratorio',
                    'icon' => 'nav-icon fas fa-flask text-default',
                    'can' => 'super-admin-only',
                ],
            ],
        ],
        [
            'text' => 'Farmacia',
            'icon' => 'nav-icon fas fa-pills text-info',
            'submenu' => [
                [
                    'text' => 'Farmacia',
                    'icon' => 'nav-icon fas fa-capsules text-success',
                    'submenu' => [
                        [
                            'text' => 'Recetas',
                            'icon' => 'nav-icon fas fa-file-alt text-default',
                            'url' => 'farmacia/farmacia/receta/index',
                            'can' => 'menu.recetas_medicas',

                        ],
                        [
                            'text' => 'Solicitud',
                            'icon' => 'nav-icon fas fa-file-alt text-default',
                            'url' => 'farmacia/solicitudes/almacen/index',
                            'can' => 'menu.solicitudes_a_almacen_farmacia',

                        ],
                        [
                            'text' => 'Solicitudes de Unidades',
                            'icon' => 'nav-icon fas fa-clipboard-check text-default',
                            'url' => 'farmacia/solicitudes/index',
                            'can' => 'menu.farmacia_solicitudes_pendientes_unidades',
                        ],
                        [
                            'text' => 'Cierre de Gestión',
                            'icon' => 'nav-icon fas fa-clipboard-check text-default',
                            'url' => 'farmacia/farmacia/cierre-gestion/index',
                            'can' => 'menu.farmacia_cierre_gestion',
                        ],
                        /* [
                            'text' => 'Bind Card',
                            'url' => 'farmacia/farmacia/bind-card/index',
                            'icon' => 'nav-icon fas fa-chart-bar text-default',
                            'can' => 'menu.solicitud_almacen',
                        ], */
                        [
                            'text' => 'Stock',
                            'url' => 'farmacia/farmacia/stock/index',
                            'icon' => 'nav-icon fas fa-boxes text-default',
                            'can' => 'menu.stock_farmacia',
                        ],

                        [
                            'text' => 'Reingresos',
                            'url' => 'farmacia/farmacia/reingresos/index',
                            'icon' => 'nav-icon fas fa-exchange-alt text-default',
                            'can' => 'menu.solicitud_almacen',
                        ],

                    ],
                ],
                [
                    'text' => 'Almacen de Medicamentos',
                    'icon' => 'nav-icon fas fa-warehouse text-success',
                    'url' => '#',
                    'submenu' => [
                        [
                            'text' => 'Medicamentos',
                            'icon' => 'nav-icon fas fa-prescription-bottle text-default ml-3',
                            'url' => 'almacen_farmacia/medicamentos/index',
                            'can' => 'menu.medicamentos',

                        ],
                        [
                            'text' => 'Proveedores',
                            'icon' => 'nav-icon fas fa-truck text-default ml-3',
                            'url' => 'almacen_farmacia/proveedores/index',
                            'can' => 'menu.proveedores',

                        ],
                        [
                            'text' => 'Ingresos',
                            'icon' => 'nav-icon fas fa-dolly-flatbed text-default ml-3',
                            'url' => 'almacen_farmacia/ingresos/index',
                            'can' => 'menu.ingresos',

                        ],
                        [
                            'text' => 'Solicitudes de Unidades',
                            'icon' => 'nav-icon fas fa-clock text-default ml-3',
                            'url' => 'almacen_farmacia/solicitudes/index',
                            'can' => 'menu.almacen_farmacia_solicitudes_pendientes_unidades',

                        ],
                        [
                            'text' => 'Solicitudes de Farmacia',
                            'icon' => 'nav-icon fas fa-notes-medical text-default ml-3',
                            'url' => 'almacen_farmacia/solicitudes/farmacia/index',
                            'can' => 'menu.almacen_farmacia_solicitudes_pendientes_farmacia',

                        ],
                        [
                            'text' => 'Cierre de Gestión',
                            'icon' => 'nav-icon fas fa-clipboard-check text-default ml-3',
                            'url' => 'almacen_farmacia/farmacia/cierre-gestion/index',
                            'can' => 'menu.almacen_farmacia_cierre_gestion',

                        ],
                        /*  [
                            'text' => 'Bind Card',
                            'url' => 'almacen_farmacia/bind-card/index',
                            'icon' => 'nav-icon fas fa-chart-bar text-default ml-3',
                        ], */
                        [
                            'text' => 'Stock',
                            'url' => 'almacen_farmacia/stock/index',
                            'icon' => 'nav-icon fas fa-boxes text-default ml-3',
                            'can' => 'menu.almacen_farmacia_stock',

                        ],
                        [
                            'text' => 'Reingresos',
                            'url' => 'farmacia/almacenfarmacia/reingresos/index',
                            'icon' => 'nav-icon fas fa-exchange-alt text-default ml-3',
                            'can' => 'menu.ingresos',
                        ],

                    ],
                ],
                [
                    'text' => 'Solicitud de Unidades',
                    'icon' => 'nav-icon fas fa-file-signature text-info',
                    'url' => 'solicitud_insumos/index',
                    'can' => 'menu.solicitudes_unidades_farmacia',

                ],

                [
                    'text' => 'Solicitudes - Farmacia',
                    'icon' => 'nav-icon fas fa-hourglass-half text-warning',
                    'url' => 'solicitudes_pendientes/index',
                    'can' => 'menu.solicitudes_aprobacion_unidades_farmacia',

                ],
                [
                    'text' => 'Adm. Med. Receta',
                    'url' => 'atencion_medica/adm_med_receta/index',
                    'icon' => 'nav-icon fas fa-notes-medical text-default',
                    'can' => 'menu.super-admin-only',

                ],
            ],
        ],

        [
            'text' => 'Atención Médica',
            'url' => '',
            'icon' => 'fas fa-fw fa-hospital-user text-warning',
            'submenu' => [
                [
                    'text' => 'Consulta Externa',
                    'url' => 'atencion_medica/consulta_externa/index',
                    'icon' => 'nav-icon fas fa-user-md text-success ml-2',
                    'icon' => 'nav-icon fas fa-user-md text-success ml-2',
                    'can' => 'menu.consulta_externa',
                ],
                [
                    'text' => 'Epidemiología',
                    'url' => 'atencion_medica/epidemiologia/index',
                    'icon' => 'nav-icon fas fa-virus text-red ml-2',
                    'icon' => 'nav-icon fas fa-virus text-red ml-2',
                    'can' => 'menu.epidemiologia',
                ],
                [
                    'text' => 'Otras Consultas',
                    'url' => 'atencion_medica/otras_consultas/index',
                    'icon' => 'nav-icon fas fa-hospital text-default ml-2',
                    'icon' => 'nav-icon fas fa-hospital text-default ml-2',
                    'can' => 'menu.otras_consultas',
                ],
                [
                    'text' => 'Bajas Médicas',
                    'url' => 'atencion_medica/bajas_medicas/index',
                    'icon' => 'nav-icon fas fa-file-medical text-default ml-2',
                    'icon' => 'nav-icon fas fa-file-medical text-default ml-2',
                    'can' => 'menu.bajas_medicas',

                ],
                [
                    'text' => 'Variables',
                    'url' => 'atencion_medica/variables/index',
                    'icon' => 'nav-icon fas fa-puzzle-piece text-default ml-2',
                    'icon' => 'nav-icon fas fa-puzzle-piece text-default ml-2',
                    //'can' => 'menu.variables',
                    'can' => 'super-admin-only',
                ],
                [
                    'text' => 'Especialidad - Variable',
                    'url' => 'atencion_medica/especialidad_variables/index',
                    'icon' => 'nav-icon fas fa-sliders-h text-default ml-2',
                    'icon' => 'nav-icon fas fa-sliders-h text-default ml-2',
                    'can' => 'menu.variables_especialidades',

                ],

            ],
        ],
        [
            'text'    => 'Enfermería',
            'icon'    => 'nav-icon fas fa-clipboard-list text-orange ui-draggable',
            'submenu' => [
                [
                    'text' => 'Signos vitales',
                    'icon' => 'nav-icon fas fa-wave-square text-red ml-3',
                    'url'  => 'atencion-medica/enfermeria/signosvitales/index',
                    'can'  => 'menu.enfermeria',
                ],
                [
                    'text'   => 'Registrar Inyección',
                    'icon'   => 'nav-icon fas fa-syringe text-info ml-3',
                    'url'    => 'enfermeria/inyectables/index',
                    'active' => ['enfermeria/inyectables*'],
                    'can'    => 'menu.enfermeria',
                ],
            ],
        ],
        [
            'text' => 'Imagenología',
            'icon' => 'nav-icon fas fa-image text-blue',
            'url' => '',
            'submenu' => [
                [
                    'text' => 'Agenda Ecografía',
                    'url' => 'imagenologia/agenda_ecografia/index',
                    'icon' => 'nav-icon fas fa-book text-orange ml-3',
                    'can' => 'menu.agenda_ecografia_etapa2',

                ],
            ],

        ],

        [
            'text' => 'Almacen General',
            'icon' => 'nav-icon fas fa-building text-success ui-draggable',
            'url' => '',
            'submenu' => [
                [
                    'text' => 'Materiales',
                    'icon' => 'nav-icon fas fa-boxes text-default ml-3',
                    'url' => 'almacen_general/materiales/index',
                    'can' => 'menu.materiales_almacen_general',

                ],
                [
                    'text' => 'Proveedores',
                    'icon' => 'nav-icon fas fa-truck text-default ml-3',
                    'url' => 'almacen_general/proveedores/index',
                    'can' => 'menu.proveedores_almacen_general',

                ],
                [
                    'text' => 'Ingresos',
                    'icon' => 'nav-icon fas fa-dolly-flatbed text-default ml-3',
                    'url' => 'almacen_general/ingresos/index',
                    'can' => 'menu.ingresos_almacen_general',

                ],
                [
                    'text' => 'Solicitudes',
                    'icon' => 'nav-icon fas fa-clock text-default ml-3',
                    'url' => 'almacen_general/solicitudes_unidades/index',
                    'can' => 'menu.solicitudes_almacen_general',

                ],
                [
                    'text' => 'Cierre de Gestión',
                    'icon' => 'nav-icon fas fa-clipboard-check text-default ml-3',
                    'url' => 'almacen_general/cierre_gestion/index',
                    'can' => 'menu.cierre_gestion_almacen_general',

                ],
                /*[
                    'text' => 'Bind Card',
                    'url' => 'almacen_general/bin_card/index',
                    'icon' => 'nav-icon fas fa-file-invoice text-default ml-3',
                    'can' => 'menu.almacen_general',

                ],*/
                [
                    'text' => 'Stock',
                    'url' => 'almacen_general/stock/index',
                    'icon' => 'nav-icon fas fa-boxes text-default ml-3',
                    'can' => 'menu.stock_almacen_general',

                ],
                [
                    'text' => 'Reingresos',
                    'url' => 'almacen_general/reingresos/index',
                    'icon' => 'nav-icon fas fa-exchange-alt text-default ml-3',
                    'can' => 'menu.reingresos_almacen_general',
                ],
                [
                    'text' => 'Solicitud de Unidades',
                    'icon' => 'nav-icon fas fa-file-signature text-info',
                    'url' => 'solicitud_materiales/index',
                    'can' => 'menu.solicitudes_materiales_almacen_general', //'menu.solicitudes_unidades_almacen',
                ],
                //SOLAMENTE PARA EL REGISTRO DE SOLICITUDES RÁPIDAS, LUEGO SE COMENTARÁ Y SE OLVIDARÁ
                [
                    'text' => 'Solicitud de Unidades - Registro Directo',
                    'icon' => 'nav-icon fas fa-clock text-danger',
                    'url' => 'solicitud_materiales_directa/index',
                    'can' => 'menu.materiales_almacen_general',
                ],
                //FIN DE DICHO MENU
                [
                    'text' => 'Solicitudes - Almacen General',
                    'icon' => 'nav-icon fas fa-hourglass-half text-warning',
                    'url' => 'solicitudes_pendientes_materiales/index',
                    'can' => 'menu.solicitudes_pendientes_almacen_general',
                ],
            ],
        ],
        [
            'text' => 'Agendas',
            'url' => '',
            'icon' => 'fas fa-fw fa fa-address-book text-orange ui-draggable',
            'submenu' => [
                [
                    'text' => 'Agenda General',
                    'url' => 'agendas/agenda_general/index',
                    'icon' => 'nav-icon fas fa-book text-default ml-3',
                    'can' => 'menu.super-admin-only',

                ],
            ],
        ],
        [
            'text' => 'Rayos X',
            'url' => '',
            'icon' => 'fas fa-window-close text-blue',
            'submenu' => [
                [
                    'text' => 'Registrar',
                    'url' => 'rayos_x/index',
                    'icon' => 'fa fa-indent text-default ml-3',
                    'can' => 'menu.placas_imagenologia',

                ],
                [
                    'text' => 'Solicitudes RX',
                    'url' => 'rayos_x/solicitudes',
                    'icon' => 'fa fa-indent text-default ml-3',
                    'can' => 'menu.resultados_imagenologia',

                ],
            ],
        ],

        [
            'text' => 'Liquidaciones',
            'url' => '',
            'icon' => 'fas fa-fw fa fa-address-book text-orange ui-draggable',
            'submenu' => [
                [
                    'text' => 'Farmacia',
                    'url' => 'liquidaciones/farmacia/index',
                    'icon' => 'nav-icon fas fa-book text-default ml-3',
                    'can' => 'menu.liquidacion-recetas',

                ],
                [
                    'text' => 'Laboratorio',
                    'url' => 'liquidaciones/laboratorio/index',
                    'icon' => 'nav-icon fas fa-book text-default ml-3',
                    'can' => 'menu.liquidacion-laboratorios',
                ],
                [
                    'text' => 'Rayos X',
                    'url' => 'liquidaciones/rayosx/index',
                    'icon' => 'nav-icon fas fa-book text-default ml-3',
                    'can' => 'menu.liquidacion-rayosx',
                ],
                [
                    'text' => 'Agendas',
                    'url' => 'liquidaciones/agendas/index',
                    'icon' => 'nav-icon fas fa-book text-default ml-3',
                    'can' => 'menu.liquidacion-agendas',
                ],
            ],
        ],
        [
            'text' => 'Bioestadistica',
            'url' => '',
            'icon' => 'fas fa-fw fa-chart-bar text-warning ui-draggable',
            'submenu' => [
                [
                    'text' => 'Administracion CIE-10',
                    'url' => 'bioestadistica/administracion_cie10/index',
                    'icon' => 'nav-icon fas fa-user-md text-default ml-3',
                    'can' => 'menu.super-admin-only',

                ],

            ],
        ],
        [
            'text' => 'Fisioterapia',
            'can' => 'fisioterapia.acceder',
            'url' => '',
            'icon' => 'fas fa-fw fa fa-child ui-draggable icon-fisioterapia',
            'submenu' => [
                [
                    'text' => 'Administracion',
                    'icon' => 'fas fa-fw fa fa-cogs text-info ui-draggable ml-2 icon-administracion',
                    'submenu' => [
                        [
                            'text' => 'Fisioterapeutas',
                            'url' => 'fisioterapia/administracion/fisioterapeutas/index',
                            'icon' => 'nav-icon fas fa-fw fa-user-md ml-4 icon-fisioterapeutas',
                            'can' => 'fisioterapia.administrar',

                        ],
                        [
                            'text' => 'Políticas Agenda',
                            'url' => 'fisioterapia/administracion/politicas-agenda/index',
                            'icon' => 'nav-icon fas fa-calendar-alt ml-4 icon-politica-agenda',
                            'can' => 'fisioterapia.administrar',
                        ],
                        [
                            'text' => 'Técnicas',
                            'url' => 'fisioterapia/administracion/tecnicas/index',
                            'icon' => 'nav-icon fas fa-file-medical-alt ml-4 icon-tecnicas',
                            'can' => 'fisioterapia.tecnicas.ver',
                        ],
                    ],

                ],
                [
                    'text' => 'Ordenes',
                    'url' => 'fisioterapia/ordenes/index',
                    'icon' => 'nav-icon fas fa-file-invoice ml-2 icon-ordenes',
                    'can' => 'fisioterapia.administrar',
                ],
                [
                    'text' => 'Agendamiento',
                    'url' => 'fisioterapia/agendamiento/index',
                    'icon' => 'nav-icon fas fa-calendar-check ml-2 icon-agendamiento',
                    'can' => 'fisioterapia.administrar',
                ],
                [
                    'text' => 'Atencion',
                    'url' => 'fisioterapia/atencion/index',
                    'icon' => 'nav-icon fas fa-clipboard-list ml-2 icon-atencion',
                    'can' => 'fisioterapia.administrar',
                ],
            ],
        ],

        [
            'text' => 'change_password',
            'url' => 'admin/cambiocontrasenia/index',
            'icon' => 'fas fa-fw fa-lock',
        ],
        // [
        //     'text' => 'multilevel',
        //     'icon' => 'fas fa-fw fa-share',
        //     'submenu' => [
        //         [
        //             'text' => 'level_one',
        //             'url' => '#',
        //         ],
        //         [
        //             'text' => 'level_one',
        //             'url' => '#',
        //             'submenu' => [
        //                 [
        //                     'text' => 'level_two',
        //                     'url' => '#',
        //                 ],
        //                 [
        //                     'text' => 'level_two',
        //                     'url' => '#',
        //                     'submenu' => [
        //                         [
        //                             'text' => 'level_three',
        //                             'url' => '#',
        //                         ],
        //                         [
        //                             'text' => 'level_three',
        //                             'url' => '#',
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'text' => 'level_one',
        //             'url' => '#',
        //         ],
        //     ],
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];