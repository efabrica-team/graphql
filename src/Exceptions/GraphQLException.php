<?php

namespace Efabrica\GraphQL\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;
use Throwable;

abstract class GraphQLException extends Exception implements ClientAware
{
    protected ?Throwable $debugException = null;

    public function __construct($message = "", $code = 0, Throwable $debugException = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->debugException = $debugException;
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getDebugException(): ?Throwable
    {
        return $this->debugException;
    }
}
