<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Time;

class TimeController extends Controller
{
    public function salvarLogos(Request $request)
    {
        $request->validate([
            'teamName' => 'required|string',
            'logoUrl' => 'required|url', 
            'league' => 'nullable|string',
        ]);

        try {
            $time = Time::firstOrCreate(
                ['nome' => $request->teamName],
                ['liga' => $request->league ?? 'Desconhecida']
            );

            $time->escudo = $request->logoUrl; 
            $time->save();

            return response()->json(['message' => 'Escudo salvo com sucesso!', 'time' => $time], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao salvar escudo.', 'error' => $e->getMessage()], 500);
        }
    }
    public function buscarTimes(Request $request)
    {
        $request->validate([
            'liga' => 'required|string', 
        ]);

        $times = Time::where('liga', 'LIKE', '%' . $request->liga . '%')->get();

        return response()->json($times, 200);
    }
    public function buscarTimePorId(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $timeId = $request->id;

        $time = Time::with([
            'estadio', 
            'campeonatos', 
            'jogadores', 
            'tecnico',
            'comentarios', 
            'partidasMandante', 
            'partidasVisitante' 
        ])->find($timeId);

        if (!$time) {
            return response()->json(['message' => 'Time não encontrado.'], 404);
        }

        return response()->json($time, 200);
    }
}