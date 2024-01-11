# Orchid log viewer

[![Run tests](https://github.com/czernika/orchid-log-viewer/actions/workflows/tests.yml/badge.svg)](https://github.com/czernika/orchid-log-viewer/actions/workflows/tests.yml)

Orchid-style table layout to view and manage Laravel application storage logs within admin panel

![Orchid log table](https://github.com/czernika/orchid-log-viewer/blob/media/assets/screen.gif?raw=true)

## Support

Version 1.x requires PHP at least 8.1, Orchid version 14 or higher and Laravel version 10+

| Version | PHP  | Laravel | Orchid |
|---------|------|---------|--------|
| 1.x     | 8.1+ | 10.x    | 14.x   |

This package is based on [rap2hpoutre/laravel-log-viewer](https://github.com/rap2hpoutre/laravel-log-viewer) package

## Installation

Install package via composer

```sh
composer require czernika/orchid-log-viewer
```

All you need to do now is to access `logs` page (it should be available under Orchid prefix, most likely it will `/admin/logs`)

If you need to publish configuration and localization files run following command

```sh
php artisan vendor:publish --provider="Czernika\OrchidLogViewer\OrchidLogServiceProvider"
```

Configuration file contains comments which should help you to understand package little bit more

## Configuration

This package is ready to work out of the box. However you may change anything you wish by your needs

### Changing Screen

If you need to change some data on a Screen and there is no config option for that you still can completely change Screen itself

Create new class and extend it from `OrchidLogListScreen` class

```php
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;

class CustomLogListScreen extends OrchidLogListScreen
{
    // Change logic
}
```

Register it in `AppServiceProvider` in order to use it

```php
use Czernika\OrchidLogViewer\LogManager;

public function boot()
{
    LogManager::useScreen(CustomLogListScreen::class);
}
```

Default layout consists of three parts - Filters, Modal for stack trace and Table. Table hits `logs` target - make sure it is return in a query method or change layout

### Changing Layout

If you need to extend columns, add new one it is also possible

Same technique - create new class and extend it from `OrchidLogTableLayout` class

```php
use Czernika\OrchidLogViewer\Layouts\OrchidLogTableLayout;

class CustomLogTableLayout extends OrchidLogTableLayout
{
    // Change logic
}
```

Register it in `AppServiceProvider` in order to use it

```php
use Czernika\OrchidLogViewer\LogManager;

public function boot()
{
    LogManager::useLayout(CustomLogTableLayout::class);
}
```

### Change LogData object

All logs returned as `Czernika\OrchidLogViewer\LogData` class. It has access to all properties from [rap2hpoutre/laravel-log-viewer](https://github.com/rap2hpoutre/laravel-log-viewer) package but in a object

Basic log represents an array with the following data

```php
[
    'text' => '',
    'context' => '',
    'level' => '',
    'level_class' => '',
    'level_img' => '',
    'folder' => '',
    'in_file' => '',
    'date' => '',
]
```

This package converts it into `LogData` class with array of data as the only parameter in order to access all its data in a convinient way (via methods with the same name but in a camelCase) ...

```php
$log->text();
$log->levelClass();

// etc
```

... plus to have access to Orchid column shortcuts

```php
TD::make('text', 'Text'),

// instead of

TD::make('text', 'Text')
    ->render(fn (array $log) => $log['text']),
```

However if you wish you may change mapper class also and completely override its logic

Create new class

> Remember - it will accept only array of log data!

```php
class CustomLogData
{
    public function __construct(
        protected readonly array $data,
    ) {}

    public function areWeGood(): bool
    {
        return 'error' === $this->data['level'] ? 'Nope' : 'Sure we are';
    }
}
```

Register it

```php
use Czernika\OrchidLogViewer\LogManager;

public function boot()
{
    LogManager::useMapper(CustomLogData::class);
}
```

Now you can render custom layout

```php
TD::make('are_we_good')
    ->render(fn (CustomLogData $log) => $log->areWeGood()),
```

If you wish to keep possibility for Orchid shortcuts name array of data as `$data` variable and use `Czernika\OrchidLogViewer\Support\Traits\Contentable` trait

```php
use Czernika\OrchidLogViewer\Support\Traits\Contentable;

class CustomLogData
{
    use Contentable;

    public function __construct(
        protected readonly array $data,
    ) {}

    public function areWeGood(): bool
    {
        return 'error' === $this->data['level'] ? 'Nope' : 'Sure we are';
    }
}
```

And remove render function

```php
TD::make('areWeGood'), // pass name in a exact case as method in a custom class (not `are_we_good` in this case)
```

Of course you need to change Table layout also

### Change actions (clear and delete)

Clear and delete actions basically clear and delete files, shows toasts and redirect back to the specified route. If you need to do extra work you may change behavior of these actions by registering your own

```php
use Czernika\OrchidLogViewer\LogManager;

public function boot()
{
    LogManager::clearLogFileUsing(CustomClearLogFileAction::class);
    LogManager::deleteLogFileUsing(CustomDeleteLogFileAction::class);
}
```

Every action should inherit `Czernika\OrchidLogViewer\Contracts\HandlesLogs` contract and therefore implement `handle` method

```php
use Czernika\OrchidLogViewer\Contracts\HandlesLogs;
use Czernika\OrchidLogViewer\Contracts\LogServiceContract;

public function handle(LogServiceContract $logService, string $file): void
{
    // ...
}
```

`$logService` has access to methods to clear or delete file (as `clearFile($file)` and `deleteFile($file)`). `$file` variables contains basename of the log file (not the full path to it)

> Note - no need to redirect in action - it should NOT return response
> Package handles redirects itself

### Other options

In a configuration file you can disable filters - it may be useful when you're using `stack` log channel and therefore you have one file only

Also you can disable registration of menu item and route completely but you have to re-register them on your own

## Known issues

- Works only with "laravelish" logs - if you put for example `worker.log` which created by process managers like supervisor or pm2 it will not be recognized correctly

## Roadmap

- [x] - Add option to exclude "unreadable" log files from filters
- [ ] - Consider: maybe is it worth to register `platform.logs` permission which will be required in order to access screen?

## Testing

Using [Pest](https://pestphp.com/) as testing tool

```sh
composer test
```

### TODOs

- [ ] - Feature tests for custom screens/layout
- [ ] - Unit tests for custom objects
- [ ] - Tests for Filter

## License

Open-source under [MIT](LICENSE)
