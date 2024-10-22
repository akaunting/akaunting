<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Banking\Account as Model;

class Account extends Form
{
    public $type = 'account';

    public $path;

    public $accounts;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'account_id';
        }

        $this->path = route('modals.accounts.create');

        if (empty($this->accounts) && ! empty($this->options)) {
            $this->accounts = $this->options;
        } else if (empty($this->accounts)) {
            $this->accounts = $this->getAccounts();
        }

        $model = $this->getParentData('model');

        $account_id = old('account.id', old('account_id', null));

        if (! empty($account_id)) {
            $this->selected = $account_id;

            if (! $this->accounts->has($account_id)) {
                $account = Model::with('transactions')->find($account_id);

                $this->accounts->push($account);
            }
        }

        if (! empty($model) && ! empty($model->{$this->name})) {
            $this->selected = $model->{$this->name};

            $selected_account = $model->account;
        }

        if (! empty($selected_account) && ! $this->accounts->contains('id', $selected_account->id)) {
            $this->accounts->push($selected_account);
        }

        if ($this->selected === null) {
            $this->selected = setting('default.account');
        }

        // Share accounts with all views
        view()->share('accounts', $this->accounts);

        return view('components.form.group.account');
    }

    protected function getAccounts()
    {
        if ($this->hideCurrency) {
            return Model::with('transactions')->enabled()->orderBy('name')->get();
        }

        return Model::with('transactions')->enabled()->orderBy('name')->get();
    }
}
