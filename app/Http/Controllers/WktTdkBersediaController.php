<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WktTdkBersedia;
use Illuminate\Support\Facades\Validator;

class WktTdkBersediaController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table dosen
        // $posts = WktTdkBersedia::latest()->get();

        // //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Data Waktu Tidak Bersedia',
        //     'data'    => $posts  
        // ], 200);

        $posts=WktTdkBersedia::join('dosen','dosen.kode','=', 'wkt_tdk_bersedia.kode_dosen')
                             ->join('hari','hari.kode','=', 'wkt_tdk_bersedia.kode_hari')
                             ->join('jam','jam.kode','=', 'wkt_tdk_bersedia.kode_jam')
                             ->get();
                       
        return Response()->json([
            'success' => true,
            'message' => 'List Data Waktu Tidak Bersedia',
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
        $post = WktTdkBersedia::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Waktu Tidak Bersedia',
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
            'kode_dosen'  => 'required',
            'kode_hari'   => 'required',
            'kode_jam'    => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = WktTdkBersedia::create([
            'kode_dosen'  => $request->kode_dosen,
            'kode_hari'   => $request->kode_hari,
            'kode_jam'    => $request->kode_jam,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Waktu Tidak Bersedia Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Waktu Tidak Bersedia Failed to Save',
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
            'kode_dosen'  => 'required',
            'kode_hari'   => 'required',
            'kode_jam'    => 'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= WktTdkBersedia::where('kode', $kode)->update([
            'kode_dosen'  => $request->kode_dosen,
            'kode_hari'   => $request->kode_hari,
            'kode_jam'    => $request->kode_jam,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Waktu Tidak Bersedia Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Waktu Tidak Bersedia Failed to Update'
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
        $post = WktTdkBersedia::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Waktu Tidak Bersedia Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Waktu Tidak Bersedia Not Found',
        ], 404);
    }
}