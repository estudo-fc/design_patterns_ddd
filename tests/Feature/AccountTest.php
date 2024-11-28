<?php

use Maruko\DddPhp\AccountApplicationService;
use Maruko\DddPhp\AccountRepositoryMemory;
use Maruko\DddPhp\CreditHandler;
use Maruko\DddPhp\DebitHandler;
use Maruko\DddPhp\Publisher;
use Maruko\DddPhp\TransferHandler;

$accountApplicationService = null;

beforeEach(function () use (&$accountApplicationService) {
    $publisher = new Publisher();
    $accountRepositoryMemory = new AccountRepositoryMemory();
    $publisher->register(new CreditHandler($accountRepositoryMemory));
    $publisher->register(new DebitHandler($accountRepositoryMemory));
    $publisher->register(new TransferHandler($accountRepositoryMemory));

    $accountApplicationService = new AccountApplicationService(
        $publisher,
        $accountRepositoryMemory
    );
});

test('Deve criar uma conta', function () use (&$accountApplicationService) {
    $document = "123.123.123-12";

    $accountApplicationService->create($document);

    $account = $accountApplicationService->get($document);

    expect($account->getBalance())
        ->toBeInt()
        ->toBe(0);
});

test('Deve criar uma conta e fazer um depósito', function () use (&$accountApplicationService) {
    $document = "123.123.123-12";

    $accountApplicationService->create($document);

    $accountApplicationService->credit($document, 1000);

    $account = $accountApplicationService->get($document);

    expect($account->getBalance())
        ->toBeInt()
        ->toBe(1000);
});

test('Deve criar uma conta e fazer um débito', function () use (&$accountApplicationService) {

    $document = "123.123.123-12";

    $accountApplicationService->create($document);

    $accountApplicationService->credit($document, 1000);

    expect($accountApplicationService->get($document)->getBalance())
        ->toBeInt()
        ->toBe(1000);

    $accountApplicationService->debit($document, 1000);

    expect($accountApplicationService->get($document)->getBalance())
        ->toBeInt()
        ->toBe(0);
});

test('Não deve ser possivel debitar valores igual ou menor que zero e ser maior que o saldo disponivel', function () use (&$accountApplicationService) {

    $document = "123.123.123-12";

    $accountApplicationService->create($document);

    expect(function () use ($accountApplicationService, $document) {
        $accountApplicationService->debit($document, 0);
    })
        ->toThrow(
            Exception::class,
            'Amount must be greater than 0'
        )
        ->and(function () use ($accountApplicationService, $document) {
            $accountApplicationService->debit($document, 1);
        })
        ->toThrow(Exception::class, 'Insufficient balance');

});

test('Não deve ser possivel depositar valores igual ou menor que zero', function () use (&$accountApplicationService) {

    $document = "123.123.123-12";

    $accountApplicationService->create($document);

    expect(fn() => $accountApplicationService->credit($document, 0))
        ->toThrow(
            Exception::class,
            'Amount must be greater than 0'
        );
});


test('Deve criar duas contas e fazer uma tranferência', function () use (&$accountApplicationService) {
    $documentFrom = "123.123.123-12";
    $documentTo = "123.123.123-11";

    $accountApplicationService->create($documentFrom);
    $accountApplicationService->credit($documentFrom, 1000);
    $accountApplicationService->create($documentTo);

    $accountApplicationService->credit($documentTo, 500);

    $accountApplicationService->transfer($documentFrom, $documentTo, 700);
    expect($accountApplicationService->get($documentFrom)->getBalance())->toBe(300)
        ->and($accountApplicationService->get($documentTo)->getBalance())->toBe(1200);
});
