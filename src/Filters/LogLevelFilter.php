<?php

namespace Czernika\OrchidLogViewer\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogLevelFilter extends Filter
{
    public function __construct(
        private LaravelLogViewer $logViewer,
        private string $level = 'error',
    ) {
        $logFiles = $this->logViewer->getFiles(true);

        /**
         * Set key same as value
         */
        $this->files = array_combine($logFiles, $logFiles);

        parent::__construct();
    }

    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Level');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['level'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder;
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Select::make('level')
                ->empty()
                ->options([
                    'debug' => __('Debug'),
                    'info' => __('Info'),
                    'notice' => __('Notice'),
                    'warning' => __('Warning'),
                    'error' => __('Error'),
                    'critical' => __('Critical'),
                    'alert' => __('Alert'),
                    'emergency' => __('Emergency'),
                    'processed' => __('Processed'),
                    'failed' => __('Failed'),
                ])
                ->value($this->request->get('level'))
                ->title(__('Choose log level')),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->request->get('level');
    }
}
