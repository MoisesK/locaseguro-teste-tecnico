<?php

declare(strict_types=1);

namespace App\Property\UseCase\RegisterProperty;

use App\Shared\Infra\Exceptions\ValidationException;
use App\Property\Domain\Contracts\PropertyRepository;

class RegisterProperty
{
    public function __construct(
        private PropertyRepository $repository
    ) {
    }

    public function execute(InputData $inputData): void
    {
        if (!$inputData->property->owner->cpf->isValid) {
            throw new ValidationException(['field' => 'cpf', 'error' => 'invalidCpf'], 'Ops, o documento informado é inválido.');
        }
        
        if (!$inputData->property->owner->email->value()) {
            throw new ValidationException(['field' => 'email', 'error' => 'invalidEmail'], 'Ops, o email informado é inválido.');
        }

        $this->repository->create($inputData->property);
    }
}
