<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadkul;
use Illuminate\Support\Facades\Validator;

class JadkulController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        //get data from table jadkul
        // $posts = Jadkul::latest()->get();

        // //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Data Jadwal Kuliah',
        //     'data'    => $posts  
        // ], 200);

        $posts=Jadkul::join('pengampu','pengampu.kode','=', 'jadkul.kode_pengampu')
                       ->join('jam','jam.kode','=', 'jadkul.kode_jam')
                       ->join('hari','hari.kode','=', 'jadkul.kode_hari')
                       ->join('ruang','ruang.kode','=', 'jadkul.kode_ruang')
                       ->get();
                       
        return Response()->json([
            'success' => true,
            'message' => 'List Data Jadwal Kuliah',
            'data'=>$posts
        ]);

    }
    
     /**
     * show
     *
     * @param  mixed $kode
     * @return void
     */
    public function show($kode)
    {
        //find post by kode
        $post = Jadkul::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Jadwal Kuliah',
            'data'    => $post 
        ], 200);

    }
    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'kode_pengampu' => 'required',
            'kode_jam'      => 'required',
            'kode_hari'     => 'required',
            'kode_ruang'    => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = Jadkul::create([
            'kode_pengampu'     => $request->kode_pengampu,
            'kode_jam'          => $request->kode_jam,
            'kode_hari'         => $request->kode_hari,
            'kode_ruang'        => $request->kode_ruang,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Jadwal Kuliah Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Jadwal Kuliah Failed to Save',
        ], 409);

    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $kode)
   {
       //set validation
       $validator = Validator::make($request->all(),[
            'kode_pengampu' => 'required',
            'kode_jam'      => 'required',
            'kode_hari'     => 'required',
            'kode_ruang'    => 'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= Jadkul::where('kode', $kode)->update([
            'kode_pengampu'     => $request->kode_pengampu,
            'kode_jam'          => $request->kode_jam,
            'kode_hari'         => $request->kode_hari,
            'kode_ruang'        => $request->kode_ruang,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Jadwal Kuliah Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Jadwal Kuliah Failed to Update'
           ], 404);
       }
   }
    
    /**
     * destroy
     *
     * @param  mixed $kode
     * @return void
     */
    public function destroy($kode)
    {
        //find post by kode
        $post = Jadkul::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal Kuliah Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Jadwal Kuliah Not Found',
        ], 404);
    }
}