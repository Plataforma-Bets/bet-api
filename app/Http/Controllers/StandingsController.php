<?php

namespace App\Http\Controllers;

use App\Models\Classificacoes;
use App\Models\Campeonato;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StandingsController extends Controller
{
    public function salvarStandings(Request $request)
    {
        Log::info('Requisição recebida para salvar standings', $request->all());

        try {
            $validated = $request->validate([
                'league' => 'required|string',
                'standings' => 'required|array',
                'standings.*.team' => 'required|string',
                'standings.*.position' => 'required|numeric',
                'standings.*.played' => 'required|numeric',
                'standings.*.wins' => 'required|numeric',
                'standings.*.draws' => 'required|numeric',
                'standings.*.losses' => 'required|numeric',
                'standings.*.goalsFor' => 'numeric',
                'standings.*.goalsAgainst' => 'numeric',
                'standings.*.points' => 'required|numeric',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro de validação: ' . $e->getMessage());
            return response()->json(['message' => 'Erro de validação', 'error' => $e->getMessage()], 400);
        }

        Log::info('Buscando campeonato: ' . $validated['league']);
        $campeonato = Campeonato::where('nome', $validated['league'])->first();

        if (!$campeonato) {
            Log::error('Campeonato não encontrado: ' . $validated['league']);
            return response()->json(['message' => 'Campeonato não encontrado'], 404);
        }

        foreach ($validated['standings'] as $data) {
            Log::info('Buscando time: ' . $data['team']);
            $time = Time::where('nome', $data['team'])->first();

            if (!$time) {
                Log::error('Time não encontrado: ' . $data['team']);
                return response()->json(['message' => "Time {$data['team']} não encontrado"], 404);
            }

            Log::info('Salvando ou atualizando a classificação para o time: ' . $data['team'], $data);

            try {
                Classificacoes::updateOrCreate(
                    [
                        'campeonato_id' => $campeonato->id,
                        'time_id' => $time->id
                    ],
                    [
                        'posicao' => $data['position'],
                        'jogos' => $data['played'],
                        'vitorias' => $data['wins'],
                        'empates' => $data['draws'],
                        'derrotas' => $data['losses'],
                        'pontos' => $data['points']
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Erro ao salvar ou atualizar a classificação para o time: ' . $data['team'], ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Erro ao salvar os dados', 'error' => $e->getMessage()], 500);
            }
        }

        Log::info('Standings salvos com sucesso');
        return response()->json(['message' => 'Standings salvos com sucesso']);
    }

    public function listarClassificacoes($campeonatoId)
    {
        $campeonato = Campeonato::find($campeonatoId);

        if (!$campeonato) {
            return response()->json(['message' => 'Campeonato não encontrado'], 404);
        }

        $classificacoes = Classificacoes::where('campeonato_id', $campeonatoId)
                                        ->with('time')
                                        ->get();

        Log::info('Listando classificações', $classificacoes->toArray());

        return response()->json($classificacoes);
    }
}