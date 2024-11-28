<?php

namespace Maruko\DddPhp;

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