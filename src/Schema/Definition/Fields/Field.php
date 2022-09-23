<?php

namespace Efabrica\GraphQL\Schema\Definition\Fields;

use Efabrica\GraphQL\Resolvers\ResolverInterface;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\HasSettigns;
use Efabrica\GraphQL\Schema\Definition\Types\Type;
use Error;

class Field
{
    use HasSettigns;

    protected Type $type;

    protected string $name;

    protected ?string $description = null;

    /** @var FieldArgument[] */
    protected array $arguments = [];

    protected bool $multi = false;

    protected bool $nullable = false;

    protected bool $multiItemNullable = false;

    protected ?ResolverInterface $resolver = null;

    public function __construct(string $name, Type $type)
    {
        $this->setName($name);
        $this->setType($type);
    }

    public function getType(): Type
    {
        return $this->type;
    }

    /* @return static */
    public function setType(Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /* @return static */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /* @return static */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function addArgument(FieldArgument $argument): self
    {
        $this->arguments[$argument->getName()] = $argument;
        return $this;
    }

    /**
     * @param FieldArgument[] $arguments
     *
     * @return static
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = [];
        foreach ($arguments as $argument) {
            $this->arguments[$argument->getName()] = $argument;
        }
        return $this;
    }

    public function isMulti(): bool
    {
        return $this->multi;
    }

    /* @return static */
    public function setMulti(bool $multi = true): self
    {
        $this->multi = $multi;
        return $this;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /* @return static */
    public function setNullable(bool $nullable = true): self
    {
        $this->nullable = $nullable;
        return $this;
    }

    public function isMultiItemNullable(): bool
    {
        return $this->multiItemNullable;
    }

    /* @return static */
    public function setMultiItemNullable(bool $multiItemNullable = true): self
    {
        $this->multiItemNullable = $multiItemNullable;
        return $this;
    }

    public function getResolver(): ?ResolverInterface
    {
        return $this->resolver;
    }

    public function setResolver(?ResolverInterface $resolver): self
    {
        $this->resolver = $resolver;
        return $this;
    }
}
