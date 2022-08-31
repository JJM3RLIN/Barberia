document.addEventListener("DOMContentLoaded", () => {
  eliminarAlertas();
  cambiarSeccion();

  //Para que se muestre automaticamente la primera seccion
  mostrarSeccion();

  //Para mostrar los botones del paginador
  botonesPaginador();
  //que al dar click en el paginador cambie la seccion
  paginaSiguiente();
  paginaAnterior();

  //Obtener el JSON creado en php
  consultarAPI();

  nombreCliente(); //Añadir el nombre en el form de info
  seleccionarFecha(); //Añade fecha de la cita en el objeto
  seleccionarHora(); //guardar la fecha

  mostrarResumen();
});
let pagina = 1;
//Para poder mostrar hasta donde nosostros queramos y que al aumentar
//pagina esta no aumente hasta una que no existe
const inicio = 1;
const final = 3;

const cita ={
   id: '',
   nombre: '',
   fecha: '',
   hora: '',
   servicios: []
}
function eliminarAlertas() {
  //Obtenemos todas las alertas
  const alertas = document.querySelectorAll(".alerta");
  alertas.forEach((alerta) => {
    //Obtenemos el padre -> en donde estan contenidas las alertas
    const padre = alerta.parentElement;

    //Que se eliminen despues de 3500ms
    setTimeout(() => {
      //Las eliminamos de en donde estan contenidos
      padre.removeChild(alerta);
    }, 3500);
  });
}
//Al presionar el btn que nos pase la clase de actual que se presiono
//y que nos muestre esa seccion
function cambiarSeccion() {
  const botones = document.querySelectorAll(".tabs button");
  botones.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      //Obtener el selector que creamos->data-paso y asignarlo a la variable pagina
      pagina = parseInt(e.target.dataset.paso);
      //cambiar al btn que se le dio click
      //Que se muestre la seccion a la que le dimos click
      mostrarSeccion();
      botonesPaginador(); //Para mostrar o no mostrar los botones del paginador
      
    });
  });
}

function mostrarSeccion() {
  const seccionAnterior = document.querySelector(".mostrar");
  //El btn que tiene la clase de actual
  const btnActual = document.querySelector(".actual");
  const seccionMostrar = document.querySelector(`#paso-${pagina}`);
  //Ocultar la seccion que se estaba mostrando
  if (seccionAnterior) {
    seccionAnterior.classList.remove("mostrar");
  }
  //Mostrar la nueva sección
  seccionMostrar.classList.add("mostrar");
  if(btnActual){
  //Se la eliminamos
  btnActual.classList.remove("actual");
  }
  // selector de atributo ->[] para que podmas utilizar nuestro atributo personalizado
 const btn = document.querySelector(`[data-paso="${pagina}"]`);
  //Se la añadimos al btn que le dimos click
  btn.classList.add("actual");
}
function botonesPaginador() {
  const btnSiguiente = document.querySelector("#siguiente");
  const btnAnterior = document.querySelector("#anterior");

  if (pagina === inicio) {
    btnAnterior.classList.add("ocultar");
    btnSiguiente.classList.remove("ocultar");
  } else if (pagina === final) {
    //que se muestre el btn de anterior
    btnAnterior.classList.remove("ocultar");
    //ocultamos el de siguiente
    btnSiguiente.classList.add("ocultar");
  } else {
    //Mostrar ambos botones
    btnSiguiente.classList.remove("ocultar");
    btnAnterior.classList.remove("ocultar");
  }
  if(pagina === final){
    //pq cuando este aqui los datos de la citas e llenaron
    mostrarResumen();
  }
  mostrarSeccion();
}
function paginaSiguiente() {
  const siguiente = document.querySelector("#siguiente");
  siguiente.addEventListener("click", () => {
    if (pagina === final) {
      pagina = final;
    }else{
        pagina++;
    }

    botonesPaginador();
  });
}
function paginaAnterior() {
  const anterior = document.querySelector("#anterior");

  anterior.addEventListener("click", () => {
    if (pagina === inicio) {
      pagina= inicio;
    }
    else{
        pagina--;
    }
    botonesPaginador();
  });
}

async function consultarAPI(){
  try {
    const url = 'http://localhost:3000/api/servicios';
    const resultado = await fetch( url );
    const servicios = await resultado.json();

    //Mostrar lo traído de la API en html
    mostrarServicios(servicios);
  } catch (error) {
    console.log(error)
  }
}
function mostrarServicios(servicios){

  //Iterar sobre todo el arreglo
  servicios.forEach(servicio =>{
    const {id, nombre, precio} = servicio;
    const nombreServicio = document.createElement('P');
    const precioServicio = document.createElement('P')
    nombreServicio.textContent = nombre;
    nombreServicio.classList.add('nombre-servicio');
    precioServicio.textContent = `$${precio}`;
    precioServicio.classList.add('precio-servicio');

    //Contenedor que contenga cadad uno de estos servicios
    const serviciosContenedor = document.createElement('DIV');
    serviciosContenedor.classList.add('servicio');
    /*
    Asi con parebtesis es como si se llamara la función y ene ste caso se 
    ejecutara, no espera al click
    serviciosContenedor.onclick = seleccionarServicio();*/
    //Hacemos un callback para que se ejecute al dar click
    serviciosContenedor.onclick = () =>{
      seleccionarServicio(servicio);
    } 
    //Creamos un atributo personalizado
    serviciosContenedor.dataset.idServicio = id;
    //Añadimos el precio y nombre al contenedor
    serviciosContenedor.appendChild(nombreServicio);
    serviciosContenedor.appendChild(precioServicio);

    //Obtener el padre en donde se insertaran los contenedores de servicio
   document.querySelector('#servicios').appendChild(serviciosContenedor);

  });
}

function seleccionarServicio(servicio){
  const {id} = servicio;
  //Utilizar el arreglo del objeto
  const { servicios } = cita

  //Identificar al elemento qu se le dio click
  const servicioDiv = document.querySelector(`[data-id-servicio = "${id}"]`);
  //Comprobar si un servici ya fue agregado o eliminarlo
  //Iterar sobre el arreglo y retorn a true o false
  if( servicios.some( agregado => agregado.id === id ) ){
    //Se quiere eliminar ya que ya esxistia

    //Filtrar todos aquellos que sean diferentes a id
    cita.servicios = servicios.filter( serv => serv.id != id );
  } else{
    //Se quiere añadir
    cita.servicios = [...servicios, servicio];
  }
 
  //Marcar el servicio que se selecciono dado el dataset
  servicioDiv.classList.toggle('seleccionado');
  
}
function nombreCliente(){
   cita.nombre = document.querySelector('#nombre').value;
   cita.id = document.querySelector('#id').value;
}
function seleccionarFecha(){
  const inputFecha = document.querySelector('#fecha');
  inputFecha.addEventListener('input', (e)=>{
    const dia = new Date(e.target.value).getUTCDay();

    //Verificar si dia esta en el arreglo
    if([6, 0].includes(dia)){
      e.target.value = '';
      mostrarAlerta('Fines de semana no permitidos', 'error', '#paso-2 p');
    }else{
      cita.fecha = e.target.value;
    }
  })
}

function seleccionarHora(){
  const inputHora = document.querySelector('#hora');
  inputHora.addEventListener('input', (e)=>{
    const horaCita = e.target.value;
    //separamos el string de la hora
    const hora = parseInt(horaCita.split(":")[0]);
    if(hora < 10 || hora > 18){

      //Limpiamos el input para que el usuario note que no es valida
      e.target.value = '';
      //hora invalida
      mostrarAlerta('Hora no válida', 'error',  '#paso-2 p');
    }else{
      cita.hora = e.target.value
    }
  });
}

function mostrarResumen(){
  const resumen = document.querySelector('.contenido-resumen');

  //Limpiar resumen
  while(resumen.firstChild){
    resumen.removeChild(resumen.firstChild)
  }
   if(Object.values(cita).includes("")){
    //No hay nada
    mostrarAlerta('Faltan datos de la cita, fecha u hora', 'error',  '.contenido-resumen', false);
    return; 
  }else if(cita.servicios.length === 0){
    //No se eligio ningun servicio
    mostrarAlerta('No se seleciono un servicio', 'error',  '.contenido-resumen', false);
    return; 
  }

  const {nombre, fecha, hora, servicios} = cita
  //heading para servicios en resumen
  const headingServicios = document.createElement('H3');
  headingServicios.textContent = 'Resumen de servicios';
  resumen.appendChild(headingServicios);

  //iterando en los servicios y mostrandolos
  servicios.forEach(servicio=>{
    const {nombre, id , precio} = servicio;
    const contenedorServicio = document.createElement('DIV');
    contenedorServicio.classList.add('contenedor-servicio');

    const textoServicio = document.createElement('P');
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);
     resumen.appendChild(contenedorServicio);
  })

    //heading para cita en resumen
  const headingCita = document.createElement('H3');
  headingCita.textContent = 'Resumen de cita';
  resumen.appendChild(headingCita);
    //Formatear el div de resumen
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;
  
    //formatear la fecha
    const fechaFormateada = new Date(fecha);
    const opciones ={
        day:'numeric',
        year: 'numeric',
        month: 'long'
    }
    const nuevaFecha = fechaFormateada.toLocaleDateString('es-ES', opciones)
    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${nuevaFecha}`;
  
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;
  //Btotn para crear una cita
    const btnReservar = document.createElement('BUTTON');
    btnReservar.classList.add('btn');
    btnReservar.textContent = 'Reservar Cita'
    btnReservar.onclick = reservarCita 
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(btnReservar);
}

async function  reservarCita(){

  const {nombre, fecha, hora, id, servicios} = cita

  //para que nos retorne nada mas un arreglo conlas id
  const idServicios = servicios.map(servicio=>servicio.id);
  const datos = new FormData();
  datos.append('fecha', fecha);
  datos.append('hora', hora);
  datos.append('usuarioId', id);
  datos.append('servicios', idServicios);

  try {
    //peticion hacia la api
  const url = '/api/citas';
  const respuesta = await fetch(url,{
    method:'POST',
    body: datos
  });
  const resultado = await respuesta.json();
  console.log(resultado)
  if(resultado.resultado){
    Swal.fire({
      icon: 'success',
      title: 'Cita creada',
      text: 'Tu cita fue creada correctamente',
      button: 'OK'
    }).then(()=>{
      setTimeout(()=>{
        window.location.reload()
      }, 2500)
    })
  }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrio un error al guardar la cita'
    })
    console.log(error)
  }

  
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
  const  alertaPrevia = document.querySelector('.alerta');
  //si existe una alerta ya no continua
  if(alertaPrevia){
    console.log('hola')
     alertaPrevia.remove();
  }
  const alerta = document.createElement('DIV');
  alerta.textContent = mensaje;
  alerta.classList.add('alerta');
  alerta.classList.add(tipo); 
  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

if(desaparece){
  setTimeout(() => {
    alerta.remove();
    }, 2000);
}

}
