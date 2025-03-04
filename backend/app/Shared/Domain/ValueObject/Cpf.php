<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObjectBase;

/**
 * @property bool $isValid
 * @property string $number
 */
class Cpf extends ValueObjectBase
{
    protected bool $isValid = false;
    protected string $number = '';

    public function __construct(string $number)
    {
        $this->number = preg_replace('/\D/', '', $number);
        $this->isValid = $this->validate();
    }

    private function validate(): bool
    {
        if (strlen($this->number) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $this->number)) {
            return false;
        }
        
        return $this->validateCheckDigits();
    }

    private function validateCheckDigits(): bool
    {
        $digits = str_split($this->number);

        $sum1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum1 += (int) $digits[$i] * (10 - $i);
        }
        $digit1 = ($sum1 * 10) % 11;
        $digit1 = $digit1 === 10 ? 0 : $digit1;

        $sum2 = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum2 += (int) $digits[$i] * (11 - $i);
        }
        $sum2 += $digit1 * 2;
        $digit2 = ($sum2 * 10) % 11;
        $digit2 = $digit2 === 10 ? 0 : $digit2;

        return $digit1 === (int) $digits[9] && $digit2 === (int) $digits[10];
    }

    public function value(): string|int|float|bool
    {
        return $this->number;
    }
}
