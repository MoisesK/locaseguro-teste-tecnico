<?php

declare(strict_types=1);

namespace App\Proposal\Infra\Repository;

use DateTime;
use App\Proposal\Domain\Entity\Payment;
use App\Proposal\Domain\Entity\Customer;
use App\Proposal\Domain\Entity\Proposal;
use App\Proposal\Domain\Enum\ProposalStatuses;
use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Infra\Models\Customer as CustomerModel;
use App\Shared\Infra\Models\Proposal as ProposalModel;

class ProposalEloquentRepository implements ProposalRepository
{
    public function findById(int $id): ?Proposal
    {
        $record = ProposalModel::where('id', $id)->first();

        if (!$record) {
            return null;
        }

        return new Proposal(
            customer: new Customer(
                name: $record->customer->name,
                cpf: $record->customer->cpf,
                birthDate: new DateTime($record->customer->birth_date),
                email: $record->customer->email
            ),
            payment: new Payment($record->amount, $record->pix_key),
            id: $record->id,
            number: $record->number,
            status: ProposalStatuses::tryFrom($record->status)
        );
    }

    public function create(Proposal $proposal): void
    {
        $customer = CustomerModel::where('id', $proposal->customer->id)->first();

        if (!$customer) {
            $customer = CustomerModel::create([
                'name' => $proposal->customer->name,
                'cpf' => $proposal->customer->cpf->value(),
                'birth_date' => $proposal->customer->birthDate->format('Y-m-d'),
                'email' => $proposal->customer->email->value()
            ]);
        }

        $record = ProposalModel::create([
            'customer_id' => $customer->id,
            'number' => $proposal->number,
            'amount' => $proposal->payment->amount,
            'pix_key' => $proposal->payment->pixKey,
            'status' => $proposal->status->value
        ]);

        $proposal->id = $record->id;
    }

    public function update(Proposal $proposal): void
    {
        $record = ProposalModel::where('id', $proposal->id)->first();

        if (!$record) {
            return;
        }
        $record->update([
            'status' => $proposal->status->value
        ]);
    }
}
