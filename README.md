# Signature

[![Latest Version on Packagist](https://img.shields.io/packagist/v/uteq/signature.svg?style=flat-square)](https://packagist.org/packages/uteq/signature)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/uteq/signature/run-tests?label=tests)](https://github.com/uteq/signature/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/uteq/signature.svg?style=flat-square)](https://packagist.org/packages/uteq/signature)

Signature gives you the ability to create action links that can be used everywhere on your site (including emails).

A simple url can be created by the example below. The first parameter is the class to execute the action when the user visits the link, the second parameter is an array that holds all the data to be provided to the action class. The payload automatically gets encrypted when entering the database.
```php 
$url = SignatureFacade::make(Action::class, ['email' => 'person@example.com'])->get();

```
The get() function returns a complete url based on the APP_URL in the .env file and the 'action_route' in the signature config

Example action class:
```php
class Action 
{
    public function __invoke($payload)
    {
        // Action        

        return redirect('login');
    }
}


```
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
     * if the action_route is action the url will for example be https://example.com/action/{key}
     */
    'action_route' => '/action/{key}',
    
    /*
    * When hidden actions is true Signature wont use the class string as a handler but a string that is devined in the actions
    * config below hiddne_actions. You cant still put a class string into the handler but it will not work if the same key is not
    * found in actions
    */
    'hidden_actions' => false,
    
    /*
    * Here you can define the actions, for example: 'action => '\App\SignatureActions\Action'
    * When making a url you will need to provide the key instead of the class path, in the example above it would look like
    * SignatureFacade::make('action', [])->get();
    */
    'actions' => [
        
    ]
];
```

## Usage
  You can create a link with the example provided below. 
``` php
$url = SignatureFacade::make(Action::class)
	->payload(['variable_1' => 'information', 'variable_2' => 'even more information'])
	->expirationDate(now()->addWeek())
	->password('secretPassword')
	->oneTimeLink()
	->get();
```
- payload(): Alternative way to pass variables to the link
- expirationDate(): Option to allow you to specify the expiration date (defaults to 2 weeks from creating the link)
- password(): Protects the link by asking for the password set in this function when using the link
- oneTimeLink(): Deletes the link when the action has successfully executed
- get(): Makes a complete url based on the APP_URL in the .env file and the 'action_route' in the signature config (defaults to /action/{key})

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
- [Nathan Jansen](https://github.com/uteq)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
