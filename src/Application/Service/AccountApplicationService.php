<?php

namespace Maruko\DddPhp\Application\Service;

use Maruko\DddPhp\Application\Command\CreditCommand;
use Maruko\DddPhp\Application\Command\DebitCommand;
use Maruko\DddPhp\Application\Command\TransferCommand;
use Maruko\DddPhp\Domain\Builder\AccountBuilder;
use Maruko\DddPhp\Domain\Entity\Account;
use Maruko\DddPhp\Domain\Repository\AccountRepository;
use Maruko\DddPhp\Infra\Queue\Publisher;

readonly class AccountApplicationService
{

    public function __construct(
        private Publisher         $publisher,
        private AccountRepository $accountRepository
    )
    {
    }

    public function get(string $accountDocument): Account
    {
        return $this->accountRepository->get($accountDocument);
    }

    public function create(string $document): void
    {
        $accountBuilder = new AccountBuilder($document);
        $account = $accountBuilder->build();
        $this->accountRepository->save($account);
    }

    public function credit(string $document, int $amount): void
    {
        $creditCommand = new CreditCommand($document, $amount);
        $this->publisher->publish($creditCommand);
    }

    public function debit(string $document, int $amount): void
    {
        $debitCommand = new DebitCommand($document, $amount);
        $this->publisher->publish($debitCommand);

    }

    public function transfer(string $documentFrom, string $documentTo, int $amount): void
    {
        $transferCommand = new TransferCommand($documentFrom, $documentTo, $amount);
        $this->publisher->publish($transferCommand);

    }

    public function getAccountRepository(): AccountRepository
    {
        return $this->accountRepository;
    }
}