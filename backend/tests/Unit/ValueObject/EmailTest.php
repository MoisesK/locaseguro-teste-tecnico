<?php

namespace Tests\Unit;

use App\Shared\Domain\ValueObject\Cpf;
use App\Shared\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testItShouldReturnNullWhenInvalidEmailProvided(): void
    {
        $email = new Email('email.com');
        $this->assertEmpty($email->value);
    }

    public function testItShouldReturnValueWhenValidEmailProvided(): void
    {
        $email = new Email('teste@email.com');
        $this->assertNotNull($email->value);
        $this->assertEquals('teste@email.com', $email->value);

    }
}
