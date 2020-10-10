<?php

namespace Vvmsoftware\Transactions\Interfaces;

interface Registry
{
    /**
     * Check if the registry has a record for $item
     *
     * @param  string $item The item name we are looking for
     * @return bool
     */
    public function has(string $item): bool;

    /**
     * Get $item from the registry
     *
     * @param  string $item The item name we are looking for
     * @param  mixed $default Returned if the item does not exist. Default: null
     * @return void
     */
    public function get(string $item, $default=null);

        
    /**
     * Set $item to $value in the registry
     *
     * @param  string $item The item name we are setting
     * @param  mixed $value The actual value
     * @return void
     */
    public function set(string $item, $value): void;
}
