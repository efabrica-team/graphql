# GraphQL (work in progress)

This package is using [webonyx/graphql-php](https://github.com/webonyx/graphql-php) as GraphQL driver and is meant to be
used as automatic schema definition loader.

## Installation

Via composer
```sh
composer require efabrica/graphql
```

## Usage

### Schema definition
```php
use Efabrica\GraphQL\Resolvers\ResolverInterface;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\ResolveInfo;
use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IDType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;

$userResolver = new class implements ResolverInterface {
    public function __invoke($parentValue, array $args, ResolveInfo $resolveInfo): array
    {
        $limit = $args['pagination']['limit'] ?? null;
        $offset = $args['pagination']['offset'] ?? 0;
        
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@doe.com',
            ],
            [
                'id' => 2,
                'name' => 'Jane Dane',
                'email' => 'jane@dane.com',
            ],
            [
                'id' => 3,
                'name' => 'Moe Lester',
                'email' => 'moe@lester.com',
            ],
        ];
        
        return array_slice($users, $offset, $limit);
    }
};

$userObjectType = (new ObjectType('User'))
    ->setFields([
        new Field('id', new IDType()),
        new Field('name', new StringType()),
        new Field('email', new StringType()),
    ]);

$paginationArgument = new FieldArgument(
    'pagination',
    (new InputObjectType('pagination_argument'))
        ->setFields([
            (new InputObjectField('limit', new IntType()))
                ->setNullable(),
            (new InputObjectField('offset', new IntType()))
                ->setNullable(),
        ])
);

$schema = (new Schema())
    ->setQuery(
        (new ObjectType('Query'))
            ->setFields([
                (new Field('Users', $userObjectType))
                    ->setMulti()
                    ->setArguments([
                        $paginationArgument,
                    ])
                    ->setResolver($userResolver),
            ])
    );

```

### Initialization
```php
use Efabrica\GraphQL\Drivers\WebonyxDriver;
use Efabrica\GraphQL\GraphQL;
use Efabrica\GraphQL\Schema\Loaders\DefinitionSchemaLoader;


$schemaLoader = new DefinitionSchemaLoader($schema);
$driver = new WebonyxDriver($schemaLoader);
$graphql = new GraphQL($driver);
```

### Fetching results

```php
$query = <<<GQL
    {
        Users (
            pagination: {
                limit: 2
                offset: 1
            }
        ) {
            id
            name
            email
        }
    }
    GQL;

$users = $graphql->executeQuery($query);

//array(1) {
//  'Users' =>
//  array(2) {
//    [0] =>
//    array(3) {
//      'id' =>
//      string(1) "2"
//      'name' =>
//      string(9) "Jane Dane"
//      'email' =>
//      string(13) "jane@dane.com"
//    }
//    [1] =>
//    array(3) {
//      'id' =>
//      string(1) "3"
//      'name' =>
//      string(10) "Moe Lester"
//      'email' =>
//      string(14) "moe@lester.com"
//    }
//  }
//}
```
