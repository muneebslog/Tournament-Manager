<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Barryvdh\DomPDF\Facade\Pdf;

class MatchController extends Controller
{
    public function show($id)
    {
        $match = Game::with(['team1', 'team2', 'scores', 'gameevents', 'round', 'event', 'winnerTeam'])
            ->findOrFail($id);

        $pointsTeam1 = 0;
        $pointsTeam2 = 0;
        foreach ($match->scores as $score) {
            $pointsTeam1 += $score->team1_score;
            $pointsTeam2 += $score->team2_score;
        }

        return view('matches.show', [
            'match' => $match,
            'scores' => $match->scores,
            'events' => $match->gameevents()->orderBy('created_at')->get(),
            'pointsTeam1' => $pointsTeam1,
            'pointsTeam2' => $pointsTeam2,
        ]);
    }

    public function report($id)
    {
        $match = Game::with(['team1', 'team2', 'scores', 'gameevents', 'round', 'event', 'winnerTeam'])
            ->findOrFail($id);
        // dd($match->scores);
        $pointsTeam1 = 0;
        $pointsTeam2 = 0;
        foreach ($match->scores as $score) {
            $pointsTeam1 += $score->team1_score;
            $pointsTeam2 += $score->team2_score;
        }

        $data = [
            'match' => $match,
            'scores' => $match->scores,
            'events' => $match->gameevents()->orderBy('created_at')->get(),
            'pointsTeam1' => $pointsTeam1,
            'pointsTeam2' => $pointsTeam2,
        ];

        $pdf = Pdf::loadView('matches.report-pdf', $data);

        return $pdf->download('Match-Report-' . $match->name . '.pdf');
    }
}
