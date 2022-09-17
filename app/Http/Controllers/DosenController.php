<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table dosen
        $posts = Dosen::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Dosen',
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
        $post = Dosen::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Dosen',
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
            'nidn'       => 'required',
            'nama'       => 'required',
            'alamat'     => 'required',
            'telp'       => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = Dosen::create([
            'nidn'       => $request->nidn,
            'nama'       => $request->nama,
            'alamat'     => $request->alamat,
            'telp'       => $request->telp,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Dosen Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Dosen Failed to Save',
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
           'nidn'   =>'required',
           'nama'   =>'required',
           'alamat' =>'required',
           'telp'   =>'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= Dosen::where('kode', $kode)->update([
           'nidn'       =>$request->nidn,
           'nama'       =>$request->nama,
           'alamat'     =>$request->alamat,
           'telp'       =>$request->telp,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Dosen Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Dosen Failed to Update'
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
        $post = Dosen::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dosen Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Dosen Not Found',
        ], 404);
    }
}