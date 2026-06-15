<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internal Test Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4361ee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #1e3a5f;
        }
        .summary-box {
            background-color: #f8faff;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
        }
        .summary-table td {
            padding: 5px;
        }
        .val-pass { color: #06d6a0; font-weight: bold; }
        .val-fail { color: #ef476f; font-weight: bold; }
        .val-dur { color: #4361ee; font-weight: bold; }
        
        .log-section {
            background-color: #1e1e1e;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
        }
        .log-pass { color: #06d6a0; }
        .log-fail { color: #ef476f; }
        .log-warn { color: #ffd166; }
        .log-info { color: #4895ef; }
        .log-dim { color: #a0a0a0; }
        .log-header { color: #a5b4fc; font-weight: bold; }
        .log-sep { color: #555; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>eSIR 2.1 - Internal Test Report</h2>
        <p>Tanggal Pengujian: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="summary-box">
        <h3>Ringkasan Eksekusi</h3>
        <table class="summary-table">
            <tr>
                <td width="25%"><strong>Total Skenario:</strong></td>
                <td width="25%">5</td>
                <td width="25%"><strong>Status Akhir:</strong></td>
                <td width="25%">
                    @if($stats['fail'] == 0)
                        <span style="color: #06d6a0; font-weight: bold;">ALL PASSED</span>
                    @else
                        <span style="color: #ef476f; font-weight: bold;">FAILED</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Passed:</strong></td>
                <td class="val-pass">{{ $stats['pass'] }}</td>
                <td><strong>Failed:</strong></td>
                <td class="val-fail">{{ $stats['fail'] }}</td>
            </tr>
            <tr>
                <td><strong>Total Waktu:</strong></td>
                <td class="val-dur">{{ $stats['duration'] }} ms</td>
                <td><strong>Diuji Oleh:</strong></td>
                <td>{{ auth()->user()->name ?? 'System' }}</td>
            </tr>
        </table>
    </div>

    <h3>Detail Output Pengujian (Terminal Logs)</h3>
    <div class="log-section">
        @foreach($logs as $log)
            @php
                $class = 'log-dim';
                if (str_starts_with($log, '✅') || str_contains($log, 'PASS')) $class = 'log-pass';
                elseif (str_starts_with($log, '❌') || str_contains($log, 'FAIL') || str_contains($log, 'ERROR')) $class = 'log-fail';
                elseif (str_starts_with($log, '⚠️')) $class = 'log-warn';
                elseif (str_starts_with($log, '▶') || str_contains($log, 'dimulai')) $class = 'log-header';
                elseif (str_starts_with($log, '─')) $class = 'log-sep';
                elseif (str_contains($log, 'Selesai')) $class = 'log-info';
            @endphp
            <div class="{{ $class }}">{{ $log }}</div>
        @endforeach
    </div>

    <div class="footer">
        Dicetak otomatis oleh eSIR 2.1 System ({{ request()->ip() }})
    </div>
</body>
</html>
