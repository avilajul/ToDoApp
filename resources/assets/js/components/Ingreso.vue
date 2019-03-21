<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">Escritorio</a>
            </li>   
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- Listado -->
                <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                    <option value="tipo_comprobante">Tipo Comprobante</option>
                                    <option value="num_comprobante">Número Comprobante</option>
                                    <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Numero Comprobante</th>
                                        <th>Fecha - Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>             
                                        <th>Estado</th>                   
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id" >
                                        <td>
                                            <button type="button" @click="abrirModal('ingreso','actualizar', ingreso)" class="btn btn-warning btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>&nbsp;
                                        <template v-if="ingreso.estado == 'Registrado'">
                                            <button class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                <i class="icon-trash"></i>
                                            </button>                                  
                                        </template>                                                                    
                                        </td>
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.num_comprobante"></td>
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>
                                        <td v-text="ingreso.estado"></td>                                    
                                    </tr>
                                </tbody>
                            </table>                        
                        </div>

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
                </template>
                <!-- Detalle -->
                <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectProveedor"
                                        label="nombre"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDatosProveedor"                                        
                                    >
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tipo Comprobante(*)</label>
                                    <select name="" id="" class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option> 
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error"></div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000x">
                                </div>
                            </div>                        
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <label for="">Artículo <span style="color:red;" v-show="id_articulo==0">(*Seleccione)</span></label>
                                <div class="form-inline">
                                    <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                                    <button @click="abrirModal()" class="btn btn-primary">...</button>
                                    <input type="text" readonly class="form-control" v-model="articulo">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Precio <span style="color:red;" v-show="precio==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Cantidad <span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnAgregar">
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>                                               
                        </div>
                        <div class="form-group row border">
                            <div class="table-resoponsive col-md-12">
                                <table class="table table-border table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-s">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input  v-model="detalle.cantidad" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                               {{detalle.precio*detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="4" align="right"><strong>Subtotal</strong></td>
                                            <td >$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto</strong></td>
                                            <td >$ {{totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color:#CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto</strong></td>
                                            <td >$ {{total=calcularTotal}}</td>
                                        </tr>                                                                      
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">No hay artículos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                </template>
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
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                    <option value="codigo">Código</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA, criterioA)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio de Venta</th>
                                        <th>Stock</th>                                        
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td>
                                            <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                            <i class="icon-check"></i>
                                            </button>                              
                                        </td>
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre"></td>
                                        <td v-text="articulo.nombre_categoria"></td>
                                        <td v-text="articulo.precio_venta"></td>
                                        <td v-text="articulo.stock"></td>
                                        <td>
                                            <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>                                    
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarPersona()" class="btn btn-primary">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarPersona()" class="btn btn-primary">Actualizar</button>
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
    import vSelect from 'vue-select';
    export default {
        data() { 
            return{
                ingreso_id: 0,
                id_proveedor: '',
                nombre: '',
                tipo_comprobante: 'BOLETA',
                serie_comprobante: '',
                num_comprobante: '',
                impuesto: 0.18,
                total: 0.0,
                totalImpuesto: 0.0,
                totalParcial:0.0,
                arrayIngreso: [],
                arrayDetalle: [],
                arrayProveedor: [],
                listado:1,

                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorIngreso: 0,
                errorMostrarMsjIngreso: [],
                pagination : {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                criterio: 'num_comprobante',
                buscar: '',
                criterioA: 'nombre',
                buscarA: '',
                arrayArticulo: [],
                id_articulo: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad: 0
            }
        },
        components:{
            vSelect
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
            },
            calcularTotal: function(){
                var resultado = 0.0;
                for (let i = 0; i < this.arrayDetalle.length; i++) {
                    resultado = resultado+(this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad);       
                }
                return resultado;
            }         
        },
        methods:{
            listarIngreso(page, buscar, criterio){
                let me = this;
                var url = '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio ;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            selectProveedor(search,loading){
                let me=this;
                loading(true)

                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayProveedor=respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.id_proveedor = val1.id;
            },
            buscarArticulo(){
                let me=this;
                var url='/articulo/buscarArticulo?filtro='+ me.codigo;

                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if(me.arrayArticulo.length > 0)
                    {
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.id_articulo = me.arrayArticulo[0]['id'];
                    }
                    else
                    {
                        me.articulo = 'No existe artículo';
                        me.id_articulo = 0;
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envía la petición para visualizar la data de esta página
                me.listarIngreso(page,buscar,criterio);
            },
            encuentra(id){                
                var sw = false;               
                //recorre el arrayDetalle en busca del id retorna True si encuentra el id
                for (let i = 0; i < this.arrayDetalle.length; i++) {
                    if(this.arrayDetalle[i].id_articulo == id)
                    {
                        sw = true;                        
                    }                    
                }                
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },            
            agregarDetalle(){
                let me = this;
                //valida que los campos no se encuentren vacíos
                if(me.id_articulo == 0 || me.cantidad == 0 || me.precio == 0)
                {                    
                }
                else
                {
                    //valida que el artículo no se encuentre agregado
                    //lanza un mensaje de error si el articulo se agregó anteriormente
                    if(me.encuentra(me.id_articulo))
                    {
                        swal({
                            type:'error',
                            title: 'Error...',
                            text: '¡Ese Artículo ya se encuentra agregado!',
                        })
                    }
                    else
                    {                        
                        me.arrayDetalle.push({
                            id_articulo: me.id_articulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });
                        me.codigo = "";
                        me.id_articulo = 0;
                        me.articulo = "";
                        me.precio = 0;
                        me.cantidad = 0;
                    }
                }
            },
            agregarDetalleModal(data = []){
                let me = this;
                //valida que el artículo no se encuentre agregado
                //lanza un mensaje de error si el articulo se agregó anteriormente
                if(me.encuentra(data['id']))
                {
                    swal({
                        type:'error',
                        title: 'Error...',
                        text: '¡Ese Artículo ya se encuentra agregado!',
                    })
                }
                else
                {                        
                    me.arrayDetalle.push({
                        id_articulo: data['id'],
                        articulo: data['nombre'],
                        cantidad: 1,
                        precio: 1
                    });
                }
            },
            listarArticulo(buscar, criterio){
                let me = this;
                var url = '/articulo/listarArticulo?page=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos.data;                                  
                })
                .catch(function(error){
                    console.log(error);
                });
            },            
            registrarIngreso(){
                if(this.validarIngreso()){
                    return;
                }

                let me = this;

                axios.post('/ingreso/registrar',{
                    'id_proveedor': this.id_proveedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante': this.serie_comprobante,
                    'num_comprobante': this.num_comprobante,
                    'impuesto': this.impuesto,
                    'total': this.total,
                    'data': this.arrayDetalle                 
                   
                }).then(function(response){
                    me.listado = 1;
                    me.listarIngreso(1,'','num_comprobante');
                    me.id_proveedor = 0;
                    me.tipo_comprobante = 'BOLETA';
                    me.serie_comprobante = '';
                    me.num_comprobante = '';                   
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.id_articulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.arrayDetalle = [];

                }).catch(function(error){
                    console.log(error);
                });
            },
            validarIngreso(){
                this.errorIngreso = 0;
                this.errorMostrarMsjIngreso = [];
                
                if(this.id_proveedor == 0){
                    this.errorMostrarMsjIngreso.push("Seleccione un Proveedor");
                }
                if(this.tipo_comprobante == 0){
                    this.errorMostrarMsjIngreso.push("Seleccione el comprobante");
                }
                if(!this.num_comprobante){
                    this.errorMostrarMsjIngreso.push("Ingrese el número de comprobante");
                } 
                if(!this.impuesto){
                    this.errorMostrarMsjIngreso.push("Debes ingresar el impuesto de compra");
                }
                if(this.arrayDetalle.length <= 0){
                    this.errorMostrarMsjIngreso.push("Debes ingresar Detalles");
                }                                                
                if(this.errorMostrarMsjIngreso.length){
                    this.errorIngreso = 1;
                }

                return this.errorIngreso;
            },
            mostrarDetalle(){
                let me = this;
                me.listado = 0;
                me.id_proveedor = 0;
                me.tipo_comprobante = 'BOLETA';
                me.serie_comprobante = '';
                me.num_comprobante = '';                   
                me.impuesto = 0.18;
                me.total = 0.0;
                me.id_articulo = 0;
                me.articulo  = '';
                me.cantidad = 0;
                me.precio = 0;
                me.arrayDetalle = [];                
            },
            ocultarDetalle(){
                this.listado = 1;
            },            
            cerrarModal(){

                this.modal = 0;
                this.tituloModal = '';
            },            
            abrirModal(){
                this.arrayArticulo = [];                               
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios artículos';                
            }
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
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
    @media(min-width: 600px){
        .btnAgregar{
            margin-top: 2rem;
        }
    }
</style>

