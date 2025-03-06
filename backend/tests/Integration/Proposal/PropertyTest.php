<?php

declare(strict_types=1);

namespace Tests\Integration\Proposal;

use App\Property\Domain\Contracts\PropertyRepository;
use App\Property\Domain\Entity\Owner;
use App\Property\Domain\Entity\Property;
use App\Property\UseCase\RegisterProperty\InputData;
use App\Property\UseCase\RegisterProperty\RegisterProperty;
use App\Shared\Infra\Models\Property as PropertyModel;
use DateTime;
use PHPUnit\Framework\TestCase;
use App\Shared\Infra\Exceptions\ValidationException;

class PropertyTest extends TestCase
{
    public function testItShouldRegisterPropertyWhenValidDataProvided(): void
    {
        /**
         * @var  \App\Property\Domain\Contracts\PropertyRepository $propertyRepo
         */
        $propertyRepo = app()->make(PropertyRepository::class);
        $property = new Property(
            owner: new Owner(
                name: 'Jorginho',
                cpf: '01234567890',
                email: 'jorgin@email.com'
            ),
            city: 'São Paulo',
            number: '123',
            street: 'Rua teste',
            zipCode: '12345678',
            amount: 1_000_000
        );

        $propertyRepo->create($property);
        $savedProperty = PropertyModel::find($property->id);

        $this->assertEquals($property->owner->name, $savedProperty->owner->name);
        $this->assertEquals($property->owner->cpf->value(), $savedProperty->owner->cpf);
        $this->assertEquals($property->owner->email->value(), $savedProperty->owner->email);

        $this->assertEquals($property->amount, $savedProperty->amount);
        $this->assertEquals($property->zipCode, $savedProperty->zip_code);
        $this->assertEquals($property->number, $savedProperty->number);
        $this->assertEquals($property->street, $savedProperty->street);
        $this->assertEquals($property->city, $savedProperty->city);
    }

    public function testItShouldThwoValidationExceptionWhenInvalidCpfProvided(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Ops, o documento informado é inválido.');
        $this->expectExceptionCode('VALIDATION_ERROR');

        $useCase = app()->make(RegisterProperty::class);

        $property = new Property(
            owner: new Owner(
                name: 'Jorginho',
                cpf: '11111111111',
                email: 'jorgin@email.com'
            ),
            city: 'São Paulo',
            number: '123',
            street: 'Rua teste',
            zipCode: '12345678',
            amount: 1_000_000
        );

        $useCase->execute(new InputData($property));
    }

    public function testItShouldThwoValidationExceptionWhenInvalidEmailProvided(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Ops, o email informado é inválido.');
        $this->expectExceptionCode('VALIDATION_ERROR');

        $useCase = app()->make(RegisterProperty::class);

        $property = new Property(
            owner: new Owner(
                name: 'Jorginho',
                cpf: '01234567890',
                email: 'teste.com'
            ),
            city: 'São Paulo',
            number: '123',
            street: 'Rua teste',
            zipCode: '12345678',
            amount: 1_000_000
        );

        $useCase->execute(new InputData($property));
    }
}
