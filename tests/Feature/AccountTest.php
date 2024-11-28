<?php

use Maruko\DddPhp\AccountApplicationService;
use Maruko\DddPhp\AccountBuilder;
use Maruko\DddPhp\AccountRepositoryMemory;
use Maruko\DddPhp\CreditCommand;
use Maruko\DddPhp\CreditHandler;
use Maruko\DddPhp\DebitCommand;
use Maruko\DddPhp\Publisher;
use Maruko\DddPhp\TransferCommand;

$accountApplicationService = null;

beforeEach(function () use (&$accountApplicationService) {
    $publisher = new Publisher();
    $accountRepositoryMemory = new AccountRepositoryMemory();
    $publisher->register(new CreditHandler($accountRepositoryMemory));

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

test('Deve criar uma conta e fazer um débito', function () {

    $accountBuilder = new AccountBuilder("123.123.123-12");

    $account = $accountBuilder
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();

    $creditCommand = new CreditCommand($account, 1000);
    $creditCommand->execute();

    $creditCommand = new DebitCommand($account, 1000);
    $creditCommand->execute();

    expect($account->getBalance())
        ->toBeInt()
        ->toBe(0);
});

test('Não deve ser possivel debitar valores igual ou menor que zero e ser maior que o saldo disponivel', function () {

    $accountBuilder = new AccountBuilder("123.123.123-12");

    $account = $accountBuilder
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();


    expect(function () use ($account) {
        $debitCommand = new DebitCommand($account, 0);
        $debitCommand->execute();
    })
        ->toThrow(
            Exception::class,
            'Amount must be greater than 0'
        )
        ->and(function () use ($account) {
            $debitCommand = new DebitCommand($account, 1);
            $debitCommand->execute();
        })
        ->toThrow(Exception::class, 'Insufficient balance');

});

test('Não deve ser possivel depositar valores igual ou menor que zero', function () {

    $accountBuilder = new AccountBuilder("123.123.123-12");

    $account = $accountBuilder
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();

    $creditCommand = new CreditCommand($account, 0);
    expect(fn() => $creditCommand->execute())
        ->toThrow(
            Exception::class,
            'Amount must be greater than 0'
        );
});


test('Deve criar duas contas e fazer uma tranferência', function () {

    $accountBuilderFrom = new AccountBuilder("123.123.123-12");
    $accountBuilderTo = new AccountBuilder("123.123.123-13");

    $accountFrom = $accountBuilderFrom
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();

    $accountTo = $accountBuilderTo
        ->setBank("031")
        ->setBranch("002")
        ->setAccount("98752-5")
        ->build();

    $creditCommandFrom = new CreditCommand($accountFrom, 1000);
    $creditCommandFrom->execute();

    $creditCommandTo = new CreditCommand($accountTo, 500);
    $creditCommandTo->execute();

    $creditCommandTo = new TransferCommand($accountFrom, $accountTo, 700);
    $creditCommandTo->execute();

    expect($accountFrom->getBalance())->toBe(300)
        ->and($accountTo->getBalance())->toBe(1200);
});
