<?php

declare(strict_types=1);

namespace App\Property\Infra\Repository;

use App\Property\Domain\Contracts\PropertyRepository;
use App\Property\Domain\Entity\Owner;
use App\Property\Domain\Entity\Property;
use App\Shared\Infra\Models\Owner as OwnerModel;
use App\Shared\Infra\Models\Property as PropertyModel;
use DateTime;

class PropertyEloquentRepository implements PropertyRepository
{
    public function getAll(): array
    {
        return PropertyModel::paginate(50)
            ->map(function (PropertyModel $record) {
                $property = new Property(
                    owner: new Owner(
                        name: $record->owner->name,
                        cpf: $record->owner->cpf,
                        email: $record->owner->email
                    ), 
                    city: $record->city, 
                    number: $record->number, 
                    street: $record->street, 
                    zipCode: $record->zip_code, 
                    amount: $record->amount
                );

                $property->id = $record->id;
                $property->owner->id = $record->owner_id;

                return $property->toArray();
            })    
            ->toArray();
    }
    
    public function findOwnerByField(string $field, mixed $value): ?Owner
    {
        $record = OwnerModel::where($field, $value)->first();

        if (!$record) {
            return null;
        }
        
        $owner = new Owner(
            name: $record->name,
            cpf: $record->cpf,
            email: $record->email
        );

        $owner->id = $record->id;

        return $owner;
    }


    public function create(Property $property): void
    {
        $owner = OwnerModel::where('cpf', $property->owner->cpf->value())->first();

        if (!$owner) {
            $owner = OwnerModel::create([
                'name' => $property->owner->name,
                'cpf' => $property->owner->cpf->value(),
                'email' => $property->owner->email->value()
            ]);

            $property->owner->id = $owner->id;
        }

        $record = PropertyModel::create([
            'owner_id' => $owner->id,
            'city' => $property->city,
            'street' => $property->street,
            'zip_code' => $property->zipCode,
            'number' => $property->number,
            'amount' => $property->amount
        ]);

        $property->id = $record->id;
        $property->owner->id = $owner->id;
    }
}
