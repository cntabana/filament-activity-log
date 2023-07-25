<?php

namespace pxlrbt\FilamentActivityLog;

use Filament\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentActivityLogServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-activity-log';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }
}
