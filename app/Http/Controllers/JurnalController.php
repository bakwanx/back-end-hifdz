<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;

class JurnalController extends Controller
{
    public function getJurnal($idUser){
        $result = Jurnal::where(['id_user'=>$idUser])->get();
        
        return response()->json($result);
    }
}
