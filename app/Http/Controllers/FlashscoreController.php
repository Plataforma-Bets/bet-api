<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class FlashscoreController extends Controller
{
    public function scrapeFlashscore()
    {
        $scriptPath = base_path('scripts/scrape-flashscore.js');

        $process = new Process(['node', $scriptPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(['error' => 'Erro ao executar o scraping.'], 500);
        }

        $output = $process->getOutput();
        $data = json_decode($output, true);

        return response()->json($data);
    }
}