/*(function () {
    'use strict'*/

let datatable = null
feather.replace()

actualizarTablaArchivos()
subirArchivo()


//Metodo para crear y/o actualizar la tabla
function actualizarTablaArchivos() {

    if (datatable) {
        datatable.destroy()
    }
    //confirmo que exista listar archivos
    if (document.querySelector('#listaDeArchivos')) {
        datatable = new simpleDatatables.DataTable("#listaDeArchivos", {
            perPage: 5,
            //searchable: false,
            //fixedHeight: true
            ajax: {
                url: "/dashboard/json", // url to remote data
                content: {
                    headings: true,
                    lineDelimiter: "\n",
                    columnDelimiter: ","
                }
            }
        })

        let buscar = document.querySelector('#buscarArchivo')
        buscar.style.display = 'block'
        buscar.onkeyup = function () {
            datatable.search(this.value);
        }
    }
}


//Metodo para subir los archivos
function subirArchivo() {
    let iFiles = document.querySelector('#iSubirArchivo');
    //vinculo al boton subir archivo, el input file
    document.querySelector('#btnSubirArchivo').onclick = function () {
        iFiles.click();
    }

    //cuando se suba los archivos al input file, manda archivos al servidor
    iFiles.onchange = function () {
        if (iFiles.files.length > 0) {

            Swal.fire({
                title: 'Recuerde!',
                //  icon: 'info',
                html: 'Si ya ha subido un archivo con el mismo nombre, este será reemplazado.',
                showCancelButton: true,
                confirmButtonText: `Continuar y subir`,
                cancelButtonText: `Cancelar`,
            }).then(function (result) {
                if (result.isConfirmed) {
                    //Si desea subir, mando los archivos al servidor y muestro respuesta
                    subirArchivosServer(iFiles, function(){
                        return Swal.fire('Archivos subidos', '', 'success')
                    }, function (err){
                        return Swal.fire(err.message, '', 'error')
                    })
                } else {
                    iFiles.files.length = 0
                }
            })
        }
    }
}

//Sube el archivo al servidor y muestra porcentaje de barra
function subirArchivosServer(iFiles, success, error) {
    let data = new FormData();
    data.append('_token', document.querySelector('#iSubirArchivo_token').value);

    for (let i = 0; i < iFiles.files.length; i++) {
        data.append('files[]', iFiles.files[i]);
    }
    let progressFiles = document.querySelector('#progressFiles')
    progressFiles.style.display = 'block'
    moverProgressBar(0)

    axios.post('/dashboard/upload', data, {
            onUploadProgress: function (progressEvent) {
                let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                console.log(percentCompleted)
                moverProgressBar(percentCompleted)

            }
        })
        .then(function (res) {
            success(res).then(function (result) {
                progressFiles.style.display = 'none'
                actualizarTablaArchivos() //Actualizo la lista
                moverProgressBar(0)
            })
        })
        .catch(function (err) {
            error(err).then(function (result) {
                progressFiles.style.display = 'none'
                moverProgressBar(0)
            })
        })
}

//Mueve la barra de progreso al enviar el archivo al servidor
function moverProgressBar(valor){
    let progressBar = document.querySelector('#progressFiles .progress-bar')
    document.querySelector('#progressFiles .progress-bar').innerText
    progressBar.innerText = 'Subiendo en '+ valor + '%';
    progressBar.style.width = valor + '%';
}

//})()


