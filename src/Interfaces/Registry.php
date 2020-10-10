<?php

namespace Vvmsoftware\Transactions\Interfaces;

interface Registry
{
    public function has(string $item): bool;
    public function get(string $item, $default=null);
    public function set(string $item, $value): void;
}
