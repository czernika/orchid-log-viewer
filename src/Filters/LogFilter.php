<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Filters;

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class LogFilter extends Filter
{
    public function __construct(
        protected readonly LogServiceContract $logService,
    ) {
        parent::__construct();
    }

    /**
     * The displayable name of the filter.
     */
    public function name(): string
    {
        return trans('orchid-log::messages.screen.name');
    }

    /**
     * The array of matched parameters.
     */
    public function parameters(): ?array
    {
        return [
            $this->logService->levelKey(),
            $this->logService->fileKey(),
        ];
    }

    /**
     * Apply to a given Eloquent query builder.
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
        $filters = [];

        if (config('orchid-log.filters.enabled.file', true)) {
            $filters[] = Select::make($this->logService->fileKey())
                ->title('orchid-log::messages.filter.headings.file')
                ->options($this->logService->logFiles())
                ->value($this->request->get(
                    $this->logService->fileKey(),
                    config('logging.default') === 'daily' ? 1 : null,
                ));
        }

        if (config('orchid-log.filters.enabled.level', true)) {
            $filters[] = Select::make($this->logService->levelKey())
                ->title('orchid-log::messages.filter.headings.level')
                ->options($this->logLevels())
                ->empty(__('All'))
                ->value($this->request->get($this->logService->levelKey()));
        }

        return $filters;
    }

    /**
     * Hide/show the filter in the selection
     */
    public function isDisplay(): bool
    {
        return ! empty($this->display()) &&
            ! empty($this->logService->logFiles());
    }

    protected function logLevels(): array
    {
        return [
            'debug' => __('Debug'),
            'info' => __('Info'),
            'notice' => __('Notice'),
            'processed' => __('Processed'),
            'warning' => __('Warning'),
            'failed' => __('Failed'),
            'error' => __('Error'),
            'critical' => __('Critical'),
            'alert' => __('Alert'),
            'emergency' => __('Emergency'),
        ];
    }

    protected function logLevelLabel(string $level)
    {
        return $this->logLevels()[$level];
    }

    public function value(): string
    {
        $value = trans('orchid-log::messages.filter.file').': '.$this->logService->resolveSelectedFile();

        if ($this->request->query->has($this->logService->levelKey())) {
            $value .= '; '.trans('orchid-log::messages.filter.level').': '.$this->logLevelLabel(
                $this->request->query->get($this->logService->levelKey())
            );
        }

        return $value;
    }
}
