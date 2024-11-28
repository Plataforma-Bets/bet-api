<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campeonato;

class CampeonatosController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarTodasLigas()
    {
        $ligas = Campeonato::all(); // Recupera todas as ligas
        return response()->json(['ligas' => $ligas], 200);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarLigasPorPais(Request $request)
    {
        $request->validate([
            'pais' => 'required|string'
        ]);

        $pais = $request->input('pais');

        $ligas = Campeonato::where('pais', 'LIKE', '%' . $pais . '%')->get();

        return response()->json(['ligas' => $ligas], 200);
    }
}