/*(function () {
    'use strict'*/

    let datatable = null
    feather.replace()

    crearDatatable()

    function crearDatatable(){

        if (datatable){
            datatable.destroy()
        }
        //confirmo que exista listar archivos
        if (document.querySelector('#listaDeArchivos')){
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
            buscar.onkeyup = function (){
                datatable.search(this.value);
            }
        }

    }
//})()


