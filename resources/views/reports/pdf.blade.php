<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ringkasan Rujukan eSIR</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4361ee; padding-bottom: 10px; }
        .header h2 { color: #4361ee; margin-bottom: 5px; }
        .stats-box { margin-bottom: 20px; }
        .stats-box table { width: 100%; border-collapse: collapse; }
        .stats-box td { padding: 10px; background: #f8f9fa; border: 1px solid #eee; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #eee; padding: 8px; text-align: left; }
        .table th { background-color: #4361ee; color: white; }
        .footer { margin-top: 30px; font-size: 10px; color: #777; text-align: right; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 10px; }
        .bg-success { background-color: #d1e7dd; color: #0f5132; }
        .bg-danger { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SISTEM INFORMASI RUJUKAN (eSIR 2.1)</h2>
        <p>LAPORAN RINGKASAN DATA RUJUKAN</p>
        <p style="font-size: 10px;">Periode: {{ $request->start_date }} s/d {{ $request->end_date }}</p>
    </div>

    <div class="stats-box">
        <table>
            <tr>
                <td><strong>Total Rujukan:</strong><br>{{ $stats['total'] }} Kasus</td>
                <td><strong>Selesai:</strong><br>{{ $stats['completed'] }} Kasus</td>
                <td><strong>Dibatalkan:</strong><br>{{ $stats['cancelled'] }} Kasus</td>
                <td><strong>Avg Response:</strong><br>{{ round($stats['avg_response'], 1) }} Menit</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pasien</th>
                <th>Dari</th>
                <th>Ke</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($referrals as $ref)
            <tr>
                <td>#{{ $ref->id }}</td>
                <td>{{ $ref->patient->name }}</td>
                <td>{{ $ref->fromFaskes->name }}</td>
                <td>{{ $ref->toFaskes->name }}</td>
                <td>{{ strtoupper($ref->status) }}</td>
                <td>{{ $ref->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d M Y H:i:s') }}<br>
        Oleh: {{ auth()->user()->name }} ({{ auth()->user()->role }})
    </div>
</body>
</html>
