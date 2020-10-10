<?php
declare(strict_types=1);
namespace Vvmsoftware\Transactions;

use Vvmsoftware\Transactions\Interfaces\Registry as RegistryInterface;

class Registry implements RegistryInterface
{
    protected $store = array();

    public function __construct()
    {
        //
    }

    public function has(string $item): bool
    {
        return array_key_exists($item, $this->store);
    }

    public function get(string $item, $default=null)
    {
        return $this->has($item) ? $this->store[$item] : $default;
    }

    public function set(string $item, $value): void
    {
        $this->store[$item] = $value;
    }
}
