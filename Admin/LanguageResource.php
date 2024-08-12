<?php

namespace  Modules\Language\Admin;

use App\Models\Language;
use App\Models\Setting;
use App\Services\Schema;
use App\Services\TableSchema;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Language\Admin\LanguageResource\Pages\ManageLanguages;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->withoutGlobalScopes()->count();
    }

    public static function getModelLabel(): string
    {
        return __('Language');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Languages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    // Forms\Components\TextInput::make('slug')
                    //     ->required()
                    //     ->maxLength(255),
                    Schema::getStatus(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TableSchema::getName(),
                TableSchema::getStatus(),
                TableSchema::getUpdatedAt(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Settings')
                    ->slideOver()
                    ->icon('heroicon-o-cog')
                    ->modal()
                    ->fillForm(function (): array {
                        return [
                            'is_multi_lang' => setting(config('settings.is_multi_lang')),
                            'main_language' => setting(config('settings.main_language'), main_lang_id()),
                        ];
                    })
                    ->action(function (array $data): void {
                        setting([
                            config('settings.is_multi_lang') => $data['is_multi_lang'],
                            config('settings.main_language') => $data['main_language'],
                        ]);
                        Setting::updatedSettings();
                    })
                    ->form(function ($form) {
                        return $form
                            ->schema([
                                Section::make('')->schema([
                                    Forms\Components\Toggle::make('is_multi_lang'),
                                    Forms\Components\Select::make('main_language')
                                        ->options(Language::query()->pluck('name', 'id')->toArray())
                                        ->native(false)
                                        ->required(),
                                ]),
                            ]);
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLanguages::route('/'),
        ];
    }
}
