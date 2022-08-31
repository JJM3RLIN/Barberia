document.addEventListener("DOMContentLoaded",()=>{eliminarAlertas(),cambiarSeccion(),mostrarSeccion(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),consultarAPI(),nombreCliente(),seleccionarFecha(),seleccionarHora(),mostrarResumen()});let pagina=1;const inicio=1,final=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function eliminarAlertas(){document.querySelectorAll(".alerta").forEach(e=>{const t=e.parentElement;setTimeout(()=>{t.removeChild(e)},3500)})}function cambiarSeccion(){document.querySelectorAll(".tabs button").forEach(e=>{e.addEventListener("click",e=>{pagina=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()})})}function mostrarSeccion(){const e=document.querySelector(".mostrar"),t=document.querySelector(".actual"),a=document.querySelector("#paso-"+pagina);e&&e.classList.remove("mostrar"),a.classList.add("mostrar"),t&&t.classList.remove("actual");document.querySelector(`[data-paso="${pagina}"]`).classList.add("actual")}function botonesPaginador(){const e=document.querySelector("#siguiente"),t=document.querySelector("#anterior");1===pagina?(t.classList.add("ocultar"),e.classList.remove("ocultar")):3===pagina?(t.classList.remove("ocultar"),e.classList.add("ocultar")):(e.classList.remove("ocultar"),t.classList.remove("ocultar")),3===pagina&&mostrarResumen(),mostrarSeccion()}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",()=>{3===pagina?pagina=3:pagina++,botonesPaginador()})}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",()=>{1===pagina?pagina=1:pagina--,botonesPaginador()})}async function consultarAPI(){try{const e="http://localhost:3000/api/servicios",t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach(e=>{const{id:t,nombre:a,precio:o}=e,n=document.createElement("P"),r=document.createElement("P");n.textContent=a,n.classList.add("nombre-servicio"),r.textContent="$"+o,r.classList.add("precio-servicio");const c=document.createElement("DIV");c.classList.add("servicio"),c.onclick=()=>{seleccionarServicio(e)},c.dataset.idServicio=t,c.appendChild(n),c.appendChild(r),document.querySelector("#servicios").appendChild(c)})}function seleccionarServicio(e){const{id:t}=e,{servicios:a}=cita,o=document.querySelector(`[data-id-servicio = "${t}"]`);a.some(e=>e.id===t)?cita.servicios=a.filter(e=>e.id!=t):cita.servicios=[...a,e],o.classList.toggle("seleccionado")}function nombreCliente(){cita.nombre=document.querySelector("#nombre").value,cita.id=document.querySelector("#id").value}function seleccionarFecha(){document.querySelector("#fecha").addEventListener("input",e=>{const t=new Date(e.target.value).getUTCDay();[6,0].includes(t)?(e.target.value="",mostrarAlerta("Fines de semana no permitidos","error","#paso-2 p")):cita.fecha=e.target.value})}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",e=>{const t=e.target.value,a=parseInt(t.split(":")[0]);a<10||a>18?(e.target.value="",mostrarAlerta("Hora no válida","error","#paso-2 p")):cita.hora=e.target.value})}function mostrarResumen(){const e=document.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes(""))return void mostrarAlerta("Faltan datos de la cita, fecha u hora","error",".contenido-resumen",!1);if(0===cita.servicios.length)return void mostrarAlerta("No se seleciono un servicio","error",".contenido-resumen",!1);const{nombre:t,fecha:a,hora:o,servicios:n}=cita,r=document.createElement("H3");r.textContent="Resumen de servicios",e.appendChild(r),n.forEach(t=>{const{nombre:a,id:o,precio:n}=t,r=document.createElement("DIV");r.classList.add("contenedor-servicio");const c=document.createElement("P");c.textContent=a;const i=document.createElement("P");i.innerHTML="<span>Precio:</span> $"+n,r.appendChild(c),r.appendChild(i),e.appendChild(r)});const c=document.createElement("H3");c.textContent="Resumen de cita",e.appendChild(c);const i=document.createElement("P");i.innerHTML="<span>Nombre:</span> "+t;const s=new Date(a).toLocaleDateString("es-ES",{day:"numeric",year:"numeric",month:"long"}),l=document.createElement("P");l.innerHTML="<span>Fecha:</span> "+s;const d=document.createElement("P");d.innerHTML=`<span>Hora:</span> ${o} horas`;const u=document.createElement("BUTTON");u.classList.add("btn"),u.textContent="Reservar Cita",u.onclick=reservarCita,e.appendChild(i),e.appendChild(l),e.appendChild(d),e.appendChild(u)}async function reservarCita(){const{nombre:e,fecha:t,hora:a,id:o,servicios:n}=cita,r=n.map(e=>e.id),c=new FormData;c.append("fecha",t),c.append("hora",a),c.append("usuarioId",o),c.append("servicios",r);try{const e="/api/citas",t=await fetch(e,{method:"POST",body:c}),a=await t.json();console.log(a),a.resultado&&Swal.fire({icon:"success",title:"Cita creada",text:"Tu cita fue creada correctamente",button:"OK"}).then(()=>{setTimeout(()=>{window.location.reload()},2500)})}catch(e){Swal.fire({icon:"error",title:"Error",text:"Ocurrio un error al guardar la cita"}),console.log(e)}}function mostrarAlerta(e,t,a,o=!0){const n=document.querySelector(".alerta");n&&(console.log("hola"),n.remove());const r=document.createElement("DIV");r.textContent=e,r.classList.add("alerta"),r.classList.add(t);document.querySelector(a).appendChild(r),o&&setTimeout(()=>{r.remove()},2e3)}