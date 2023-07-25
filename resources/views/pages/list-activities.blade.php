<x-filament::page>
    <div class="space-y-6">
        @foreach($this->getActivities() as $activityItem)
            <div @class([
                'p-2 space-y-2 bg-white rounded-xl shadow',
                'dark:border-gray-600 dark:bg-gray-800' => config('filament.dark_mode'),
            ])>
                <div class="px-4 py-2">
                    <div class="flex justify-between">
                        <div class="flex gap-4 items-center">
                            @if ($activityItem->causer)
                                <x-filament::avatar.user :user="$activityItem->causer" class="!w-7 !h-7" />
                                <span class="font-bold">{{ $activityItem->causer?->name }}</span>
                            @endif
                        </div>
                        <div class="flex flex-col gap-0.5 text-xs text-gray-500">
                            <span>@lang('filament-activity-log::activities.events.' . $activityItem->event)</span>
                            <span>{{ $activityItem->created_at->format(__('filament-activity-log::activities.default_datetime_format')) }}</span>
                        </div>
                    </div>
                </div>

                <x-filament::hr />

                <x-filament-tables::table class="w-full overflow-hidden text-sm">
                    <x-slot:header>
                        <x-filament-tables::header-cell>
                            @lang('filament-activity-log::activities.table.field')
                        </x-filament-tables::header-cell>
                        <x-filament-tables::header-cell>
                            @lang('filament-activity-log::activities.table.old')
                        </x-filament-tables::header-cell>
                        <x-filament-tables::header-cell>
                            @lang('filament-activity-log::activities.table.new')
                        </x-filament-tables::header-cell>
                    </x-slot:header>
                        @php
                            /* @var \Spatie\Activitylog\Models\Activity $activityItem */
                            $changes = $activityItem->getChangesAttribute();
                        @endphp
                        @foreach(data_get($changes, 'attributes',[]) as $field => $change)
                            <x-filament-tables::row @class(['bg-gray-100/30' => $loop->even])>
                                <x-filament-tables::cell width="20%" class="px-4 py-2 align-top">
                                    {{ $field}}
                                    {{-- {{ $this->getFieldLabel($field) }} --}}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell width="40%" class="px-4 py-2 align-top break-all !whitespace-normal">
                                    {{ data_get($changes, "old.{$field}") }}
                                </x-filament-tables::cell>
                                <x-filament-tables::cell width="40%" class="px-4 py-2 align-top break-all !whitespace-normal">
                                    {{ data_get($changes, "attributes.{$field}") }}
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach
                </x-filament-tables::table>
            </div>
        @endforeach

        <x-filament-tables::pagination
            :paginator="$this->getActivities()"
            :page-options="$this->getTableRecordsPerPageSelectOptions()"
        />
    </div>
</x-filament::page>
