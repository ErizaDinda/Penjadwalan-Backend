<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengampu;
use Illuminate\Support\Facades\Validator;

class PengampuController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // //get data from table pengampu
        $posts = Pengampu::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Pengampu',
            'data'    => $posts  
        ], 200);

        // $posts=Pengampu::join('matkul','matkul.kode','=', 'pengampu.kode_mk')
        //                ->join('dosen','dosen.kode','=', 'pengampu.kode_dosen')
        //                ->get();
                       
        // return Response()->json([
        //     'success' => true,
        //     'message' => 'List Data Pengampu',
        //     'data'=>$posts
        // ]);


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
        $post = Pengampu::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Pengampu',
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
            'kode_mk'             => 'required',
            'kode_dosen'          => 'required',
            'kelas'             => 'required',
            'tahun_akademik'    => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = Pengampu::create([
            'kode_mk'             => $request->kode_mk,
            'kode_dosen'          => $request->kode_dosen,
            'kelas'             => $request->kelas,
            'tahun_akademik'    => $request->tahun_akademik,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Pengampu Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Pengampu Failed to Save',
        ], 409);

    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $id)
   {
       //set validation
       $validator = Validator::make($request->all(),[
            'kode_mk'             => 'required',
            'kode_dosen'          => 'required',
            'kelas'             => 'required',
            'tahun_akademik'    => 'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= Pengampu::where('kode', $kode)->update([
            'kode_mk'             => $request->kode_mk,
            'kode_dosen'          => $request->kode_dosen,
            'kelas'             => $request->kelas,
            'tahun_akademik'    => $request->tahun_akademik,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Pengampu Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Pengampu Failed to Update'
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
        $post = Pengampu::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengampu Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Pengampu Not Found',
        ], 404);
    }
}