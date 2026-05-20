<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Simulation DécisionClaire</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 13px; line-height: 1.5; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        h2 { font-size: 16px; margin-top: 22px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; }
        .badge { display: inline-block; padding: 4px 8px; border: 1px solid #a7f3d0; background: #ecfdf5; color: #065f46; border-radius: 4px; }
        .metric { border: 1px solid #cbd5e1; padding: 8px; margin-bottom: 6px; }
        .muted { color: #475569; }
        pre { white-space: pre-wrap; border: 1px solid #cbd5e1; padding: 10px; background: #f8fafc; }
    </style>
</head>
<body>
    <p class="badge">DécisionClaire</p>
    <h1>{{ $result['tool_name'] ?? 'Simulation' }} - {{ $simulation->title }}</h1>
    <p class="muted">Date : {{ $simulation->created_at->format('d/m/Y H:i') }}</p>

    <h2>Résultat</h2>
    <p><strong>Verdict :</strong> {{ $result['verdict'] }}</p>
    <p><strong>{{ $result['primary_label'] }} :</strong> {{ $result['primary_value'] }}</p>
    <p><strong>Risque :</strong> {{ $result['risk_level'] }}</p>
    <p><strong>Confiance :</strong> {{ $result['confidence_score'] }}/100</p>
    <p>{{ $result['explanation'] }}</p>

    <h2>Données principales</h2>
    @foreach (($result['metrics'] ?? []) as $metric)
        <div class="metric"><strong>{{ $metric['label'] }} :</strong> {{ $metric['value'] }}</div>
    @endforeach

    <h2>Recommandations</h2>
    <ul>
        @foreach (($result['recommendations'] ?? []) as $recommendation)
            <li>{{ $recommendation }}</li>
        @endforeach
    </ul>

    <h2>Résumé</h2>
    <pre>{{ $result['summary'] }}</pre>
    <p class="muted">{{ $result['notice'] ?? 'Estimation indicative, ne remplace pas un conseil financier professionnel.' }}</p>
</body>
</html>
