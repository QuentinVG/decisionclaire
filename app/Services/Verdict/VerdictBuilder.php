<?php

namespace App\Services\Verdict;

final class VerdictBuilder
{
    public const NOTICE = 'Estimation indicative, ne remplace pas un conseil financier professionnel.';

    public static function money(float|int $amount): string
    {
        return number_format((float) $amount, 0, ',', ' ').' €';
    }

    public static function decimalMoney(float|int $amount): string
    {
        return number_format((float) $amount, 2, ',', ' ').' €';
    }

    public static function percent(float|int $value): string
    {
        return number_format((float) $value, 0, ',', ' ').' %';
    }

    public static function clampScore(float|int $score): int
    {
        return (int) max(0, min(100, round((float) $score)));
    }

    /**
     * @param  array<int, array{label:string,value:string,help?:string}>  $metrics
     * @param  array<int, string>  $recommendations
     * @return array<string, mixed>
     */
    public static function result(
        string $toolKey,
        string $toolName,
        string $title,
        string $verdict,
        string $primaryLabel,
        string $primaryValue,
        string $riskLevel,
        int $confidenceScore,
        string $explanation,
        array $recommendations,
        string $summary,
        array $metrics = [],
    ): array {
        return [
            'tool_key' => $toolKey,
            'tool_name' => $toolName,
            'title' => $title,
            'verdict' => $verdict,
            'primary_label' => $primaryLabel,
            'primary_value' => $primaryValue,
            'risk_level' => $riskLevel,
            'confidence_score' => self::clampScore($confidenceScore),
            'explanation' => $explanation,
            'recommendations' => array_values($recommendations),
            'summary' => $summary,
            'metrics' => array_values($metrics),
            'notice' => self::NOTICE,
        ];
    }
}
