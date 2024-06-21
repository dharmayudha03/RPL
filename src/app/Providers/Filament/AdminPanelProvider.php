<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CetakanNaikResource;
use App\Filament\Resources\CetakanResource\Widgets\CetakanStatsOverview;
use App\Filament\Resources\CetakanResource\Widgets\StatsOverview;
use App\Filament\Resources\CetakanTurunResource;
use App\Filament\Resources\MesinResource\Widgets\MesinStatsOverview;
use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Filament\Navigation\NavigationBuilder;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Resources\CetakanResource;
use App\Filament\Resources\MesinResource;
use App\Filament\Resources\SandblastingResource;
use App\Models\User;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarFullyCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Inter')
            ->brandName('IRC INOAC INDONESIA')
            ->favicon(asset('images/log-irc-inoac.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->darkMode(true)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //
                MesinStatsOverview::class,
                CetakanStatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Maintenance')
                        ->items([
                            ...CetakanNaikResource::getNavigationItems(),
                            ...CetakanTurunResource::getNavigationItems(),
                            ...SandblastingResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('List Cetakan dan Mesin')
                        ->items([
                            ...MesinResource::getNavigationItems(),
                            ...CetakanResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Settings')
                        ->items([
                            ...UserResource::getNavigationItems(),
                            NavigationItem::make('Roles')
                                ->icon('heroicon-o-lock-closed')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.roles.index',
                                    'filament.admin.resources.roles.create',
                                    'filament.admin.resources.roles.view',
                                    'filament.admin.resources.roles.edit',
                                ]))
                                ->url(fn (): string => '/admin/roles'),
                            NavigationItem::make('Permissions')
                                ->icon('heroicon-o-lock-closed')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.permissions.index',
                                    'filament.admin.resources.permissions.create',
                                    'filament.admin.resources.permissions.view',
                                    'filament.admin.resources.permissions.edit',
                                ]))
                                ->url(fn (): string => '/admin/permissions'),
                        ]),
                ]);
            })
            ->databaseNotifications();;
    }

    public function boot(User $user) : void
    {
        Filament::serving(function() use ($user) {
            if ($user->hasRole('admin')) {
                Filament::registerUserMenuItems([
                    UserMenuItem::make()
                        ->label('Settings')
                        ->url(UserResource::getUrl())
                        ->icon('heroicon-s-cog'),
                ]);
            }
        });
    }
}
