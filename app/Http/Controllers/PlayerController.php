<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function Index(Request $request)
    {
        $players = DB::table('Player')
                        ->select('*')
                        ->get();

        $this->checkExists($players);

        foreach ($players as $player) {
            $player->Status = (bool)$player->Status;
        }

        return response()->json($players);
    }

    public function GetPlayer(Request $request, $PlayerId)
    {
        $player = DB::table('Player')
                        ->select('*')
                        ->where('Id', $PlayerId)
                        ->first();

        $this->checkExists($player);

        $player->Status = (bool)$player->Status;

        return response()->json($player);
    }

    public function InsertPlayer(Request $request)
    {
        try
        {
            DB::table('Player')->insert([
                'Id' => $request->Id,
                'CommunityId' => $request->CommunityId,
                'Name' => $request->Name,
                'Status' => $request->Status
            ]);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            return response()->json("Duplicate entry", 409);
        }    

        return response()->json($request, 201);
    }

    public function UpdatePlayer(Request $request, $PlayerId)
    {
        DB::table('Player')->where('Id', $PlayerId)->update([
            'Name' => $request->Name,
            'Status' => $request->Status
        ]);

        return response()->json('OK');
    }

    public function DeletePlayer(Request $request, $PlayerId)
    {
        DB::table('Player')->where('Id', $PlayerId)->delete();

        return response()->json('OK');
    }
}
