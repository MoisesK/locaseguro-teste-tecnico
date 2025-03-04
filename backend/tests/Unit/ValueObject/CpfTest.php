<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\Cpf;

class CpfTest extends TestCase
{
    public function testItShouldMarkInvalidWhenRepeatitiveDigitsProvided(): void
    {
        $cpf = new Cpf('111.111.111-11');
        $this->assertEquals('11111111111', $cpf->number);
        $this->assertFalse($cpf->isValid);
    }

    public function testItShouldMarkIsValidWhenValidDigitsProvided(): void
    {
        $cpf = new Cpf('012.345.678-90');
        $this->assertEquals('01234567890', $cpf->number);
        $this->assertTrue($cpf->isValid);
    }
}
