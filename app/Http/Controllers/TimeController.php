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
    public function buscarTimePorId($id)
    {
        try {
            $time = Time::with([
                'campeonatos',
                'jogadores',
                'comentarios',
                'partidasMandante',
                'partidasVisitante'
            ])->find($id);

            if (!$time) {
                return response()->json(['message' => 'Time não encontrado.'], 404);
            }

            return response()->json($time, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro no servidor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

        public function buscarTimePorNome(Request $request)
        {
            try {
                
                $request->validate([
                    'nome' => 'required|string|min:1',
                ]);

                
                $time = Time::with([
                    'campeonatos',
                    'jogadores',
                    'comentarios',
                    'partidasMandante',
                    'partidasVisitante'
                ])->where('nome', 'LIKE', '%' . $request->nome . '%')->first();

                
                if (!$time) {
                    return response()->json(['message' => 'Time não encontrado.'], 404);
                }

                
                return response()->json($time, 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao buscar o time.', 'error' => $e->getMessage()], 500);
            }
        }

}
