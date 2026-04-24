    <script setup lang='ts'>
    import {ref,reactive,watch} from 'vue';
    import type {consultafinalInterface,ProductoWrapper,serviceWrapper} from '@/interfaces/CitaForm.ts';
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    import { createPopper } from '@popperjs/core'
    import {useCita} from '@/composables/useCita.ts';
    import {ConsultaValidator} from '@/validations/AuthValidation.ts';
    import {useToast} from '@/composables/useToast.ts';
    const props = defineProps<{
        cita:any
    }>();
    const emit = defineEmits<{
        (e: 'enviarDatos'): void,
        (e:'cerrar'):void,
        (e:'creado'):void
    }>();
    function withPopper(dropdownList:any, component:any, { width }:any) {
        dropdownList.style.width = width
     dropdownList.style.zIndex = "10000000";
        const popper = createPopper(component.$refs.toggle, dropdownList, {
            placement: 'top', 
            modifiers: [
                { name: 'offset', options: { offset: [0, -1] } }
            ]
        })
        return () => popper.destroy()
    }
    let arrayservicioselected:any=[];
    //let arrayproductosselected:any =[];
    const cita = useCita();
    const isLoading = ref<boolean>(false);
    const dueño = ref<string>(props.cita.mascota.cliente.user.nombre);
    const mascota = ref<string>(props.cita.mascota.nombre);
    //const animal = ref<string>(props.cita.mascota.raza.animal.perro)
   // const raza = ref<string>(props.cita.mascota.raza.nombre)
    const descripcion_consulta = ref<string>(props.cita.consulta.descripcion_consulta);
    const indicaciones = ref<string>('');
   // const tipocita = ref<string>(props.cita.tipo)
    const errores = reactive<Record<string,string>>({});
    const fields = reactive<Record<string,string>>({});
    const prediagnostico = ref<string>(props.cita.consulta.pre_diagnostico);
    const productos = ref<any>(cita.consultaProductos());
    const servicios = ref<any>(cita.consultaServicios());
    const consultaobject = reactive<consultafinalInterface>({
        pre_diagnostico:prediagnostico.value,
        descripcion_consulta:descripcion_consulta.value,
        indicaciones: indicaciones.value,
        total_servicios: 0,
        total_producto: 0, 
        total: 0, 
        servicios: []
    });

    function calcularSubtotal(producto:any,servicio:any){
        if(!producto.tipo_venta || !producto.cantidad ||isNaN(producto.cantidad)){
            producto.subtotal = 0
        };
        if(producto.tipo_venta == 'Total'){
            const subtotal = producto.cantidad * producto.producto.precio_venta
            producto.subtotal = subtotal
        }
        else if(producto.tipo_venta == 'Fraccionado'){
            const subtotal = (producto.cantidad / producto.producto.cantidad) *  producto.producto.precio_venta
            producto.subtotal = subtotal
        }else{
            producto.subtotal=0;
        }
        servicio.precio_producto = servicio.productos.reduce((sum:any,p:any)=>sum+p.subtotal,0)
        servicio.total = Number(servicio.precio_producto ) + Number(servicio.precio_servicio);
        calcularTotal()
    }
    watch(()=>productos.value.data,(data)=>{
        if(!data.data){
            const {showToast} = useToast()
            showToast({status:'warning',customMessage:data.message})
        }
    })

    watch(()=>servicios.value.data,(data)=>{
        if(!data.data){
            const {showToast} = useToast()
            showToast({status:'warning',customMessage:data.message})
        }
    })

    function addproducto(awa:any){
        const myserviceobject = reactive<serviceWrapper>({
            servicio:awa,
            productList:[...productos?.value?.data?.data??[]],
            servicio_id:awa.id,
            detalles_servicio:"",
            precio_servicio:0,
            precio_producto:0,
            total:0,
            productos: [],
            visibilidad:true
        });
        consultaobject?.servicios.push(myserviceobject)
    }

    function TotalProductos(servicio:any){
        servicio.total = (Number(servicio.precio_servicio) + Number(servicio.precio_producto));
        calcularTotal();
    }

    function añadirProducto(product:any,servicio:any){
        const myproductobject = reactive<ProductoWrapper>({
            producto:product,
            producto_id:product.id,
            cantidad:0,
            tipo_venta:"",
            subtotal:0
        }); 
        const index:number = servicio.productList.findIndex((p:any)=>{
            return p.id == product.id
        })
        servicio.productos.push(myproductobject);
        servicio.productList.splice(index,1);

    }
    function cosaseleccionada(awa:any){
        const index:number =  servicios.value.data.data.findIndex((element:any)=>{
            return element.id == awa.id
        })

        servicios.value.data.data.splice(index,1);
        const mynewarraylist = servicios.value.data.data;

        const mynewdata = {
            data: mynewarraylist,
            message: servicios.value.data.message,
            error:servicios.value.data.error
        }
        servicios.value.data = mynewdata;
        arrayservicioselected.push(awa)

        addproducto(awa)
    }

    async function enviarDatos(){

        consultaobject.pre_diagnostico = prediagnostico.value
        consultaobject.descripcion_consulta=descripcion_consulta.value
        consultaobject.indicaciones= indicaciones.value
        Object.keys(errores).forEach(key => {
            delete errores[key];
        });
        fields['servicios']=consultaobject.servicios.length.toString();
        fields['prediagnostico'] = consultaobject.pre_diagnostico?.trim();
        fields['indicaciones']=consultaobject.indicaciones?.trim();
        fields['descripcion']=consultaobject.descripcion_consulta?.trim();
        consultaobject.servicios.forEach(service => {
            if(service.precio_servicio!=undefined && service.detalles_servicio!=undefined){
                fields[`${service.servicio_id} precio_servicio`] = service?.precio_servicio.toString()
                fields[`${service.servicio_id} detalles_servicio`] = service?.detalles_servicio.toString()?.trim() 
            }
            
            service.productos.forEach(product => {
                
                if(product.tipo_venta!=undefined && product.cantidad!=undefined){
                    fields[`${service.servicio_id} cantidad_usada ${product.producto_id}`] = product?.cantidad.toString()
                    fields[`${service.servicio_id} tipo_venta ${product.producto_id}`] = product?.tipo_venta.toString() ?? ""
                }else{
                    fields[`${service.servicio_id} tipo_venta ${product.producto_id}`] = ""
                }
                
            });
        });
        Object.keys(errores).forEach(key => {
            delete errores[key];
        });
        const result = ConsultaValidator(fields);
        Object.assign(errores,result.errors);
        if(result.isValid){
            const newobjet = consultaobject;
            const obj={
                ...newobjet,
                cita_id:props.cita.id,
                servicios:newobjet.servicios.map((service)=>{
                    const {productList,servicio,visibilidad,...resto} = service
                    const productos = service.productos.map((product)=>{
                        const {producto,...total} = product;
                        return total;
                    })
                    return {...resto,productos};
                })
            }
       isLoading.value = true;
       const respuesta = await cita.consultaUpdate(props.cita.consulta.id,obj)
       isLoading.value=false;
       if(respuesta.statusCode.value == 200){
        emit('creado');
       }else{
        const {showToast} = useToast();
            showToast({status:'error',customMessage:respuesta?.data?.value?.message ??respuesta?.data?.value?.error })
       }
      }
    }

    function eliminarservicio(serviceWrapper: serviceWrapper){
       const index =  consultaobject.servicios.findIndex((element)=>{
            return serviceWrapper.servicio_id === element.servicio_id
        })
      const servicio =  consultaobject.servicios.splice(index,1);
      const newlist = Array.from(servicios.value.data.data);
      newlist.push(servicio[0].servicio);
      newlist.sort((a:any,b:any)=>{
        return a.nombre.localeCompare(b.nombre,"es")
      })

      servicios.value.data = {
        data: newlist,
        message: servicios.value.data.message,
        error:servicios.value.data.error
      }

    }

    function eliminarproducto(producto: ProductoWrapper,servicio: serviceWrapper){
        const index = servicio.productos.findIndex((e)=>{
            return e.producto.id === producto.producto_id
        })
        const product = servicio.productos.splice(index,1);
        servicio?.productList.push(product?.[0].producto) ?? 0;
        if(servicio.precio_producto){
            servicio.precio_producto  -= product?.[0].subtotal ?? 0;
        }
        
         TotalProductos(servicio)
        servicio.productList.sort((a,b)=>{
            return a.nombre.localeCompare(b.nombre);
        })
       /* newArrayProducts.push(product);
        newArrayProducts.sort((a,b)=>{
        return a.nombre.localeCompare(b.nombre);
        })
        const */
    }

    function calcularTotal(){
        consultaobject.total_servicios = 0;
        consultaobject. total_producto = 0;
        consultaobject.total = 0;
        consultaobject.servicios.forEach((servicio)=>{
        consultaobject.total_servicios += Number(servicio.precio_servicio);
        consultaobject.total_producto += Number(servicio.precio_producto);
        })
        consultaobject.total = Number(consultaobject.total_servicios) + Number(consultaobject.total_producto);
    }
    const deselected = (producto: ProductoWrapper, servicewrapper: serviceWrapper) => {
        producto.tipo_venta = '';
        calcularSubtotal(producto, servicewrapper);
    }
    </script>

    <template>
    <Teleport to="body">
        <div class="modal-fondo fixed flex justify-center items-center inset-0 bg-black/40 backdrop-blur-sm transition-all duration-300 p-4 md:p-8" style="z-index: 9999999" @click.self="emit('cerrar')">
            
            <div class="rounded-[20px] p-4 sm:p-5 md:p-6 bg-[#e2d2c4] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex flex-col w-full max-w-[1000px] h-auto max-h-full md:max-h-[90vh]">
                
                <form @submit.prevent class="flex flex-col flex-1 min-h-0 h-full">
                    <h2 class="text-xl md:text-2xl font-extrabold text-[#523013] mb-2 shrink-0 truncate">Consulta: {{mascota}}</h2>
                    
                    <div class="overflow-y-auto pr-1 md:pr-2 pb-2 md:pb-4 flex-1 custom-scrollbar">
                        
                        <p class="text-[#523013] font-extrabold text-base md:text-lg border-b-2 border-[#a07a58] pb-1 mb-3">Información General</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 md:gap-y-4">
                            <div class="flex flex-col gap-3 md:gap-4">
                                <div>
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Dueño</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{dueño}}
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-baseline mb-1">
                                        <label class="block text-[#523013] font-bold text-xs md:text-sm opacity-80">Indicaciones</label>
                                        <span class="text-xs text-[#b51f1f] font-bold">{{errores['indicaciones']}}</span>
                                    </div>
                                    <textarea v-model="indicaciones" class="w-full py-2.5 md:py-3 px-3 md:px-4 rounded-md bg-[#fcf7f2] box-border font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none text-sm md:text-base outline-none focus:bg-white transition-colors" placeholder="Ingrese las indicaciones..."></textarea>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 md:gap-4">
                                <div>
                                    <label class="block text-[#523013] mb-1 font-bold text-xs md:text-sm opacity-80">Mascota</label>
                                    <div class="w-full py-2 px-3 rounded-md bg-[#fcf7f2] font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] cursor-not-allowed text-sm md:text-base">
                                        {{mascota}}
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-baseline mb-1">
                                        <label class="block text-[#523013] font-bold text-xs md:text-sm opacity-80">Diagnóstico / Prediagnóstico</label>
                                        <span class="text-xs text-[#b51f1f] font-bold">{{errores['prediagnostico']}}</span>
                                    </div>
                                    <textarea v-model="prediagnostico" class="w-full py-2.5 md:py-3 px-3 md:px-4 rounded-md bg-[#fcf7f2] box-border font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none text-sm md:text-base outline-none focus:bg-white transition-colors" placeholder="Ingrese el diagnóstico..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 md:mt-4">
                            <div class="flex justify-between items-baseline mb-1">
                                <label class="block text-[#523013] font-bold text-xs md:text-sm opacity-80">Descripción de la consulta</label>
                                <span class="text-xs text-[#b51f1f] font-bold">{{errores['descripcion']}}</span>
                            </div>
                            <textarea v-model="descripcion_consulta" class="w-full py-2.5 md:py-3 px-3 md:px-4 rounded-md bg-[#fcf7f2] box-border font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none text-sm md:text-base outline-none focus:bg-white transition-colors" placeholder="Ingrese una descripción breve..."></textarea>
                        </div>    
                        
                        <div class="mt-6 md:mt-8">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-2 border-[#a07a58] pb-1 mb-3 gap-2">
                                <p class="text-[#523013] font-extrabold text-base md:text-lg">Servicios y Productos</p>
                                <span class="text-xs text-[#b51f1f] font-bold">{{errores['servicios']}}</span>
                            </div>

                            <div class="mb-4 w-full md:w-[300px]">
                                <v-select
                                    :options="servicios.data?.data??[]"
                                    :key="servicios.data?.data?.length"
                                    :loading="servicios.isFetching"
                                    :calculate-position=" withPopper"
                                    append-to-body
                                    @option:selected='(selectedOption:any)=>cosaseleccionada(selectedOption)'
                                    :getOptionLabel="(object:any)=>object.nombre"
                                    placeholder="Añadir un nuevo servicio..."
                                />
                            </div>

                            <div v-for="servicewrapper in consultaobject.servicios" :key="servicewrapper.servicio_id" class="bg-[#fcf7f2] rounded-[16px] p-3 md:p-5 mb-4 shadow-sm border border-[#a07a58]/30">
                                
                                <div class="flex items-start md:items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 md:gap-3 min-w-0">
                                        <p class="font-extrabold text-[#523013] text-base md:text-lg truncate">{{servicewrapper.servicio.nombre}}</p>
                                        <button type="button" class="text-[#b51f1f] hover:text-red-700 hover:scale-110 transition-transform shrink-0" @click="eliminarservicio(servicewrapper)" title="Eliminar servicio">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5 cursor-pointer"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </button>
                                    </div>
                                    <button type="button" class="text-xs md:text-sm font-bold text-[#812c8f] hover:scale-95 cursor-pointer opacity-80 shrink-0" @click="servicewrapper.visibilidad = !servicewrapper.visibilidad">
                                        {{ servicewrapper.visibilidad ? 'Ocultar' : 'Mostrar' }}
                                    </button>
                                </div>

                                <div v-show="servicewrapper.visibilidad" class="mt-3 md:mt-4">
                                    
                                    <div class="mb-3 md:mb-4">
                                        <div class="flex justify-between items-baseline mb-1">
                                            <p class="text-[#523013] font-bold text-xs md:text-sm opacity-80">Detalles operativos</p> 
                                            <span class="text-xs text-[#b51f1f] font-bold">{{ errores[`${servicewrapper.servicio_id} detalles_servicio`]}}</span>
                                        </div>
                                        <textarea class="w-full py-2.5 md:py-3 px-3 md:px-4 rounded-md bg-[#fcf7f2] box-border font-extrabold text-[#523013] border-b-2 border-b-[#812c8f] resize-none text-sm md:text-base outline-none focus:bg-white transition-colors" 
                                        placeholder="Describa los detalles de la aplicación..." v-model="servicewrapper.detalles_servicio"></textarea>
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-4 mt-4">
                                        <p class="text-[#523013] font-bold text-xs md:text-sm shrink-0 opacity-80">Agregar Producto:</p>
                                        <div class="w-full sm:w-[250px]">
                                            <v-select
                                                :key="servicewrapper?.productList.length"
                                                :options="servicewrapper?.productList??[]"
                                                :getOptionLabel="(options:any)=>options.nombre"
                                                placeholder="Buscar producto..."
                                                :calculate-position=" withPopper"
                                                 append-to-body
                                                @option:selected="(producto:any)=>añadirProducto(producto,servicewrapper)"
                                            />
                                        </div>
                                    </div>

                                    <div v-if="servicewrapper.productos.length > 0">
                                        <div class="hidden md:grid grid-cols-12 gap-2 text-[#523013] font-bold text-xs border-b border-[#a07a58]/50 pb-1 text-center items-center opacity-80">
                                            <p class="col-span-4 text-left">Producto</p>
                                            <p class="col-span-3">Tipo Venta</p>
                                            <p class="col-span-2">Cant.</p>
                                            <p class="col-span-2 text-right">Subtotal</p>
                                            <p class="col-span-1"></p> 
                                        </div>

                                        <div class="mt-2 flex flex-col gap-3 md:gap-1">
                                            <div v-for="producto in servicewrapper.productos" :key="producto.producto_id" 
                                            class="flex flex-col md:grid md:grid-cols-12 gap-2 md:gap-2 items-start md:items-center text-sm font-medium bg-[#e2d2c4]/20 md:bg-transparent p-3 md:p-0 rounded-lg md:rounded-none border border-[#a07a58]/20 md:border-none">
                                                
                                                <div class="flex justify-between w-full md:col-span-4 md:w-auto items-center">
                                                    <p class="font-bold truncate text-[#812c8f] text-sm" :title="producto.producto.nombre">{{producto.producto.nombre}}</p>
                                                    <button type="button" class="md:hidden text-[#b51f1f] hover:scale-110" @click="eliminarproducto(producto,servicewrapper)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                    </button>
                                                </div>
                                                
                                                <div class="flex gap-2 w-full md:contents">
                                                    <div class="flex-1 md:col-span-3 flex flex-col justify-center">
                                                        <label class="md:hidden text-[10px] font-bold text-[#523013] opacity-60 mb-0.5">Tipo</label>
                                                        <v-select
                                                            :options="['Total','Fraccionado']"
                                                            :searchable="false"
                                                            :calculate-position=" withPopper"
                                                            append-to-body
                                                            @option:selected="()=>calcularSubtotal(producto,servicewrapper)"
                                                            @update:modelValue="(val:any) => {val ? calcularSubtotal(producto,servicewrapper):deselected(producto,servicewrapper)}"
                                                            v-model="producto.tipo_venta"
                                                            placeholder="Tipo..."
                                                        />
                                                        <span class="text-[10px] text-[#b51f1f] leading-none mt-1 text-center" v-if="errores[`${servicewrapper.servicio_id} tipo_venta ${producto.producto_id}`]">Requerido</span>
                                                    </div>
                                                    
                                                    <div class="w-[80px] md:w-auto md:col-span-2 flex flex-col justify-center">
                                                        <label class="md:hidden text-[10px] font-bold text-[#523013] opacity-60 mb-0.5 text-center">Cant.</label>
                                                        <input type="text" class="w-full bg-[#fcf7f2] border border-[#a07a58]/50 border-b-2 border-b-[#812c8f] rounded px-2 py-1 md:py-1.5 text-center text-[#523013] outline-none focus:bg-white" v-model="producto.cantidad" placeholder="0" @input="calcularSubtotal(producto,servicewrapper)">
                                                        <span class="text-[10px] text-[#b51f1f] leading-none mt-1 text-center" v-if="errores[`${servicewrapper.servicio_id} cantidad_usada ${producto.producto_id}`]">Requerido</span>
                                                    </div>
                                                </div>

                                                <div class="w-full flex justify-between items-center md:contents">
                                                    <div class="flex items-center gap-2 md:contents">
                                                        <label class="md:hidden text-xs font-bold text-[#523013] opacity-80">Subtotal:</label>
                                                        <p class="md:col-span-2 text-right font-black text-lg md:text-base text-[#b51f1f]">${{producto.subtotal}}</p>
                                                    </div>
                                                    
                                                    <div class="hidden md:flex col-span-1 justify-end">
                                                        <button type="button" class="text-[#b51f1f] hover:text-red-700 hover:scale-110 transition-transform" @click="eliminarproducto(producto,servicewrapper)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="text-center text-xs md:text-sm font-bold text-[#523013] opacity-50 my-3">No hay productos agregados a este servicio.</div>
                                    
                                    <div class="grid grid-cols-3 gap-4 mt-4 pt-3 border-t-2 border-[#a07a58] text-center">
                                        <div class="flex flex-col items-center"> 
                                            <div class="flex gap-1 items-baseline">
                                                <p class="font-bold text-sm text-[#812c8f] opacity-80">Precio Servicio</p>
                                            </div>
                                            <div class="flex flex-col w-[100px]">
                                                <div class="relative">
                                                    <span class="absolute left-2 top-1 text-[#523013] font-bold opacity-70">$</span>
                                                    <input type="number" class="w-full bg-[#fcf7f2] border border-[#a07a58]/50 border-b-2 border-b-[#812c8f] rounded pl-5 pr-2 py-1 text-center font-bold text-[#523013] outline-none focus:bg-white" v-model="servicewrapper.precio_servicio" @input="TotalProductos(servicewrapper)">
                                                </div>
                                                <span class="text-[10px] text-[#b51f1f] leading-none mt-1" v-if="errores[`${servicewrapper.servicio_id} precio_servicio`]">Requerido</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-center justify-start">
                                            <p class="font-bold text-sm text-[#812c8f] opacity-80">Total Productos</p>
                                            <p class="font-bold mt-1 text-[#812c8f]">${{servicewrapper.precio_producto}}</p>
                                        </div>
                                        <div class="flex flex-col items-center justify-start">
                                            <p class="font-bold text-sm text-[#b51f1f] opacity-80">Total Servicio</p>
                                            <p class="font-bold mt-1 text-lg text-[#b51f1f]">${{servicewrapper.total}}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                            
                    <div class="shrink-0 pt-4 border-t-2 border-[#a07a58]">
                        <div class="grid grid-cols-2 md:flex items-center justify-between gap-2 md:gap-4 mb-3 md:mb-4">
    
    <div class="flex-1 text-center bg-[#fcf7f2] py-2 px-2 md:px-4 rounded-lg">
        <p class="text-[#812c8f] font-bold text-xs md:text-sm opacity-80">Total Servicios</p>
        <p class="text-[#812c8f] font-extrabold text-sm md:text-base">${{consultaobject.total_servicios}}</p>
    </div>
    
    <div class="flex-1 text-center bg-[#fcf7f2] py-2 px-2 md:px-4 rounded-lg">
        <p class="text-[#812c8f] font-bold text-xs md:text-sm opacity-80">Total Productos</p>
        <p class="text-[#812c8f] font-extrabold text-sm md:text-base">${{consultaobject.total_producto}}</p>
    </div>
    
    <div class="col-span-2 md:flex-1 text-center bg-[#fcf7f2] py-2 md:py-2 px-2 md:px-4 rounded-lg border-2 border-[#b51f1f]/20"> 
        <p class="text-[#b51f1f] font-bold text-sm opacity-80">Total a pagar</p>
        <p class="text-[#b51f1f] font-extrabold text-xl">${{consultaobject.total}}</p>
    </div>
    
</div>

                        <div class="flex justify-center gap-4">
                            <button type="button" class="flex-1 py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#b51f1f] hover:scale-[1.02] hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#b51f1f]" @click="emit('cerrar')" :disabled="isLoading">
                                Cancelar
                            </button>
                            <button type="button" class="flex-1 flex justify-center items-center py-3 font-extrabold rounded-md cursor-pointer transition-all duration-200 bg-[#fcf7f2] text-[#812c8f] hover:scale-[1.02] hover:shadow-lg hover:text-[#fcf7f2] hover:bg-[#812c8f]" @click="enviarDatos" :disabled="isLoading">
                                <div v-if="isLoading" class="loader"></div>
                                <span v-else>Guardar Consulta</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@reference "tailwindcss";

.loader {
    margin:auto;
    width: 25px;
    aspect-ratio: 1;
    border-radius: 50%;
    border: 3px solid #812c8f;
    animation:
        l20-1 0.8s infinite linear alternate,
        l20-2 1.6s infinite linear;
}

button:hover .loader {
    border-color: white;
}

@keyframes l20-1{
   0%    {clip-path: polygon(50% 50%,0       0,  50%   0%,  50%    0%, 50%    0%, 50%    0%, 50%    0% )}
   12.5% {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100%   0%, 100%   0%, 100%   0% )}
   25%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 100% 100%, 100% 100% )}
   50%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   62.5% {clip-path: polygon(50% 50%,100%    0, 100%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   75%   {clip-path: polygon(50% 50%,100% 100%, 100% 100%,  100% 100%, 100% 100%, 50%  100%, 0%   100% )}
   100%  {clip-path: polygon(50% 50%,50%  100%,  50% 100%,   50% 100%,  50% 100%, 50%  100%, 0%   100% )}
}
@keyframes l20-2{ 
  0%    {transform:scaleY(1)  rotate(0deg)}
  49.99%{transform:scaleY(1)  rotate(135deg)}
  50%   {transform:scaleY(-1) rotate(0deg)}
  100%  {transform:scaleY(-1) rotate(-135deg)}
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(160, 122, 88, 0.3);
  border-radius: 10px;
}

/* TUS ESTILOS ORIGINALES DE V-SELECT INTACTOS */
:deep(.vs__dropdown-toggle) {
    width:100%;
    background-color: #F5EBE0;
    border: 0.5px solid #D4B8A0;
    border-radius: 8px;
}

:deep(.vs__search) {
    color: #3B2A1A;
}

:deep(.vs__dropdown-menu) {

    width: 100%;
    max-height:200px;
    margin-top:4px;
    background-color: #F5EBE0;
    border: 0.5px solid #D4B8A0;
    z-index: 9999; /* Asegura que caiga sobre el contenido scrolleable */
}

:deep(.vs_no-options){
    display: none;
}

:deep(.vs__dropdown-option--highlight) {
    background-color: #C0392B;
    color: white;
}

:deep(.vs__selected-options){
    flex-wrap:nowrap;
    overflow-y:auto;
    scrollbar-width: none;
}

:deep(.vs__selected) {
    color: #3B2A1A;
    background-color: #EDD9C8;
    border: 0.5px solid #D4B8A0;
    white-space: nowrap;
}
</style>