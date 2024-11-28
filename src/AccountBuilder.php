<?php

namespace Maruko\DddPhp;

class AccountBuilder
{
    private ?string $bank = null;
    private ?string $branch = null;
    private ?string $account = null;
    private ?string $document;

    public function __construct(
        string $document
    )
    {
        $this->document = $document;
    }

    public function setBank(string $bank): AccountBuilder
    {
        $this->bank = $bank;
        return $this;
    }

    public function setBranch(string $branch): AccountBuilder
    {
        $this->branch = $branch;
        return $this;

    }

    public function setAccount(string $account): AccountBuilder
    {
        $this->account = $account;
        return $this;

    }

    public function build(): Account
    {
        return new Account($this);
    }


    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

}