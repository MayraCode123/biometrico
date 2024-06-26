<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Data;
use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Cargo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Event\Test\MarkedIncompleteSubscriber;

class PersonalController extends Controller
{
    public function index(){
        $data = DB::table('data')->distinct()->get(['id_biometrico', 'name']);
        $area = DB::table('area')->get();
        $unidad = DB::table('unidad')->get();
        $cargo = DB::table('cargo')->get();

        return view('personas.index', compact('area', 'unidad', 'data', 'cargo'));

    }
    public function lista_personal_area(Request $request){
        $area_id = $request->input('category');

        $personal = DB::table('personal')
            ->select('personal.id as id', 'personal.name as nombre_personal', 'data.name as nombre_usuario', 'area.name as area_nombre', 'unidad.name as unidad_nombre')
            ->join('area', 'area.id', '=', 'personal.area_id')
            ->join('unidad', 'unidad.id', '=', 'personal.unidad_id')
            ->join('data_personal', 'data_personal.data_id_biometrico', '=', 'personal.data_personal_id')
            ->join('data', 'data.id_biometrico', '=', 'data_personal.data_id_biometrico')
            ->where('area.id', $area_id)
            ->distinct('personal.id')
            ->get();

        return response()->json(['data' => $personal]);
    }

    // public function index(){
    //     $personal = DB::table('personal')
    //     ->select('personal.id', 'personal.name as name', 'data.name as data','area.name as area_name','unidad.name as unidad_name')
    //     ->join(DB::raw('(SELECT DISTINCT id_biometrico, name FROM data) as data'), 'personal.data_id', '=', 'data.id_biometrico')
    //     ->join('area','area.id','=','personal.area_id')
    //     ->join('unidad','unidad.id','=','personal.unidad_id')
    //     ->get();
    //     $data = DB::table('data')->distinct()->get(['id_biometrico', 'name']);
    //     // dd($data);
    //     $area = DB::table('area')->get();
    //     $unidad = DB::table('unidad')->get();


    public function store(Request $request){

            $personal = new Personal();
            $personal->name = $request->name;
            $personal->data_personal_id = $request->data_personal_id;
            $personal->unidad_id = $request->unidad_id;
            $personal->area_id = $request->area_id;
            $personal->cargo_id = $request->cargo_id;
            $personal->save();
            return redirect()->route('personal');
    }
    public function lista($id){
        $persona = DB::table('personal')
        ->join('cargo','cargo.id','=','personal.cargo_id')
        ->select('personal.id as id','cargo.name as name_cargo','personal.name as name_personal')
        ->where('personal.id', $id)
        ->first();

        $registros = DB::table('personal')
        ->join('data_personal', 'personal.data_personal_id', '=', 'data_personal.data_id_biometrico')
        ->join('data', 'data_personal.data_id_biometrico', '=', 'data.id_biometrico')
        ->join('data_horario', 'data.id_biometrico', '=', 'data_horario.data_id_biometrico')
        ->join('horario', 'horario.id', '=', 'data_horario.horario_id')
        ->select(
            DB::raw('DATE(data.time) as fecha'),
            'data.name as nombre_usuario',
            DB::raw('GROUP_CONCAT(DISTINCT CONCAT(TIME(data.time), " - ", data.state) ORDER BY TIME(data.time) SEPARATOR ", ") as estados')
        )
        ->where('personal.id', $id)
        ->groupBy(DB::raw('DATE(data.time)'), 'data.name')
        ->get();


        return view('personas.lista', [
            'persona' => $persona,
            'registros' => $registros,


        ]);

    }

    public function area_personal(Request $request){
        $data = DB::table('personal')
        ->join('area','area.id','=','personal.area_id')
        ->get();
        return response()->json($data);

    }
    public function direccion_personal($id){
        $persona = DB::table('personal')
        ->join('cargo','cargo.id','=','personal.cargo_id')
        ->select('personal.id as id','cargo.name as name_cargo','personal.name as name_personal')
        ->where('personal.id', $id)
        ->first();

        $registros = DB::table('personal')
        ->join('data_personal', 'personal.data_personal_id', '=', 'data_personal.data_id_biometrico')
        ->join('data', 'data_personal.data_id_biometrico', '=', 'data.id_biometrico')
        ->join('data_horario', 'data.id_biometrico', '=', 'data_horario.data_id_biometrico')
        ->join('horario', 'horario.id', '=', 'data_horario.horario_id')
        ->select(
            DB::raw('DATE(data.time) as fecha'),
            'data.name as nombre_usuario',
            DB::raw('GROUP_CONCAT(DISTINCT CONCAT(TIME(data.time), " - ", data.state) ORDER BY TIME(data.time) SEPARATOR ", ") as estados')
        )
        ->where('personal.id', $id)
        ->groupBy(DB::raw('DATE(data.time)'), 'data.name')
        ->get();
        // return response()->json($registros);

        return view('area_personal.direccion', [
            'persona' => $persona,
            'registros' => $registros,
        ]);


    }
    public function marketing_personal($id){
        $persona = DB::table('personal')
        ->join('cargo','cargo.id','=','personal.cargo_id')
        ->select('personal.id as id','cargo.name as name_cargo','personal.name as name_personal')
        ->where('personal.id', $id)
        ->first();
        return view('area_personal.marketing',compact('persona'));
    }
    public function academicos_personal($id){
        $persona = DB::table('personal')
        ->join('cargo','cargo.id','=','personal.cargo_id')
        ->select('personal.id as id','cargo.name as name_cargo','personal.name as name_personal')
        ->where('personal.id', $id)
        ->first();
        return view('area_personal.academicos',compact('persona'));
    }
    public function ti_personal($id){
        $persona = DB::table('personal')
        ->join('cargo','cargo.id','=','personal.cargo_id')
        ->select('personal.id as id','cargo.name as name_cargo','personal.name as name_personal')
        ->where('personal.id', $id)
        ->first();

        $registros = DB::table('personal')
        ->join('data_personal', 'personal.data_personal_id', '=', 'data_personal.data_id_biometrico')
        ->join('data', 'data_personal.data_id_biometrico', '=', 'data.id_biometrico')
        ->join('data_horario', 'data.id_biometrico', '=', 'data_horario.data_id_biometrico')
        ->join('horario', 'horario.id', '=', 'data_horario.horario_id')
        ->select(
            DB::raw('DATE(data.time) as fecha'),
            'data.name as nombre_usuario',
            DB::raw('GROUP_CONCAT(DISTINCT CONCAT(TIME(data.time), " - ", data.state) ORDER BY TIME(data.time) SEPARATOR ", ") as estados')
        )
        ->where('personal.id', $id)
        ->groupBy(DB::raw('DATE(data.time)'), 'data.name')
        ->get();

        return view('area_personal.ti', [
            'persona' => $persona,
            'registros' => $registros,
        ]);


    }

}
