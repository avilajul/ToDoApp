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
                    <i class="fa fa-align-justify"></i> Lista de Notas
                    <button type="button" @click="abrirModal('nota','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                  <option value="nombre">Nombre</option>
                                  <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarNota(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarNota(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Descripción</th>                                
                                <th>Categoría</th>                                
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="nota in arrayNotas" :key="nota.id">
                                <td>
                                    <button type="button" @click="abrirModal('nota','actualizar', nota)" class="btn btn-warning btn-sm">
                                      <i class="icon-pencil"></i>
                                    </button> &nbsp;
<!--                                    <template v-if="nota.condicion">
                                       <button class="btn btn-danger btn-sm" @click="desactivarNota(nota.id)">
                                           <i class="icon-trash"></i>
                                       </button>                                       
                                   </template>
                                   <template v-else>
                                       <button class="btn btn-danger btn-sm" @click="activarNota(nota.id)">
                                           <i class="icon-check"></i>
                                       </button>                                       
                                   </template>    -->                                
                                </td>
                                <td v-text="nota.descripcion"></td>
                                <td v-text="nota.nombre_categoria"></td>
                                <td>
<!--                                     <div v-if="nota.condicion">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>   -->                                  
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
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
                                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="id_categoria">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="codigo" class="form-control" placeholder="Código de barras">
                                    
                                     <svg id="barcode" v-bind:display="visible"></svg>         
                                </div>
                            </div>           
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de artículo">                                    
                                </div>
                            </div>       
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="precio_venta" class="form-control" placeholder="">                                    
                                </div>
                            </div>            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="stock" class="form-control" placeholder="">                                    
                                </div>
                            </div>   
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese Descripción">
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
    export default {
        data() { 
            return{
                articulo_id: 0,
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
                criterio: 'nombre',
                buscar: '',
                arrayCategoria: [],
                visible: 'none',
            }
        },

        watch: {
            codigo: function() {
                this.getCodigo()
            }
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
            listarNota(page, buscar, criterio){
                let me = this;
                var url = '/nota?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayNotas = respuesta.notas.data;
                    me.pagination = respuesta.pagination;                                        
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            selectCategoria(){
                let me = this;
                var url = '/categoria/selectCategoria';
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias;
                       
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envía la petición para visualizar la data de esta página
                me.listarNota(page,buscar,criterio);
            },
            registrarNota(){
                if(this.validarNota()){
                    return;
                }

                let me = this;

                axios.post('/nota/registrar',{
                    'id_categoria': this.id_categoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'precio_venta': this.precio_venta,
                    'stock': this.stock,
                    'descripcion': this.descripcion
                }).then(function(response){
                    me.cerrarModal();
                    me.listarNota(1,'','nombre');
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
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'precio_venta': this.precio_venta,
                    'stock': this.stock,
                    'descripcion': this.descripcion,
                    'id': this.articulo_id                    
                }).then(function(response){                    
                    me.cerrarModal();
                    me.listarNota(1,'','nombre');
                }).catch(function(error){
                    console.log(error);
                });                
            },
            activarNota(id){  
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: '¿Está seguro de activar este artículo?',                
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {                    
                    let me = this;

                    axios.put('/nota/activar',{
                        'id': id                    
                    }).then(function(response){
                        me.listarNota(1,'','nombre');
                        swalWithBootstrapButtons(
                        'Activado!',
                        'La artículo ha sido activado con éxito',
                        'success'
                        )                        
                    }).catch(function(error){
                        console.log(error);
                    });                       

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })                
            },            
            desactivarNota(id){                
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: '¿Está seguro de desactivar este artículo?',                
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {                    
                    let me = this;

                    axios.put('/nota/desactivar',{
                        'id': id                    
                    }).then(function(response){
                        me.listarNota(1,'','nombre');
                        swalWithBootstrapButtons(
                        'Desactivado!',
                        'El artículo ha sido desactivado con éxito',
                        'success'
                        )                        
                    }).catch(function(error){
                        console.log(error);
                    });                       

                } else if (
                    // Read more about handling dismissals
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
                if(!this.nombre){
                    this.errorMostrarMsjNota.push("El nombre del artículo no puede estar vacío.");
                }
                if(!this.precio_venta){
                    this.errorMostrarMsjNota.push("El precio de venta del artículo debe ser un número y no debe estar vacío.");
                }
                if(!this.stock){
                    this.errorMostrarMsjNota.push("El stock del artículo debe ser un número y no debe estar vacío.");
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
                this.codigo = '';
                this.nombre = '';
                this.precio_venta = 0;
                this.stock = 0;
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
                                this.nombre  ='';
                                this.precio_venta = 0;
                                this.stock = 0;
                                this.descripcion = '';
                                this.tipoAccion = 1; 
                                break;

                            }
                            case "actualizar":
                            {
                                this.modal = 1;
                                this.tituloModal = "Actualizar Nota de Actividad";
                                this.tipoAccion = 2;
                                this.articulo_id = data['id'];
                                this.id_categoria = data['id_categoria'];
                                this.codigo = data['codigo'];
                                this.nombre = data['nombre'];
                                this.precio_venta = data['precio_venta'];
                                this.stock = data['stock'];
                                this.descripcion = data['descripcion'];
                                break;                                
                            }
                        }
                    }
                }
                this.selectCategoria();
            }
        },
        mounted() {
            this.listarNota(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
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

