# Facade Generator for Laravel
An Artisan command that generates a Service Provider and Facade based on a custom Service class file.

## Installation

```bash
composer require prezire/facade-generator
```

## Usage

```bash
php artisan make:facade [FacadeAliasName] [TargetServiceFile]
```

## Example: Create a FooBar facade based on Foo service class file.

```bash
php artisan make:facade FooBar \\App\\Services\\Foo
```

You must register the generated provider and alias for the facade in `config/app.php' file.

```php
'providers' => [
  App\Providers\Facades\FooBarServiceProvider::class,
],
'aliases' => [
  'FooBar' => App\Facades\FooBar::class,
],
```