<?php

declare(strict_types=1);

namespace Vvmsoftware\Transactions;

use Exception;
use Vvmsoftware\Transactions\Exceptions\RollBackFailedException;
use Vvmsoftware\Transactions\Interfaces\TransactionItem;
use Vvmsoftware\Transactions\Registry as Registry;
use Vvmsoftware\Transactions\Interfaces\Registry as RegistryInterface;

/**
 * Simple library to enable transaction-like execution of code
 */
class Transaction implements TransactionItem
{
    /**
     * @var Registry
     * Registry for the items to save transient data to
     */
    private Registry $registry;

    /**
     * @var array
     * Collection of the items we are going to commit
     */
    private array $items;

    /**
     * @var Exception
     * Contains the exception object, in case one is thrown
     * by any of the items that we are commiting
     */
    private Exception $e;

    /**
     * @var int
     * Start rolling back from this index
     */
    private int $rollBackIndex = 0;

    public function __construct()
    {
        $this->registry = new Registry();
    }
    /**
     * Add an item to the transaction
     *
     * @param  TransactionItem $item
     * @return object
     */
    public function add(TransactionItem $item): object
    {
        $this->items[] = $item;
        return $this;
    }
    
    /**
     * Alias to commit() with an automatic empty registry object
     *
     * @return bool
     */
    public function run(): bool
    {
        return $this->commit($this->registry);
    }
    
    /**
     * Commit and run the transaction
     *
     * @param  RegistryInterface $r
     * @return bool
     * @throws RollBackFailedException when rolling back process fails
     */
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
        }
        return true;
    }
    
    /**
     * Rollback the transaction starting from the last idx
     *
     * @param  RegistryInterface $r
     * @return bool
     */
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
    
    /**
     * Returns the exception encountered during commit()
     *
     * @return Exception
     */
    public function getException(): \Exception
    {
        return $this->e;
    }
}
