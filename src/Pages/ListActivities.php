<?php

namespace pxlrbt\FilamentActivityLog\Pages;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Form;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\CanPaginateRecords;
use Illuminate\Support\Collection;

abstract class ListActivities extends Page
{
    use InteractsWithRecord;
    use CanPaginateRecords;

    protected static string $view = 'filament-activity-log::pages.list-activities';

    protected static Collection $fieldLabelMap;

    public function mount($record)
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return __('filament-activity-log::activities.title', ['record' => $this->getRecordTitle()]);
    }

    public function getActivities()
    {
        return $this->paginateTableQuery(
            $this->record->activities()->latest()->getQuery()
        );
    }

    public function getFieldLabel(string $name): string
    {
        static::$fieldLabelMap ??= $this->createFieldLabelMap();

        return static::$fieldLabelMap[$name] ?? $name;
    }

    protected function createFieldLabelMap(): Collection
    {
        $form = static::getResource()::form(new Form());

        $components = collect($form->getSchema());
        $extracted = collect();

        while (($component = $components->shift()) !== null) {
            $children = $component->getChildComponents();

            if (
                $component instanceof Repeater
                || $component instanceof Builder
            ) {
                $extracted->push($component);

                continue;
            }

            if (count($children) > 0) {
                $components = $components->merge($children);

                continue;
            }

            $extracted->push($component);
        }

        return $extracted
            ->filter(fn ($field) => $field instanceof Field)
            ->mapWithKeys(fn (Field $field) => [
                $field->getName() => $field->getLabel(),
            ]);
    }

    protected function getIdentifiedTableQueryStringPropertyNameFor(string $property): string
    {
        return $property;
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 10;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50];
    }
}
