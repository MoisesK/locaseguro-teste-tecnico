<?php

declare(strict_types=1);

namespace App\Property\Infra\Http\Controllers;

use App\Property\Domain\Entity\Owner;
use App\Property\Domain\Entity\Property;
use App\Property\UseCase\RegisterProperty\InputData;
use App\Property\UseCase\RegisterProperty\RegisterProperty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Shared\Infra\Traits\JsonResponsable;

class CreatePropertyController extends Controller
{
    use JsonResponsable;
    public function __construct(
        private RegisterProperty $useCase
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payloadData = $request->validate([
            'owner' => 'array:name,cpf,email|required',
            'city' => 'string|required',
            'street' => 'string|required',
            'zipCode' => 'string|required',
            'number' => 'string|required',
            'amount' => 'numeric|required'
        ]);

        $payloadData['amount'] = preg_replace('/(\.\d+)/', '', (string)$payloadData['amount']);

        $property = new Property(
            owner: new Owner(
                name: $payloadData['owner']['name'],
                cpf: $payloadData['owner']['cpf'],
                email: $payloadData['owner']['email']
            ),
            city: $payloadData['city'],
            street: $payloadData['street'],
            zipCode: $payloadData['zipCode'],
            number: $payloadData['number'],
            amount: $payloadData['amount']
        );

        $this->useCase->execute(new InputData($property));

        return $this->created(
            data: $property->toArray(), 
            message: 'Propriedade registrada com sucesso.'
        );
    }
}
