<?php

namespace Vvmsoftware\Transactions\Interfaces;

use Vvmsoftware\Transactions\Interfaces\Registry as RegistryInterface;

interface TransactionItem
{
    public function commit(RegistryInterface $r): bool;
    public function rollback(RegistryInterface $r): bool;
}
