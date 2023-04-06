<?php

namespace Efabrica\GraphQL\Drivers;

use Efabrica\GraphQL\Exceptions\GraphQLException;
use Efabrica\GraphQL\Helpers\AdditionalResponseData;
use Efabrica\GraphQL\Schema\Loaders\SchemaLoaderInterface;
use Efabrica\GraphQL\Schema\Transformers\WebonyxSchemaTransformer;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\Error;
use GraphQL\GraphQL as WebonyxGraphQl;

final class WebonyxDriver implements DriverInterface
{
    private SchemaLoaderInterface $schemaLoader;

    private WebonyxSchemaTransformer $schemaTransformer;

    private AdditionalResponseData $additionalResponseData;

    private bool $debug = false;

    public function __construct(SchemaLoaderInterface $schemaLoader, AdditionalResponseData $additionalResponseData)
    {
        $this->schemaLoader = $schemaLoader;
        $this->additionalResponseData = $additionalResponseData;
        $this->schemaTransformer = new WebonyxSchemaTransformer();
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    public function executeQuery(string $query): array
    {
        $result = WebonyxGraphQl::executeQuery($this->schemaTransformer->handle($this->schemaLoader->getSchema()), $query)
            ->setErrorsHandler(function (array $errors, callable $formatter) {
                /**
                 * @var Error $error
                 */
                foreach ($errors as $key => $error) {
                    $exception = $error->getPrevious();
                    if ($this->debug && $exception instanceof GraphQLException && $exception->getDebugException() !== null) {
                        $errors[$key] = new Error(
                            $exception->getDebugException()->getMessage(),
                            $error->getNodes(),
                            $error->getSource(),
                            $error->getPositions(),
                            $error->getPath(),
                            $exception,
                            $error->getExtensions()
                        );
                    }
                }
                return array_map($formatter, $errors);
            });

        $debugFlag = DebugFlag::NONE;
        $additionalResponseData = $this->additionalResponseData->data;
        if ($this->debug) {
            $debugFlag = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
            $additionalResponseData = array_merge($additionalResponseData, ['debug' => $this->additionalResponseData->debugData]);
        }

        return array_merge($result->toArray($debugFlag), $additionalResponseData);
    }
}
