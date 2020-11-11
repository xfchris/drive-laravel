/*(function () {
    'use strict'*/
var token =  document.querySelector('#i_token').value

document.querySelector('#btnActualizarCuenta').onclick = function(){
    actualizarCuenta()
}


function actualizarCuenta(){
    //recojo datos de entrada
    axios.post('/cuenta', {
        'nombres':document.querySelector('#iNombres').value,
        'plan':document.querySelector('#iPlan').value,
        '_token':token
    })
        .then(function (res){
            console.log(res)
            //si actualizó plan, avisar que se actualizó
            Swal.fire("Datos actualizados", '', 'success')
        })
        .catch(function (err){
            console.log(err.response.data.msg)
            Swal.fire(err.response.data.msg, '', 'error')
        })
}


//})()
