<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pimpinan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PimpinanController extends Controller
{
    public function index()
    {
        // //get data from table user
        // $pimpinans = Pimpinan::latest()->get();

        // //make response json
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Data Dinas',
        //     'data' => $pimpinans
        // ]);
        return Pimpinan::when(request('search'), function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->paginate(3);
    }
    //SHOW
    public function show($id)
    {
        $pimpinan = Pimpinan::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Dinas',
            'data' => $pimpinan
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'telp'      => 'required',
            'username'  => 'required',
            'password'  => 'required',
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $pimpinan = Pimpinan::create([
            'name' => $request->name,
            'email' => $request->email,
            'telp' => $request->telp,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        //success save to database
        if ($pimpinan) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perusahaan Created',
                'data'    => $pimpinan
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Perusahaan Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'telp'      => 'required',
            'username'  => 'required',
            'password'  => '',
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $pimpinan = Pimpinan::findOrFail($id);
        if ($pimpinan) {
            //update level akses
            $pimpinan->update([
                'name' => $request->name,
                'email' => $request->email,
                'telp' => $request->telp,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Saved',
                'data' => $pimpinan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Dinas'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $pimpinan = Pimpinan::findOrfail($id);

        if ($pimpinan) {
            $pimpinan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Pimpinan Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'User Dinas not found'
        ]);
    }
}

