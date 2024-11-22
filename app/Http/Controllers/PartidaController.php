<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;
use App\Models\Time;
use App\Models\Campeonato;

class PartidaController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Dados recebidos na requisição:', $request->all());

        try {
            $data = $request->all();

            $campeonato = Campeonato::firstOrCreate(
                ['nome' => $data['league']], 
                ['temporada' => '2024/2025'] 
            );

            foreach ($data['matches'] as $match) {
                // \Log::info('Processando partida:', $match);
                
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

                // \Log::info('Time mandante ID:', ['id' => $homeTeam->id]);
                // \Log::info('Time visitante ID:', ['id' => $awayTeam->id]);

                Partida::create([
                    'data' => $formattedDate,
                    'time_mandante_id' => $homeTeam->id,
                    'time_visitante_id' => $awayTeam->id,
                    'placar_mandante' => $match['scoreHome'],
                    'placar_visitante' => $match['scoreAway'],
                    'tipo' => $data['type'], 
                    'campeonato_id' => $campeonato->id
                ]);
            }

            \Log::info('Todas as partidas foram processadas com sucesso.');

            return response()->json(['message' => 'Partidas salvas com sucesso!'], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar partidas:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao salvar partidas.'], 500);
        }
    }
}