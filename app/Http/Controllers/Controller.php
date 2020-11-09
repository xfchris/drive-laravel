<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Pagina principal y landing page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        return view('index');
    }

    /**
     * Panel de administracion
     * @return string
     */
    public function getDashboard(){
        return view('dashboard.index');
    }

    public function getFilesJson(){
        return '[
            {
                "Name": "Unity Pugh",
                "Ext.": "9958",
                "City": "Curicó",
                "Start Date": "2005/02/11",
                "Completion": "37%"
            },
            {
                "Name": "Theodore Duran",
                "Ext.": "8971",
                "City": "Dhanbad",
                "Start Date": "1999/04/07",
                "Completion": "97%"
            },
            {
                "Name": "Theodore Duran",
                "Ext.": "8971",
                "City": "Dhanbad",
                "Start Date": "1999/04/07",
                "Completion": "97%"
            },
            {
                "Name": "Theodore Duran",
                "Ext.": "8971",
                "City": "Dhanbad",
                "Start Date": "1999/04/07",
                "Completion": "97%"
            }
        ]';
    }

    public function postUploadFiles(Request $request){
        var_dump($request->all());
    }
}
