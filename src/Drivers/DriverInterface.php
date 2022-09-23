<?php

namespace Efabrica\GraphQL\Drivers;

interface DriverInterface
{
    public function executeQuery(string $query): array;
}
