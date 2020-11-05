# Signature

[![Latest Version on Packagist](https://img.shields.io/packagist/v/uteq/signature.svg?style=flat-square)](https://packagist.org/packages/uteq/signature)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/uteq/signature/run-tests?label=tests)](https://github.com/uteq/signature/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/uteq/signature.svg?style=flat-square)](https://packagist.org/packages/uteq/signature)


Signature gives the user the ability to create link that can be use to perform action based on the variables and the class provided when generating the link

## Installation

You can install the package via composer:

```bash
composer require uteq/signature
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Uteq\Signature\SignatureServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Uteq\Signature\SignatureServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /*
     * This will be the url Signature will use to handle the actions
     * if the action_route is action the url will for example be https://example.com/action/<key>
     */
    'action_route' => '/action/{key}'
];
```

## Usage
  You can create a link with the example provided below. 
``` php
$url = SignatureFacade::make(Action::class)
	->payload(['variable_1' => 'information', 'variable_2' => 'even more information'])
	->expirationDate(now()->addWeeks(2))
	->password('secretPassword')
	->oneTimeLink()
	->get();
```

Action class:
```php
class Action
{
    public function __invoke($payload)
    {
        // from here on you can use the variables in $payload to make the link actually do something;

        return redirect('/login'); // If no return is provided the user will be redirected to "/".
    }

}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Stef van den Berg](https://github.com/stef1904berg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
