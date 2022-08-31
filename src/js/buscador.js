document.addEventListener('DOMContentLoaded', ()=>{
    buscarPorFecha();
   
});
function buscarPorFecha(){
        const inputFecha = document.querySelector('#fecha');
        inputFecha.addEventListener('change', (e)=>{
            const nuevaFecha = e.target.value;

            window.location = `/admin?fecha=${nuevaFecha}`
        })
}