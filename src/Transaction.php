<?php

declare(strict_types=1);

namespace Vvmsoftware\Transactions;

use Exception;
use Vvmsoftware\Transactions\Exceptions\RollBackFailedException;
use Vvmsoftware\Transactions\Interfaces\TransactionItem;
use Vvmsoftware\Transactions\Registry as Registry;
use Vvmsoftware\Transactions\Interfaces\Registry as RegistryInterface;

class Transaction implements TransactionItem
{
    private Registry $registry;
    private array $items;
    private Exception $e;
    private int $rollBackIndex = 0;

    public function __construct()
    {
        $this->registry = new Registry();
    }
    public function add(TransactionItem $item): object
    {
        $this->items[] = $item;
        return $this;
    }

    public function run(): bool
    {
        return $this->commit($this->registry);
    }

    public function commit(RegistryInterface $r): bool
    {
        foreach ($this->items as $index=>$item) {
            // Run each individual item, trapping any exceptions
            $shouldRollBack = false;
            try {
                if (!$item->commit($r)) {
                    $shouldRollBack = true;
                }
            } catch (Exception $e) {
                $this->e = $e;
                $shouldRollBack = true;
            }
            // Did we succeed in this action?
            if ($shouldRollBack) {
                // Nope.. rolling back
                $this->rollBackIndex = $index;
                if (!$this->rollback($r)) {
                    throw new RollBackFailedException("Could not roll back transaction", 1);
                }
                return false;
            }
            return true;
        }
    }

    public function rollback(RegistryInterface $r): bool
    {
        $currentIndex = $this->rollBackIndex;
        while ($currentIndex > -1) {
            if (!$this->items[$currentIndex]->rollback($r)) {
                return false;
            }
            $currentIndex--;
        }
        return true;
    }

    public function getException(): \Exception
    {
        return $this->e;
    }
}
