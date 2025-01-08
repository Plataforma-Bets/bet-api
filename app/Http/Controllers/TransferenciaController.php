<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransferenciaController extends Controller
{
    /**
     * Salvar transferências enviadas pelo scraping.
     */
    public function salvarTransferencias(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'teamName' => 'required|string',
                'transfers' => 'required|array',
                'transfers.*.player' => 'required|string',
                'transfers.*.type' => 'required|in:contratado,emprestado,vendido',
            ]);
    
            $time = Time::firstOrCreate(['nome' => $validatedData['teamName']]);
    
            foreach ($validatedData['transfers'] as $transferData) {
                try {
                    Transferencia::create([
                        'time_id' => $time->id,
                        'jogador' => $transferData['player'],
                        'tipo' => $transferData['type'],
                    ]);
                    Log::info('Transferência salva com sucesso.', [
                        'time_id' => $time->id,
                        'jogador' => $transferData['player'],
                        'tipo' => $transferData['type'],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erro ao salvar transferência.', [
                        'time_id' => $time->id,
                        'jogador' => $transferData['player'],
                        'tipo' => $transferData['type'],
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
    
            return response()->json(['message' => 'Transferências processadas com sucesso!'], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao processar transferências.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro interno no servidor.'], 500);
        }
    }
}