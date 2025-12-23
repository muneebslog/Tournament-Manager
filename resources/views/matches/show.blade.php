<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Match Report - {{ $match->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background: white;
        }

        .container {
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        /* Summary Section */
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .team {
            display: table-cell;
            width: 48%;
            padding: 20px;
            border: 2px solid #000;
            vertical-align: top;
        }

        .team:first-child {
            margin-right: 4%;
            border-right: none;
        }

        .team h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }

        .team-info {
            margin-bottom: 12px;
        }

        .team-info label {
            font-size: 11px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
        }

        .team-info .value {
            font-size: 16px;
            margin-top: 4px;
            color: #000;
            font-weight: bold;
        }

        .team-info .large {
            font-size: 36px;
            color: #0066cc;
            margin: 8px 0;
        }

        /* Match Info Box */
        .match-info {
            background-color: #f0f0f0;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #000;
            page-break-inside: avoid;
        }

        .match-info h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-item {
            display: table-cell;
            width: 25%;
            padding-right: 15px;
        }

        .info-item label {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
        }

        .info-item .value {
            font-size: 13px;
            margin-top: 3px;
            color: #000;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        table thead {
            background-color: #000;
            color: #fff;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #000;
        }

        table td {
            padding: 10px 12px;
            border: 1px solid #ccc;
            font-size: 12px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Officials Section */
        .officials {
            background-color: #e3f2fd;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #0066cc;
            page-break-inside: avoid;
        }

        .officials h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }

        .officials-grid {
            display: table;
            width: 100%;
        }

        .official-item {
            display: table-cell;
            width: 33%;
            padding-right: 20px;
        }

        .official-item label {
            font-size: 11px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
        }

        .official-item .value {
            font-size: 13px;
            margin-top: 4px;
            color: #000;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #000;
            padding-top: 30px;
            margin-top: 40px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 0 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 8px;
            font-size: 11px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
        }

        .generated-time {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>BADMINTON MATCH REPORT</h1>
            <p>{{ $match->event->tournament->name ?? 'Tournament' }} {{ now()->format('Y') }}</p>
        </div>

        <!-- Match Summary -->
        <div class="summary">
            <div class="team">
                <h2>
                    @if($match->winner_team_id === $match->team1_id)
                        WINNER
                    @else
                        RUNNER-UP
                    @endif
                </h2>
                <div class="team-info">
                    <label>Player Name</label>
                    <div class="value">{{ $match->team1->name ?? 'Team 1' }}</div>
                </div>
                <div class="team-info">
                    <label>Player ID</label>
                    <div class="value">#{{ $match->team1_id }}</div>
                </div>
                <div class="team-info">
                    <label>Sets Won</label>
                    <div class="value ">{{ $match->team1_points }}</div>
                </div>
                <div class="team-info">
                    <label>Total Points</label>
                    <div class="value ">{{ $pointsTeam1 }}</div>
                </div>
            </div>

            <div class="team">
                <h2>
                    @if($match->winner_team_id === $match->team2_id)
                        ⭐ WINNER
                    @else
                        RUNNER-UP
                    @endif
                </h2>
                <div class="team-info">
                    <label>Player Name</label>
                    <div class="value">{{ $match->team2->name ?? 'Team 2' }}</div>
                </div>
                <div class="team-info">
                    <label>Player ID</label>
                    <div class="value">#{{ $match->team2_id }}</div>
                </div>
                <div class="team-info">
                    <label>Sets Won</label>
                    <div class="value ">{{ $match->team2_points }}</div>
                </div>
                <div class="team-info">
                    <label>Total Points</label>
                    <div class="value ">
                      
                        {{ $pointsTeam2 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Match Information -->
        <div class="match-info">
            <h3>Match Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Match Number</label>
                    <div class="value">{{ $match->name }}</div>
                </div>
                <div class="info-item">
                    <label>Match Type</label>
                    <div class="value">{{ $match->is_doubles ? 'Doubles' : 'Singles' }}</div>
                </div>
                <div class="info-item">
                    <label>Category</label>
                    <div class="value"> {{ $match->round->event->name }}</div>
                </div>
                <div class="info-item">
                    <label>Round</label>
                    <div class="value">{{ $match->round->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <label>Match Date</label>
                    <div class="value">{{ $match->created_at->format('M d, Y') }}</div>
                </div>
                <div class="info-item">
                    <label>Start Time</label>
                    <div class="value">{{ $match->start_time?->format('h:i A') ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <label>End Time</label>
                    <div class="value">{{ $match->end_time?->format('h:i A') ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <label>Duration</label>
                    <div class="value">
                        @if($match->start_time && $match->end_time)
                            {{ $match->end_time->diffInMinutes($match->start_time) }} mins
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Set Results Table -->
        <table>
            <thead>
                <tr>
                    <th>Set</th>
                    <th>{{ $match->team1->name ?? 'Team 1' }}</th>
                    <th>{{ $match->team2->name ?? 'Team 2' }}</th>
                    <th>Winner</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scores as $score)
                    <tr>
                        <td><strong>Set {{ $score->set_number }}</strong></td>
                        <td><strong>{{ $score->team1_score }}</strong></td>
                        <td><strong>{{ $score->team2_score }}</strong></td>
                        <td>
                            @if($score->team1_score > $score->team2_score)
                                {{ $match->team1->name ?? 'Team 1' }}
                            @else
                                {{ $match->team2->name ?? 'Team 2' }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No scores recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Officials -->
        <div class="officials">
            <h3>Match Officials</h3>
            <div class="officials-grid">
                <div class="official-item">
                    <label>Umpire</label>
                    <div class="value">{{ $match->empire_name ?? 'N/A' }}</div>
                </div>
                <div class="official-item">
                    <label>Service Judge</label>
                    <div class="value">{{ $match->service_judge_name ?? 'N/A' }}</div>
                </div>
                <div class="official-item">
                    <label>Shuttles Used</label>
                    <div class="value">{{ $match->shuttles_used ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="signature">
                <div class="signature-line">Umpire Signature</div>
            </div>
            <div class="signature">
                <div class="signature-line">Tournament Director</div>
            </div>
            <div class="signature">
                <div class="signature-line">Date</div>
            </div>
        </div>

        <div class="generated-time">
            Generated: {{ now()->format('M d, Y - h:i A') }}
        </div>
    </div>
</body>

</html>