<?php

declare(strict_types=1);

namespace Tests\Integration\Proposal;

use DateTime;
use PHPUnit\Framework\TestCase;
use App\Proposal\Domain\Entity\Payment;
use App\Proposal\Domain\Entity\Customer;
use App\Proposal\Domain\Entity\Proposal;
use App\Proposal\Domain\Enum\ProposalStatuses;
use App\Shared\Infra\Exceptions\ValidationException;
use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Infra\Services\ProposalApi\ProposalApi;
use App\Proposal\Domain\UseCase\CreateProposal\InputData;
use App\Proposal\Domain\UseCase\CreateProposal\CreateProposal;
use App\Proposal\Domain\UseCase\MarkProposalCreated\MarkProposalCreated;
use App\Proposal\Domain\UseCase\MarkProposalCreated\InputData as MarkProposalCreatedInputData;

class ProposalTest extends TestCase
{
    public function testItShouldCreateProposalWhenValidDataProvided(): void
    {
        /**
         * @var  ProposalRepository $proposalRepo
         */
        $proposalRepo = app()->make(ProposalRepository::class);
        $proposal = new Proposal(
            customer: new Customer(
                name: 'Jorginho',
                cpf: '01234567890',
                birthDate: new DateTime('2000-07-10'),
                email: 'jorgin@email.com'
            ),
            payment: new Payment('1000', 'teste@teste.com')
        );

        $proposalRepo->create($proposal);
        $savedProposal = $proposalRepo->findById($proposal->id);

        $this->assertEquals($proposal->customer->name, $savedProposal->customer->name);
        $this->assertEquals($proposal->customer->cpf->number, $savedProposal->customer->cpf->number);
        $this->assertEquals($proposal->customer->birthDate->format('Y-m-d'), $savedProposal->customer->birthDate->format('Y-m-d'));
        $this->assertEquals($proposal->payment->amount, $savedProposal->payment->amount);
        $this->assertEquals($proposal->payment->pixKey, $savedProposal->payment->pixKey);
        $this->assertEquals($proposal->number, $savedProposal->number);
        $this->assertEquals(ProposalStatuses::PENDING, $savedProposal->status);
        $this->assertEquals($proposal->status, $savedProposal->status);
    }

    public function testItShouldThrowValidationExceptionWhenInvalidCpfProvided(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Ops, o documento digitado é inválido.');
        $this->expectExceptionCode('VALIDATION_ERROR');

        $useCase = app()->make(CreateProposal::class);

        $proposal = new Proposal(
            customer: new Customer(
                name: 'Jorginho',
                cpf: '1223456543',
                birthDate: new DateTime('2000-07-10'),
                email: 'jorgin@email.com'
            ),
            payment: new Payment('1000', 'teste@teste.com')
        );

        $useCase->execute(new InputData($proposal));
    }

    public function testItShouldThrowValidationExceptionWhenInvalidEmailProvided(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Ops, o email digitado é inválido.');
        $this->expectExceptionCode('VALIDATION_ERROR');

        $useCase = app()->make(CreateProposal::class);

        $proposal = new Proposal(
            customer: new Customer(
                name: 'Jorginho',
                cpf: '01234567890',
                birthDate: new DateTime('2000-07-10'),
                email: 'jorgin.com'
            ),
            payment: new Payment('1000', 'teste@teste.com')
        );

        $useCase->execute(new InputData($proposal));
    }

    public function testItShouldMarkProposalAsCreatedWhenExternalApiReturnApproved(): void
    {
        $proposalRepo = app()->make(ProposalRepository::class);
        $markProposalCreatedUseCase = app()->make(MarkProposalCreated::class);

        $proposalApiService = app()->make(ProposalApi::class);

        $proposal = new Proposal(
            customer: new Customer(
                name: 'Jorginho',
                cpf: '01234567890',
                birthDate: new DateTime('2000-07-10'),
                email: 'jorginho@gmail.com'
            ),
            payment: new Payment('1000', 'teste@teste.com')
        );

        $proposalRepo->create($proposal);

        $serviceStatus = false;

        while (!$serviceStatus) {
            $serviceStatus = $proposalApiService->checkHttpStatus();
        }

        $markProposalCreatedUseCase->execute(new MarkProposalCreatedInputData($proposal));

        $savedProposal = $proposalRepo->findById($proposal->id);

        $this->assertEquals($proposal->customer->name, $savedProposal->customer->name);
        $this->assertEquals($proposal->customer->cpf->number, $savedProposal->customer->cpf->number);
        $this->assertEquals($proposal->customer->birthDate->format('Y-m-d'), $savedProposal->customer->birthDate->format('Y-m-d'));
        $this->assertEquals($proposal->payment->amount, $savedProposal->payment->amount);
        $this->assertEquals($proposal->payment->pixKey, $savedProposal->payment->pixKey);
        $this->assertEquals($proposal->number, $savedProposal->number);
        $this->assertEquals(ProposalStatuses::CREATED, $savedProposal->status);
        $this->assertEquals($proposal->status, $savedProposal->status);
    }
}
