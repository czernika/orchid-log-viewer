# Orchid log viewer

[![Run tests](https://github.com/czernika/orchid-log-viewer/actions/workflows/tests.yml/badge.svg)](https://github.com/czernika/orchid-log-viewer/actions/workflows/tests.yml)

Log table layout designed in Orchid style allows you to view and manage Laravel application storage logs within admin panel

![Orchid log table](https://github.com/czernika/orchid-log-viewer/blob/media/assets/screen.gif?raw=true)

## Support

Version 1.x requires PHP greater than 8.1, Orchid version 14 or higher and Laravel version 10+

| Version | PHP  | Laravel | Orchid |
|---------|------|---------|--------|
| 1.x     | 8.1+ | 10.x    | 14.x   |

This package is based on [rap2hpoutre/laravel-log-viewer](https://github.com/rap2hpoutre/laravel-log-viewer) package

## Installation

Install package via composer

```sh
composer require czernika/orchid-log-viewer
```

That's it. Package registers menu item and screen for you. All you need to do now is to access `logs` page (it should be available under Orchid prefix, most likely it will be `/admin/logs`)

### Publish configuration

If you need to publish configuration and localization files run following command

```sh
php artisan vendor:publish --provider="Czernika\OrchidLogViewer\OrchidLogServiceProvider"
```

Configuration file contains comments which should help you to understand this package little bit more

## Configuration

This package is ready to work out of the box. However you may change any options you wish by your needs

### Changing default Screen

If you need to change some data on a Screen and there is no config option for that you still can completely override Screen itself

Create new custom screen class and extend it from `OrchidLogListScreen` class

```php
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;

class CustomLogListScreen extends OrchidLogListScreen
{
    // Change logic
}
```

Register it in `AppServiceProvider`. This way package will be aware of using custom screen instead of default one

```php
use Czernika\OrchidLogViewer\LogManager;

public function boot()
{
    LogManager::useScreen(CustomLogListScreen::class);
}
```

Default layout consists of three parts - Filters, Modal for stack trace and Table. This mostly should be the same for custom screen. Table hits `logs` target - make sure it was returned within a `query()` method or change layout

### Changing default Layout

If you need to extend columns or add new one it is also possible

Same technique - create new class and extend it from `OrchidLogTableLayout` class

```php
use Czernika\OrchidLogViewer\Layouts\OrchidLogTableLayout;

class CustomLogTableLayout extends OrchidLogTableLayout
{
    // Change logic
}
```

Register it in `AppServiceProvider`

```php
use Czernika\OrchidLogViewer\LogManager;

public function boot()
{
    LogManager::useLayout(CustomLogTableLayout::class);
}
```

Add custom columns. You may use some of default columns as these:

```php
public function columns(): iterable
{
    return [
        $this->levelColumn(), // Level icon with level name
        $this->textColumn(), // Log message text (70% table wide)
        $this->stackTraceColumn(), // Stack trace modal button
        $this->dateColumn(), // Date column
    ];
}
```

### Change LogData object

All logs returned as `Czernika\OrchidLogViewer\LogData` class. It has access to all properties from [rap2hpoutre/laravel-log-viewer](https://github.com/rap2hpoutre/laravel-log-viewer) package but in a object way

Basic log represents an array with the following data:

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

This package converts it into `LogData` class with array of data as the only parameter in order to access all of it in a convinient way (via methods with the same name but in a camelCase) ...

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

If you wish to keep possibility for Orchid shortcuts name array of data as `$data` variable and use `Contentable` trait in custom class

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

And create column without render function

```php
TD::make('areWeGood'), // pass name in a exact case as method in a custom class (not `are_we_good`)
```

> Of course you need to change Table layout also in case you need custom columns

### Change actions (clear and delete)

Clear and delete actions basically clears and deletes files, shows toasts and redirects back to the specified route. If you need to do extra work you may change behavior of these actions by registering your own

> There is no event registered for exact this reason - you still have full control over process via custom Actions

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

`$logService` has access to methods to clear or delete file (as `clearFile($file)` and `deleteFile($file)`). `$file` variable contains basename of the log file (not the full path to it, eg 'laravel.log' and not '/var/www/html/storage/logs/laravel.log')

> Note - no need to redirect in action - it should NOT return any response
> Package handles redirects itself in order to prevent errors with selected file

### Other options

In a configuration file you can disable filters completely - it may be useful when you're using `stack` log channel and therefore you have one file only

Also you can disable registration of menu item and route completely but you have to re-register them on your own

## Known issues

- Works only with "laravelish" logs - if you put for example `worker.log` which created by process managers like supervisor or pm2 it will not be recognized correctly

## Roadmap

- [x] - Add option to exclude "unreadable" log files from filters
- [ ] - Add more permissions to see menu item and action buttons
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
