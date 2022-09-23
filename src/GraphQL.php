<?php

namespace Efabrica\GraphQL;

use Efabrica\GraphQL\Drivers\DriverInterface;

final class GraphQL
{
    protected DriverInterface $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function executeQuery(string $query): array
    {
        return $this->driver->executeQuery($query);
    }
}
