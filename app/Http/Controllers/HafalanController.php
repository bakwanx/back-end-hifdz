<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murojaah;
use App\Models\Ziyadah;
use App\Models\Jurnal;

class HafalanController extends Controller
{

    public function getLastRequestMurojaah($targetIdUser){
        
        $result = Murojaah::where(['id_penguji' => $targetIdUser])->orderBy('created_at', 'desc')->first();
        $output = [
            "status"=>"",
            "id_user"=>"",
            "tipe" => ""
        ];
        if ($result != null) {
            if ($result->status == 0) {
                $output['status'] = "1";
                $output['id_user'] = $result->id_user;
                $output['tipe'] = "murojaah";
            }
          
        }else{
            $output['status'] = "0";
            $output['id_user'] = "";
        }
        return response()->json($output);
    }

    public function getLastRequestZiyadah($targetIdUser){
        
        $result = Ziyadah::where(['id_penguji' => $targetIdUser])->orderBy('created_at', 'desc')->first();
        $output = [
            "status"=>"",
            "id_user"=>"",
            "tipe" => ""
        ];
        if ($result != null) {
            if ($result->status == 0) {
                $output['status'] = "1";
                $output['id_user'] = $result->id_user;
                $output['tipe'] = "ziyadah";
            }
          
        }else{
            $output['status'] = "0";
            $output['id_user'] = "";
        }
        return response()->json($output);

    }

    public function postRequestMurojaah(Request $request){

        $output = [
            'status' =>"",
            'message' => ""
        ];

        $idUser = $request->id_user;
        $idPenguji = $request->id_penguji;
        $juz = $request->juz;
        $surat = $request->surat;
        $ayat = $request->ayat;
        $targetJuz = $request->target_juz;
        $targetSurat = $request->target_surat;
        $targetAyat = $request->target_ayat;
        $status = 0;

        $check = Murojaah::firstWhere(['id_user' => $idUser, 'id_penguji'=>$idPenguji]);
        if ($check != null) {
            $data = [
                'id_user' => $idUser,
                'id_penguji' => $idPenguji,
                'juz' => $juz,
                'surat' => $surat,
                'ayat' => $ayat,
                'target_juz' => $targetJuz,
                'target_surat' => $targetSurat,
                'target_ayat' => $targetAyat,
                'status' => $status,
                'created_at' => \Carbon\Carbon::now(),
            ];
            $update = Murojaah::where(['id_user' => $idUser, 'id_penguji' => $idPenguji])
                                ->update($data);
            if ($update) {
                $output['status'] = "1";
                $output['message'] = "Berhasil melakukan permintaan hafalan";
            }else{
                $output['status'] = "0";
                $output['message'] = "Gagal melakukan permintaan hafalan";
            }
            return response()->json($output);
        }

        $murojaah = new Murojaah();
        $murojaah->id_user = $idUser;
        $murojaah->id_penguji = $idPenguji;
        $murojaah->juz = $juz;
        $murojaah->surat = $surat;
        $murojaah->ayat = $ayat;
        $murojaah->target_juz = $targetJuz;
        $murojaah->target_surat = $targetSurat;
        $murojaah->target_ayat = $targetAyat;
        $murojaah->status = $status;
        $murojaah->created_at = \Carbon\Carbon::now();
        $save = $murojaah->save();

        if ($save) {
            $output['status'] = "1";
            $output['message'] = "Berhasil melakukan permintaan hafalan";
        }else{
            $output['status'] = "0";
            $output['message'] = "Gagal melakukan permintaan hafalan";
        }
        return response()->json($output);
    }

    public function postRequestZiyadah(Request $request){

        $output = [
            'status' =>"",
            'message' => ""
        ];

        $idUser = $request->id_user;
        $idPenguji = $request->id_penguji;
        $juz = $request->juz;
        $surat = $request->surat;
        $ayat = $request->ayat;
        $targetJuz = $request->target_juz;
        $targetSurat = $request->target_surat;
        $targetAyat = $request->target_ayat;
        $status = 0;

        $check = Ziyadah::firstWhere(['id_user' => $idUser, 'id_penguji' => $idPenguji]);
        if ($check != null) {
            $data = [
                'id_user' => $idUser,
                'id_penguji' => $idPenguji,
                'juz' => $juz,
                'surat' => $surat,
                'ayat' => $ayat,
                'target_juz' => $targetJuz,
                'target_surat' => $targetSurat,
                'target_ayat' => $targetAyat,
                'status' => $status,
                'created_at' => \Carbon\Carbon::now(),
            ];
            $update = Ziyadah::where(['id_user' => $idUser, 'id_penguji' => $idPenguji])
                                ->update($data);
            if ($update) {
                $output['status'] = "1";
                $output['message'] = "Berhasil melakukan permintaan hafalan";
            }else{
                $output['status'] = "0";
                $output['message'] = "Gagal melakukan permintaan hafalan";
            }
            return response()->json($output);
        }

        $ziyadah = new Ziyadah();
        $ziyadah->id_user = $idUser;
        $ziyadah->id_penguji = $idPenguji;
        $ziyadah->juz = $juz;
        $ziyadah->surat = $surat;
        $ziyadah->ayat = $ayat;
        $ziyadah->target_juz = $targetJuz;
        $ziyadah->target_surat = $targetSurat;
        $ziyadah->target_ayat = $targetAyat;
        $ziyadah->status = $status;
        $ziyadah->created_at = \Carbon\Carbon::now();
        $save = $ziyadah->save();

        if ($save) {
            $output['status'] = "1";
            $output['message'] = "Berhasil melakukan permintaan hafalan";
        }else{
            $output['status'] = "0";
            $output['message'] = "Gagal melakukan permintaan hafalan";
        }
        return response()->json($output);
    }

    public function postToJurnal(Request $request){

        $output = [
            "status" => "",
            "message" => ""
        ];

        $juz = "";
        $surat = "";
        $ayat = "";
        $targetJuz = "";
        $targetSurat = "";
        $targetAyat = "";

        $idUser = $request->id_user;
        $idPenguji = $request->id_penguji;
        $nilai = $request->nilai;
        $tipe = $request->tipe;
        $catatan = $request->catatan;

        if ($tipe == "murojaah") {
            $dataMurojaah = Murojaah::firstWhere(['id_user' => $idUser, 'id_penguji' => $idPenguji]);
            $juz = $dataMurojaah->juz;
            $surat = $dataMurojaah->surat;
            $ayat = $dataMurojaah->ayat;
            $targetJuz = $dataMurojaah->target_juz;
            $targetSurat = $dataMurojaah->target_surat;
            $targetAyat = $dataMurojaah->target_ayat;
            //delete data murojaah
           
        }else{
            $dataZiyadah = Ziyadah::firstWhere(['id_user' => $idUser, 'id_penguji' => $idPenguji]);
            $juz = $dataZiyadah->juz;
            $surat = $dataZiyadah->surat;
            $ayat = $dataZiyadah->ayat;
            $targetJuz = $dataZiyadah->target_juz;
            $targetSurat = $dataZiyadah->target_surat;
            $targetAyat = $dataZiyadah->target_ayat;
            //delete data ziyadah
           
        }

        $jurnal = new Jurnal();
        $jurnal->id_user = $idUser;
        $jurnal->id_penguji = $idPenguji;
        $jurnal->juz = $juz;
        $jurnal->surat = $surat;
        $jurnal->ayat = $ayat;
        $jurnal->target_juz = $targetJuz;
        $jurnal->target_surat = $targetSurat;
        $jurnal->target_ayat = $targetAyat;
        $jurnal->nilai = $nilai;
        $jurnal->tipe = $tipe;
        $jurnal->catatan = $catatan;
        $jurnal->created_at = \Carbon\Carbon::now();
        $save = $jurnal->save();

        if ($save) {
            $output['status'] = "1";
            $output['message'] = "Berhasil Menilai";
        }else{
            $output['status'] = "0";
            $output['message'] = "Gagal Menilai";
        }

        if ($tipe == "murojaah") {
            $delete = Murojaah::where(['id_user' => $idUser, 'id_penguji' => $idPenguji])->delete();
        }else{
            $delete = Ziyadah::where(['id_user' => $idUser, 'id_penguji' => $idPenguji])->delete();
        }
      

        return response()->json($output);
    }
}
