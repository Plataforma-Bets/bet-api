<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use App\Models\Time;
use App\Models\Campeonato;

class PartidaController extends Controller
{
    //Função do Web-Scraping para salvar as partidas de um campeonato
    public function salvarPartidas(Request $request)
    {
        set_time_limit(120);
        
        \Log::info('Requisição recebida:', ['dados' => $request->all()]);

        try {
            $data = $request->all();

            $campeonato = Campeonato::firstOrCreate(
                ['nome' => $data['league']], 
                ['temporada' => '2024/2025'] 
            );

            foreach ($data['matches'] as $match) {
                $year = ($data['type'] === 'results') ? 2000 : 3000;
                $dateParts = explode('.', $match['date']);
                $day = trim($dateParts[0]);
                $month = trim($dateParts[1]);
                
                $formattedDate = "$year-$month-$day"; 
                
                $homeTeam = Time::firstOrCreate(
                    ['nome' => $match['homeTeam']],
                    ['liga' => $data['league']] 
                );
                $awayTeam = Time::firstOrCreate(
                    ['nome' => $match['awayTeam']],
                    ['liga' => $data['league']]
                );

                $existingMatch = Partida::where('data', $formattedDate)
                    ->where('time_mandante_id', $homeTeam->id)
                    ->where('time_visitante_id', $awayTeam->id)
                    ->first();

                $isResult = $match['scoreHome'] !== '-' && $match['scoreAway'] !== '-';

                if ($existingMatch) {
                    $existingMatch->update([
                        'placar_mandante' => $match['scoreHome'],
                        'placar_visitante' => $match['scoreAway'],
                        'tipo' => $isResult ? 'results' : 'fixtures',
                    ]);
                } else {
                    Partida::create([
                        'data' => $formattedDate,
                        'time_mandante_id' => $homeTeam->id,
                        'time_visitante_id' => $awayTeam->id,
                        'placar_mandante' => $match['scoreHome'],
                        'placar_visitante' => $match['scoreAway'],
                        'tipo' => $isResult ? 'results' : 'fixtures', 
                        'campeonato_id' => $campeonato->id
                    ]);
                }
            }

            \Log::info('Todas as partidas foram processadas com sucesso.');

            return response()->json(['message' => 'Partidas salvas ou atualizadas com sucesso!'], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar partidas:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao salvar partidas.'], 500);
        }
    }

    public function buscarPartidas(Request $request)
    {
        $request->validate([
            'id_campeonato' => 'required|integer',  
            'tipo' => 'nullable|string'
        ]);
    
        $campeonato = Campeonato::find($request->id_campeonato);
    
        if (!$campeonato) {
            return response()->json(['message' => 'Campeonato não encontrado para o ID especificado.'], 404);
        }
    
        $query = Partida::where('campeonato_id', $campeonato->id);
    
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
    
        $partidas = $query->with([
            'timeMandante:id,nome,escudo',
            'timeVisitante:id,nome,escudo',
            'campeonato:id,nome'
        ])->get();
    
        return response()->json($partidas, 200);
    }
    public function listarPartidasPorTime(Request $request)
    {
        try {
            $request->validate([
                'idTime' => 'required|integer',
                'tipo' => 'nullable|string',
            ]);

            $timeId = $request->query('idTime');
            $tipo = $request->query('tipo');

            $time = Time::find($timeId);

            if (!$time) {
                return response()->json(['message' => 'Time não encontrado.'], 404);
            }

            $query = Partida::where('time_mandante_id', $timeId)
                ->orWhere('time_visitante_id', $timeId);

            if ($tipo) {
                $query->where('tipo', $tipo); 
            }

            $partidas = $query->orderBy('data', 'asc')
                ->with([
                    'timeMandante:id,nome,escudo',
                    'timeVisitante:id,nome,escudo',
                    'campeonato:id,nome'
                ])
                ->get();

            return response()->json([
                'time' => $time->nome,
                'partidas' => $partidas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro no servidor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}