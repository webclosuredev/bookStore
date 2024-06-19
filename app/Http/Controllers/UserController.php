<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;

class UserController extends Controller
{

    public function all()
    {

        $records = User::all();

        return response()->json([
            'data' => $records
        ], 200);
    }

    public function get($id)
    {
        $record = User::where('id', $id)->get();

        return response()->json([
            'data' => $record
        ], 200);
    }


    public function update(Request $request)
    {
        $record = User::find($request->get("id"));
        $body = $request->all();
        if (empty($record)) {
            return "No record found with id: " . $id;
        }
        
        $record->fill($body);
        $record->save();
        return $record; 
    }

    public function create(Request $request)
    {
        
        $record = new User();
        $record->fill($request->all());
        $record->save();
        return $record; 
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['message' => 'Row deleted successfully']);
    }
    
}
