<?php

namespace App\Http\ViewComposers;

use App\Traits\Plans;
use Illuminate\View\View;

class PlanLimits
{
    use Plans;

    public function compose(View $view): void
    {
        $message = '';
        $route_name = request()->route()->getName();

        $user_routes = [
            'users.index',
            'users.show',
            'users.create',
        ];

        if (in_array($route_name, $user_routes)) {
            $user_limit = $this->getUserLimitOfPlan();

            if (! $user_limit->view_status) {
                $message = $user_limit->message;
            }
        }

        $company_routes = [
            'companies.index',
            'companies.show',
            'companies.create',
        ];

        if (in_array($route_name, $company_routes)) {
            $company_limit = $this->getCompanyLimitOfPlan();

            if (! $company_limit->view_status) {
                $message = $company_limit->message;
            }
        }

        $invoice_routes = [
            'invoices.index',
            'invoices.show',
            'invoices.create',
        ];

        if (in_array($route_name, $invoice_routes)) {
            $invoice_limit = $this->getInvoiceLimitOfPlan();

            if (! $invoice_limit->view_status) {
                $message = $invoice_limit->message;
            }
        }

        if (empty($message)) {
            return;
        }

        if (! setting('apps.api_key')) {
            $view->getFactory()->startPush('header_start', view('components.alert.call')->with([
                'type'      => 'error',
                'title'     => trans('general.action_required'),
                'message'   => trans('messages.error.empty_apikey', ['url' => route('apps.api-key.create')]),
            ]));
        }

        $view->getFactory()->startPush('header_start', view('components.alert.call')->with([
            'type'      => 'warning',
            'title'     => trans('general.action_required'),
            'message'   => $message,
        ]));
    }
}
