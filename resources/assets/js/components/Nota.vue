<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">    
                <a href="/">Mis Notas</a>
            </li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Listado Notas de Actividades
                    <button type="button" @click="abrirModal('nota','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <!-- Buscador de Categorias Autocomplementable -->                            
                            <input type="text" name="buscar" id="id_categoria" class="nombre_categoria form-control" placeholder="Ingresa el nombre de la categoría" />
                            <div class="lista_categorias">
                            </div>                            
                        </div>
                        <div class="col-md-6 col-6">
                            <button type="submit" @click="buscarNotas(1,buscar)" class="buscar btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            <button type="submit" @click="listarNotas(1,buscar)" class="buscar btn btn-primary">Ver todo</button>
                        </div>                       
                    </div>
                    <template v-if="listado >= 1" >
                    <div class="row">
                        <div class="col-md-12 col-12">
                            
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>                                
                                    <th>Descripción</th>                                
                                    <th>Categoría</th>                                
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="nota in arrayNotas" :key="nota.id">
                                    <td v-text="nota.descripcion"></td>
                                    <td v-text="nota.nombre_categoria"></td>
                                    <td>
                                        <button type="button" @click="abrirModal('nota','actualizar', nota)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button class="btn btn-danger btn-sm" @click="borrarNota(nota.id)">
                                            <i class="icon-trash"></i>
                                        </button>                           
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                                </li>
                            </ul>
                        </nav>                            
                        </div>
                        
                    </div>
                    </template>
                    <template v-else>
                        <div class="row">
                            <div class="alert alert-danger col md-4 col-4" role="alert">
                                <strong>No se encontraron Notas de actividades!</strong>. Intentalo nuevamente.
                            </div>
                        </div>
                        
                    </template>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese Descripción">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                <div class="col-md-9 col-12">
                                    <select class="form-control" v-model="id_categoria">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div v-show="errorNota" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjNota" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarNota()" class="btn btn-primary">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarNota()" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>

import $ from 'jquery'

    export default {
        data() { 
            return{
                nota_id: 0,
                id_categoria: 0,
                nombre_categoria: '',                                
                nombre: '',                
                descripcion: '',
                arrayNotas: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorNota: 0,
                errorMostrarMsjNota: [],
                pagination : {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                buscar: '',
                arrayCategoria: [],
                visible: 'none',
                listado: 1
            }
        },

        watch: {
        },        
   
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }            
        },
        methods:{
            listarNotas(page, buscar){
                let me = this;
                var listar = $('#id_categoria').attr('name');

                if(buscar == 'buscar'){
                    buscar = '';
                }

                var url = '/nota';
                              
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayNotas = respuesta.notas.data;
                    me.pagination = respuesta.pagination;  
                    me.listado = respuesta.pagination.total;                                      
                })
                .catch(function(error){
                    me.pagination = 0;
                    console.log(error);
                });
            },
            buscarNotas(page, buscar){
                let me = this;
                var buscarC = $('#id_categoria').attr('name');

                if(buscarC == 'buscar'){
                    buscarC = '';
                }

                var url = '/nota/buscarNota?page=' + page + '&buscarC=' + buscarC;
                              
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayNotas = respuesta.notas.data;
                    me.pagination = respuesta.pagination;
                    me.listado = respuesta.pagination.total;                                
                })
                .catch(function(error){
                    me.pagination = 0;
                });
                buscarC = '';
            },            
            buscarCategoria(){
                let me = this;
                var url = '/categoria/buscarCategoria?';
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias;                       
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envía la petición para visualizar la data de esta página
                me.listarNotas(page,buscar);
            },
            registrarNota(){
                if(this.validarNota()){
                    return;
                }

                let me = this;

                axios.post('/nota/registrar',{
                    'id_categoria': this.id_categoria,
                     'descripcion': this.descripcion                   
                }).then(function(response){
                    me.cerrarModal();
                    me.listarNotas(1,'');
                }).catch(function(error){
                    console.log(error);
                });
            },
            actualizarNota(){
                if(this.validarNota()){
                    return;
                }

                let me = this;

                axios.put('/nota/actualizar',{
                    'id_categoria': this.id_categoria,
                    'descripcion': this.descripcion,
                    'id': this.nota_id       
                }).then(function(response){                    
                    me.cerrarModal();
                    me.listarNotas(1,'','buscar');
                }).catch(function(error){
                    console.log(error);
                });                
            },
            
            borrarNota(id){                
                const swalWithBootstrapButtons = swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                    title: '¿Está seguro de borrar esta Nota?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {                    
                        let me = this;

                        axios.put('/nota/borrar',{
                            'id': id                    
                        }).then(function(response){
                            me.listarNotas(1,'','buscar');
                            swalWithBootstrapButtons(
                            'Borrado!',
                            'La nota ha sido borrada con éxito',
                            'success'
                            )                        
                        }).catch(function(error){
                            console.log(error);
                        });                       

                    } else if (                    
                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                })
            },
            validarNota(){
                this.errorNota = 0;
                this.errorMostrarMsjNota = [];
                
                if(this.id_categoria == 0){
                    this.errorMostrarMsjNota.push("Selecciona una categoría.");
                }
                if(!this.descripcion){
                    this.errorMostrarMsjNota.push("Agrega una descripción a nota de actividad.");
                }

                if(this.errorMostrarMsjNota.length){
                    this.errorNota = 1;
                }

                return this.errorNota;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre_categoria  = '';
                this.descripcion = '';
                this.errorNota = 0;
            },            
            abrirModal(modelo, accion, data = []){
                switch (modelo) {
                    case "nota":
                    {
                        switch (accion) {
                            case "registrar":
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registar Nota de Actividad';
                                this.id_categoria = 0;
                                this.nombre_categoria = '';
                                this.descripcion  ='';
                                this.tipoAccion = 1; 
                                break;

                            }
                            case "actualizar":
                            {
                                this.modal = 1;
                                this.tituloModal = "Actualizar Nota de Actividad";
                                this.tipoAccion = 2;
                                this.nota_id = data['id'];
                                this.id_categoria = data['id_categoria'];                                
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                break;                                
                            }
                        }
                    }
                }
                this.buscarCategoria();
            }
        },
        mounted() {
            this.listarNotas(1,this.buscar);
        }
    }
</script>
<style>
    select.form-control:not([size]):not([multiple]) {
    height: calc(3.09375rem + 2px);
}
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        background-color: #3c29297a;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>

