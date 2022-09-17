<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hari;
use Illuminate\Support\Facades\Validator;

class HariController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table dosen
        $posts = Hari::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Hari',
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
        $post = Hari::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Hari',
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
            'nama_hari'       => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = Hari::create([
            'nama_hari'       => $request->nama_hari,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Hari Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Hari Failed to Save',
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
           'nama_hari'   =>'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= Hari::where('kode', $kode)->update([
           'nama_hari'       =>$request->nama_hari,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Hari Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Hari Failed to Update'
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
        $post = Hari::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hari Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Hari Not Found',
        ], 404);
    }
}