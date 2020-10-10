<?php

namespace Vvmsoftware\Transactions\Interfaces;

use Vvmsoftware\Transactions\Interfaces\Registry as RegistryInterface;

interface TransactionItem
{
    /**
     * Commit code for this item
     *
     * @param  RegistryInterface $r
     * @return bool
     */
    public function commit(RegistryInterface $r): bool;
        
    /**
     * Rollback code for this item
     *
     * @param  RegistryInterface $r
     * @return bool
     */
    public function rollback(RegistryInterface $r): bool;
}
