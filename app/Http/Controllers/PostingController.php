<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posting;
use Illuminate\Support\Facades\Storage;
class PostingController extends Controller
{

    public function postAcara(Request $request){
        
        $output = [
            "status"=>"",
            "message"=>""
        ];
        
        $userId = $request->user_id;
        $title = $request->title;
        $desc = $request->description;
        $created_at = \Carbon\Carbon::now();  
        $imageName = "";

        if($request->hasFile('image')){
            $resorce = $request->file('image');
            $imageName   = $resorce->getClientOriginalName();
            $resorce->move(\base_path() ."/storage/app/public/", $imageName);
        }

        $posting = new Posting();
        $posting->id_user = $userId;
        $posting->title = $title;
        $posting->description = $desc;
        $posting->image = $imageName;
        $posting->created_at = $created_at;
        $save = $posting->save();

        if ($save) {
            $output['status'] = '1';
            $output['message'] = 'Berhasil Posting';
        }else{
            $output['status'] = '0';
            $output['message'] = 'Gagal Posting';
        }

        return response()->json($output);
    }

    public function getAcara($idUser){
        $data = Posting::where(['id_user' => $idUser])->get();

        return response()->json($data);
    }

    public function deleteAcara($idPosting){
       
        $imageName = Posting::firstWhere(['id' => $idPosting]);
        
        $imagePath = storage_path().'/app/public/'.$imageName->image;
        unlink($imagePath);
        
        $data = Posting::where(['id' => $idPosting])->delete();
        $output = [
            'status' => '',
            'message' => ''
        ];
        if ($data) {
            $output['status'] = '1';
            $output['message'] = 'Acara Dihapus';
        }else{
            $output['status'] = '0';
            $output['message'] = 'Gagal Menghapus Acara';
        }
        
        return response()->json($output);
    }
}
