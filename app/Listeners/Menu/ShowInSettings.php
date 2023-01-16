<?php

namespace App\Listeners\Menu;

use App\Events\Menu\SettingsCreated as Event;
use App\Traits\Permissions;

class ShowInSettings
{
    use Permissions;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $menu = $event->menu;

        $title = trim(trans_choice('general.companies', 1));
        if ($this->canAccessMenuItem($title, 'read-settings-company')) {
            $menu->route('settings.company.edit', $title, [], 10, ['icon' => 'business', 'search_keywords' => trans('settings.company.search_keywords')]);
        }

        $title = trim(trans_choice('general.localisations', 1));
        if ($this->canAccessMenuItem($title, 'read-settings-localisation')) {
            $menu->route('settings.localisation.edit', $title, [], 20, ['icon' => 'flag', 'search_keywords' => trans('settings.localisation.search_keywords')]);
        }

        $title = trim(trans_choice('general.invoices', 1));
        if ($this->canAccessMenuItem($title, 'read-settings-invoice')) {
            $menu->route('settings.invoice.edit', $title, [], 30, ['icon' => 'description', 'search_keywords' => trans('settings.invoice.search_keywords')]);
        }

        $title = trim(trans_choice('general.defaults', 1));
        if ($this->canAccessMenuItem($title, 'read-settings-defaults')) {
            $menu->route('settings.default.edit', $title, [], 40, ['icon' => 'tune', 'search_keywords' => trans('settings.default.search_keywords')]);
        }

        $title = trim(trans_choice('general.email_services', 1));
        if ($this->canAccessMenuItem($title, 'read-settings-email')) {
            $menu->route('settings.email.edit', $title, [], 50, ['icon' => 'email', 'search_keywords' => trans('settings.email_services.search_keywords')]);
        }

        $title = trim(trans_choice('general.email_templates', 2));
        if ($this->canAccessMenuItem($title, 'read-settings-email-templates')) {
            $menu->route('settings.email-templates.edit', $title, [], 60, ['icon' => 'attach_email', 'search_keywords' => trans('settings.email.templates.search_keywords')]);
        }

        $title = trim(trans('settings.scheduling.name'));
        if ($this->canAccessMenuItem($title, 'read-settings-schedule')) {
            $menu->route('settings.schedule.edit', $title, [], 70, ['icon' => 'alarm', 'search_keywords' => trans('settings.scheduling.search_keywords')]);
        }

        $title = trim(trans_choice('general.categories', 2));
        if ($this->canAccessMenuItem($title, 'read-settings-categories')) {
            $menu->route('categories.index', $title, [], 80, ['icon' => 'folder', 'search_keywords' => trans('settings.categories.search_keywords')]);
        }

        $title = trim(trans_choice('general.currencies', 2));
        if ($this->canAccessMenuItem($title, 'read-settings-currencies')) {
            $menu->route('currencies.index', $title, [], 90, ['icon' => 'attach_money', 'search_keywords' => trans('settings.currencies.search_keywords')]);
        }

        $title = trim(trans_choice('general.taxes', 2));
        if ($this->canAccessMenuItem($title, 'read-settings-taxes')) {
            $menu->route('taxes.index', $title, [], 100, ['icon' => 'percent', 'search_keywords' => trans('settings.taxes.search_keywords')]);
        }
    }
}
