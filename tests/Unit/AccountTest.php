<?php

namespace Tests\Unit;

use Exception;
use Maruko\DddPhp\Domain\Builder\AccountBuilder;
use Maruko\DddPhp\Domain\Service\TransferService;

test('Deve criar uma conta', function () {

    $accountBuilder = new AccountBuilder("123.123.123-12");

    $account = $accountBuilder
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();

    expect($account->getBalance())
        ->toBeInt()
        ->toBe(0);
});

test('Deve criar uma conta e fazer um depósito', function () {

    $accountBuilder = new AccountBuilder("123.123.123-12");

    $account = $accountBuilder
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();

    $account->credit(1000);

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

    $account->credit(1000);

    $account->debit(1000);

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


    expect(fn() => $account->debit(0))
        ->toThrow(
            Exception::class,
            'Amount must be greater than 0'
        )
        ->and(fn() => $account->debit(1))
        ->toThrow(Exception::class, 'Insufficient balance');

});

test('Não deve ser possivel depositar valores igual ou menor que zero', function () {

    $accountBuilder = new AccountBuilder("123.123.123-12");

    $account = $accountBuilder
        ->setBank("033")
        ->setBranch("001")
        ->setAccount("98757-9")
        ->build();

    expect(fn() => $account->credit(0))
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


    $accountFrom->credit(1000);
    $accountTo->credit(500);

    // account -> transfer($accountFrom, $accountTo, 700)
    $transferService = new TransferService();
    $transferService->transfer($accountFrom, $accountTo, 700);

    expect($accountFrom->getBalance())->toBe(300)
        ->and($accountTo->getBalance())->toBe(1200);
});
