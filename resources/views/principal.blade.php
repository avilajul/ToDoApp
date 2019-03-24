<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplicación notas de actividades por hacer">
    <meta name="keyword" content="ToDo App, notas de actividades, Laravel Vue Js">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>To Do App</title>
    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
   
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/8.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                    <span class="d-md-down-none">{{Auth::user()->usuario}} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                    <a class="dropdown-item" href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> Cerrar sesión</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">
        @if(Auth::check())
            @if(Auth::user()->id_rol == 1)
                @include('plantilla.sidebarUsuario')            
            @endif
        @endif
        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>   
    </div>

    <!-- scripts -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
    
    <script>

        $(document).ready(function(){
            $('.dropdown-toggle').dropdown();
            
            //Envia una petición a la API através de Ajax
            $('.nombre_categoria').keyup(function(){     
                var sugerencia = $(this).val();
                if(sugerencia != ''){
                    $.ajax({
                        
                        url:"/categoria/buscarCategoria",                        
                        method:"GET",
                        data:{sugerencia:sugerencia},
                        
                        success:function(respuesta){

                            // Se crea una lista con los items de la respusta Ajax
                            var categoria = respuesta.categorias;
                            if($.isEmptyObject(categoria)== false){

                                
                                var salida = '<ul class="dropdown-menu" style="display:block; position:relative">';
                                
                                for (let i = 0; i < categoria.length; i++) {
                                    salida += "<li id="+categoria[i].id+"><a href='#'>"+categoria[i].nombre+"</a></li>";                              
                                }
                                salida += '</ul>';

                                $('.lista_categorias').fadeIn();  
                                $('.lista_categorias').html(salida); 
                            }
                            else{
                                $('.lista_categorias').fadeOut();                                                                  
                                $('.lista_categorias').html('');
                            }
                        }
                    });
                }
                else{
                    $('.lista_categorias').fadeOut();                    
                    $('.lista_categorias').html('');
                    $('.nombre_categoria').attr('name', '');
                }
                    
            });

            /* 
            * Envia el objeto        
            */
            
            $(document).on('click', 'li', function(){  
                $('.nombre_categoria').val($(this).text());
                $('.nombre_categoria').attr('name', this.id);  
                $('.lista_categorias').fadeOut();  
            });
            $(document).on('click', 'buscar', function(){  
                $('.nombre_categoria').val($(this).text());
                $('.lista_categorias').fadeOut();  
            });
 
    });      
    </script>
</body>

</html>