<?php

namespace Czernika\OrchidLogViewer\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogFileFilter extends Filter
{
    public function __construct(
        private LaravelLogViewer $logViewer,
        private array $files = [],
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
        return __('File');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['file'];
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
            Select::make('file')
                ->empty()
                ->options($this->files)
                ->value($this->request->get('file'))
                ->title(__('Choose log file')),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->request->get('file');
    }
}
