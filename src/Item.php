<?php

namespace Vvmsoftware\Transactions;

use Vvmsoftware\Transactions\Interfaces\TransactionItem;
use Vvmsoftware\Transactions\Interfaces\Registry as RegistryInterface;

class Item implements TransactionItem
{
    public function commit(RegistryInterface $r): bool
    {
        return true;
    }

    public function rollback(RegistryInterface $r): bool
    {
        return true;
    }
}
