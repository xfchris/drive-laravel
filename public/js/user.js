(function () {
    'use strict'
    var token = document.querySelector('#i_token').value


    let btnACuenta = document.querySelector('#btnActualizarCuenta')
    btnACuenta && (btnACuenta.onclick = function () {
        actualizarCuenta()
    })

    let btnAPlan = document.querySelector('#btnActualizarPlan')
    btnAPlan && (btnAPlan.onclick = function () {
        actualizarPlan()
    })


//Metodo que actualiza el usuario de la cuenta
    function actualizarCuenta() {
        axios.post('/cuenta', {
            'nombres': document.querySelector('#iNombres').value,
            '_token': token
        })
            .then(function (res) {
                Swal.fire("Datos actualizados", '', 'success')
            })
            .catch(function (err) {
                Swal.fire(err.response.data.msg, '', 'error')
            })
    }


//Metodo que actualiza el plan de la cuenta
    function actualizarPlan() {
        //recojo datos de entrada
        axios.post('/cuenta/planes', {
            'plan': document.querySelector('#iPlan').value,
            '_token': token
        })
            .then(function (res) {
                console.log(res)
                //si actualizó plan, avisar que se actualizó
                actualizarTextosPlan(res.data.tiempo)
                Swal.fire(res.data.msg, '', 'success')
            })
            .catch(function (err) {
                Swal.fire(err.response.data.msg, '', 'error')
            })
    }

//Aumenta el tiempo de los planes
    function actualizarTextosPlan(txt) {
        document.querySelectorAll('.tiempoPlan').forEach(function (i) {
            i.innerText = txt
        })
        let txtPV = document.querySelector('.txtPlanVigente')
        txtPV && (txtPV.style.display = 'none')
    }


})()
