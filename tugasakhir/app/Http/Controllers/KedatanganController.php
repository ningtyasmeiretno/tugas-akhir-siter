<?php

namespace App\Http\Controllers;

use App\Models\JenisAngkutan;
use App\Models\Kedatangan;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KedatanganController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;
    public function index()
    {
        // $kedatangan = Report::with('get_kendaraan')->with('get_terminal')->get();

    return Report::when(request('search'), function($query) {
        $query->where('no_kendaraan', 'like', '%' . request('search') . '%');
        })
        ->select(
            "reports.*",
            "kendaraans.no_kendaraan as no_kendaraan",
            "terminals.nama_terminal as  nama_terminal",
            "perusahaans.nama_po as nama_po",
            "kendaraans.no_uji as no_uji",
            "kendaraans.no_kps as no_kps",
            "kendaraans.exp_uji as exp_uji",
            "kendaraans.exp_kps as exp_kps"
        )
    ->join("kendaraans", "reports.id_kendaraan", "=", "kendaraans.id")
    ->join("terminals", "reports.id_terminal", "=", "terminals.id")
    ->join("perusahaans", "kendaraans.id_perusahaan", "=", "perusahaans.id")
    ->join("users", "reports.id_operator", "=", "users.id")
    ->where("id_status", "=", 1)
    ->paginate(2);
        //make response json
        
    }
    //SHOW
    public function show()
    {
        return Report::when(request('search'), function($query) {
        $query->where('no_kendaraan', 'like', '%' . request('search') . '%');
        })->select(
            "reports.*",
            "kendaraans.no_kendaraan as no_kendaraan",
            "terminals.nama_terminal as  nama_terminal",
            "perusahaans.nama_po as nama_po",
            "kendaraans.no_uji as no_uji",
            "kendaraans.no_kps as no_kps",
            "kendaraans.exp_uji as exp_uji",
            "kendaraans.exp_kps as exp_kps",
        )
        ->join("kendaraans", "reports.id_kendaraan", "=", "kendaraans.id")
        ->join("terminals", "reports.id_terminal", "=", "terminals.id")
        ->join("perusahaans", "kendaraans.id_perusahaan", "=", "perusahaans.id")
        ->join("users", "reports.id_operator", "=", "users.id")
        ->where("id_status", "=", 1)
        ->where('id_operator', auth()->guard('user-api')->user()->id)
        ->paginate(2);
        //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Detail Data',
        //     'data'    => $kedatangan
        // ], 200);
    }


    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_status' => 'required',
            'id_kendaraan' => 'required',
            'trayek' => 'required',
            'id_terminal' => 'required',
            'tgl' => 'required',
            'jam' => 'required',
            'id_operator' => 'required'
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $kedatangan = Report::create([
            'id_status' => $request -> id_status,
            'id_kendaraan' => $request->id_kendaraan,
            'trayek' => $request->trayek,
            'id_terminal' => $request->id_terminal,
            'tgl' => $request->tgl,
            'jam' => $request->jam,
            'id_operator' => $request->id_operator,
        ]);

        //success save to database
        if ($kedatangan) {
            return response()->json([
                'success' => true,
                'message' => 'Data kedatangan Created',
                'data'    => $kedatangan
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Kedatangan Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            // 'id_status' => 'required',
            'id_kendaraan' => 'required',
            'trayek' => 'required',
            'id_terminal' => 'required',
            'tgl' => 'required',
            'jam' => 'required',
            // 'id_operator' => 'required'
        ]);
        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $kedatangan = Report::findOrFail($id);
        if ($kedatangan) {
            //update level akses
            $kedatangan->update([
                // 'id_status' => $request->id_status,
                'id_kendaraan' => $request->id_kendaraan,
                'trayek' => $request->trayek,
                'id_terminal' => $request->id_terminal,
                'tgl' => $request->tgl,
                'jam' => $request->jam,
                // 'id_operator' => $request->id_operator,
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Updated',
                'data' => $kedatangan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Kedatangan'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $kedatangan = Report::findOrfail($id);

        if ($kedatangan) {
            $kedatangan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Kedatangan Deleted'
            ]);
        }

        //data termnal not found
        return response()->json([
            'success' => false,
            'message' => 'Kedatangan not found'
        ]);
    }
}
