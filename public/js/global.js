(function () {
    'use strict'

let datatable = null
let dataArchivos = []
var token = document.querySelector('#i_token').value
feather.replace()

actualizarTablaArchivos()
subirArchivo()

//Evento global para descargar archivo
addEvent(document, 'click', '.btnDescargar', function (e) {
    let key = dataArchivos[this.dataset.row]
    location.href = '/dashboard/descargar/' + key
});


//Evento global para botones de eliminar
addEvent(document, 'click', '.btnEliminarArchivo', function (e) {
    let key = this.dataset.row

    //Muestro mensaje de que si esta seguro de eliminar
    Swal.fire({
        title: 'Estas seguro de eliminar archivo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Eliminar`,
        cancelButtonText: `Cancelar`,
    }).then(function (result) {
        if (result.isConfirmed) {

            //mando peticion para eliminar archivo
            axios.post('/dashboard/eliminar', {
                'id': dataArchivos[key],
                '_token': token
            })
                .then(function (res) {
                    Swal.fire("Archivo eliminado", '', 'success')
                    actualizarTablaArchivos()
                })
                .catch(function (err) {
                    Swal.fire("No se pudo eliminar el archivo", '', 'error')
                })
        }
    })
})

//Metodo para crear y/o actualizar la tabla
function actualizarTablaArchivos() {

    if (datatable) {
        datatable.destroy()
    }
    //confirmo que exista listar archivos
    if (document.querySelector('#listaDeArchivos')) {
        datatable = new simpleDatatables.DataTable("#listaDeArchivos", {
            columns: [
                // Sort the second column in ascending order
                {select: 0, sort: "desc"},

                // Append a button to the seventh column
                {
                    select: 5,
                    render: function (data, cell, row) {
                        if (!isNaN(Number(data))) {
                            dataArchivos[row.dataIndex] = data
                        }
                        return "<div class='d-flex'>" +
                            "<button class='btnDescargar btn btn-xs btn-outline-success' " +
                            "type='button' data-row='" + row.dataIndex + "'>" +
                            'Descargar' +
                            "</button>" +
                            "<button class='btnEliminarArchivo btn btn-xs btn-outline-danger' " +
                            "type='button' data-row='" + row.dataIndex + "'>" +
                            '<b>X</b>' +
                            "</button>" +
                            "</div>";
                    }
                }
            ],
            perPage: 8,
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
    let iFiles = document.querySelector('#iSubirArchivo')
    let btnSArchivo = document.querySelector('#btnSubirArchivo')

    if (!btnSArchivo) {
        return;
    }

    //vinculo al boton subir archivo, el input file
    btnSArchivo.onclick = function () {
        iFiles.click();
    }
    let tamanoMax = iFiles.getAttribute('data-max');

    //cuando se suba los archivos al input file, manda archivos al servidor
    iFiles.onchange = function () {
        if (iFiles.files.length > 0) {

            //valido el peso de los archivos
            if (!validacionesArchivos(iFiles.files, tamanoMax)) {
                Swal.fire('La suma de los archivos exceden el limite de peso permitido', '', 'error')
            }else{

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
                        subirArchivosServer(iFiles,
                            function (res) {
                                return Swal.fire(res.data.msg, '', 'success')
                            },
                            function (err) {
                                let msg = err.response.data.msg
                                return Swal.fire(msg || 'La suma de los archivos exceden el limite de peso permitido', '', 'error')
                            })
                    } else {
                        iFiles.files.length = 0
                    }
                })
            }

        }
    }
}

//Funcion que suma los archivos del input y si supera el tamaño permitido, retorna error
function validacionesArchivos(iFiles, tamanoMax){
    //valido tamaño de archivos
    let size = 0
    for(let i=0; i<iFiles.length; i++){
        size += iFiles[i].size;
    }
    if (size > tamanoMax ){
        return false;
    }
    return true;
}

//Sube el archivo al servidor y muestra porcentaje de barra
function subirArchivosServer(iFiles, success, error) {
    let data = new FormData();
    data.append('_token', token);

    for (let i = 0; i < iFiles.files.length; i++) {
        data.append('files[]', iFiles.files[i]);
    }
    let progressFiles = document.querySelector('#progressFiles')
    progressFiles.style.display = 'block'
    moverProgressBar(0)

    axios.post('/dashboard/upload', data, {
        onUploadProgress: function (progressEvent) {
            let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
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
function moverProgressBar(valor) {
    let progressBar = document.querySelector('#progressFiles .progress-bar')
    document.querySelector('#progressFiles .progress-bar').innerText
    progressBar.innerText = 'Subiendo en ' + valor + '%';
    progressBar.style.width = valor + '%';
}

//Funcion para poner eventos a nivel global
function addEvent(parent, evt, selector, handler) {
    parent.addEventListener(evt, function (event) {
        if (event.target.matches(selector + ', ' + selector + ' *')) {
            handler.apply(event.target.closest(selector), arguments);
        }
    }, false);
}


})()


