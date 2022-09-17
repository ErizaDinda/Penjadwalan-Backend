<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jam;
use Illuminate\Support\Facades\Validator;

class JamController extends Controller
{
     /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table dosen
        $posts = Jam::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Jam',
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
        //find post by ID
        $post = Jam::findOrfail($kode);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Jam',
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
            'range_jam'  => 'required',
            'aktif'      => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = Jam::create([
            'range_jam'  => $request->range_jam,
            'aktif'      => $request->aktif,
        ]);

        //success save to database
        if($post) {

            return response()->json([
                'success' => true,
                'message' => 'Jam Created',
                'data'    => $post  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Jam Failed to Save',
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
            'range_jam' =>'required',
            'aktif'     =>'required',
       ]);

       //response error validation
       if($validator->fails()){
           return response()->json($validator->errors()->toJson());
       }
       $post= Jam::where('kode', $kode)->update([
            'range_jam' =>$request->range_jam,
            'aktif'     =>$request->aktif,
       ]);
       if($post){
           return response()->json([
               'status'  =>true, 
               'message' =>'Jam Updated',
            //    'data'    => $post 
           ], 200);
       } else {
           return response()->json([
               'status'  =>false, 
               'message' =>'Jam Failed to Update'
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
        //find post by ID
        $post = Jam::findOrfail($kode);

        if($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jam Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Jam Not Found',
        ], 404);
    }
}
