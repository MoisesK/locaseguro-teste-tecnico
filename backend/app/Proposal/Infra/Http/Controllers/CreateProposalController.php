<?php

declare(strict_types=1);

namespace App\Proposal\Infra\Http\Controllers;

use Throwable;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Proposal\Domain\Entity\Payment;
use App\Proposal\Domain\Entity\Customer;
use App\Proposal\Domain\Entity\Proposal;
use App\Shared\Infra\Traits\JsonResponsable;
use App\Proposal\Domain\UseCase\CreateProposal\InputData;
use App\Proposal\Domain\UseCase\CreateProposal\CreateProposal;

class CreateProposalController extends Controller
{
    use JsonResponsable;
    public function __construct(
        private CreateProposal $useCase
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payloadData = $request->validate([
            'cpf' => 'string|required',
            'name' => 'string|required',
            'email' => 'string|required',
            'birth_date' => 'date|required',
            'proposal_amount' => 'numeric|required',
            'pix_key' => 'string|required'
        ]);

        $payloadData['proposal_amount'] = preg_replace('/(\.\d+)/', '', (string)$payloadData['proposal_amount']);

        try {
            $this->useCase->execute(new InputData(new Proposal(
                customer: new Customer($payloadData['name'], $payloadData['cpf'], new DateTimeImmutable($payloadData['birth_date']), $payloadData['email']),
                payment: new Payment($payloadData['proposal_amount'], $payloadData['pix_key'])
            )));

            return $this->created([], 'Sua proposta está em análise.');
        } catch (Throwable $e) {
            return $this->error($e, $e->getMessage());
        }

    }
}
