<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Operator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OperatorController extends Controller
{
    public function index()
    {
        //get data from table user
        // $operators = User::latest()->get();

        // //make response json
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Data Operator',
        //     'data' => $operators
        // ]);
        return User::when(request('search'), function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->latest()->paginate(3);
    }
    //SHOW
    public function show($id)
    {
        $operator = User::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Operator',
            'data' => $operator
        ]);
    }

    //Add Data
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
        $operator = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telp' => $request->telp,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        //success save to database
        if ($operator) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perusahaan Created',
                'data'    => $operator
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
            'password'  => 'required',
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $operator = User::findOrFail($id);
        if ($operator) {
            //update level akses
            $operator->update([
                'name' => $request->name,
                'email' => $request->email,
                'telp' => $request->telp,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Saved',
                'data' => $operator
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data User Operator'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $operator = User::findOrfail($id);

        if ($operator) {
            $operator->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Operator Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'Operator not found'
        ]);
    }
}
