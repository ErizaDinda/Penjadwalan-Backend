<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matkul;
use Illuminate\Support\Facades\Validator;

class MatkulController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table dosen
        $posts = Matkul::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Matakuliah',
            'data'    => $posts  
        ], 200);

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
        $post = Matkul::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Matakuliah',
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
            'kode_mk'        => 'required',
            'nama_mk'        => 'required',
            'sks'            => 'required',
            'semester'       => 'required',
            'aktif'          => 'required',
            'jenis'          => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = Matkul::create([
            'kode_mk'      => $request->kode_mk,
            'nama_mk'      => $request->nama_mk,
            'sks'          => $request->sks,
            'semester'     => $request->semester,
            'aktif'        => $request->aktif,
            'jenis'        => $request->jenis,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Matakuliah Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Matakuliah Failed to Save',
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
            'kode_mk'        =>'required',
            'nama_mk'        =>'required',
            'sks'            =>'required',
            'semester'       =>'required',
            'aktif'          =>'required',
            'jenis'          =>'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= Matkul::where('kode', $kode)->update([
            'kode_mk'      => $request->kode_mk,
            'nama_mk'      => $request->nama_mk,
            'sks'          => $request->sks,
            'semester'     => $request->semester,
            'aktif'        => $request->aktif,
            'jenis'        => $request->jenis,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Matakuliah Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Matakuliah Failed to Update'
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
        $post = Matkul::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Matakuliah Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Matakuliah Not Found',
        ], 404);
    }
}