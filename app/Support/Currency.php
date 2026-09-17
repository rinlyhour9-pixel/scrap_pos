<?php

namespace App\Support;

class Currency
{
    private const RIEL_PER_USD = 4100;

    public static function usesRiel(): bool
    {
        return app()->getLocale() === 'km';
    }

    public static function symbol(): string
    {
        return self::usesRiel() ? '៛' : '$';
    }

    public static function decimalPlaces(): int
    {
        return self::usesRiel() ? 0 : 2;
    }

    public static function display(float|int|string|null $amount): float
    {
        $amount = (float) ($amount ?? 0);

        return self::usesRiel() ? round($amount * self::RIEL_PER_USD) : $amount;
    }

    public static function input(float|int|string|null $amount): float
    {
        $amount = (float) ($amount ?? 0);

        return self::usesRiel() ? $amount / self::RIEL_PER_USD : $amount;
    }

    public static function rielToBase(float|int|string $amount): float
    {
        return (float) $amount / self::RIEL_PER_USD;
    }

    public static function format(float|int|string|null $amount): string
    {
        return self::symbol() . number_format(self::display($amount), self::decimalPlaces());
    }
}
