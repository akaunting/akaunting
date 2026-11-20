<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;
use App\Models\Banking\Reconciliation as Model;

class Reconciliation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company_id = (int) $this->request->get('company_id', company_id());

        return [
            'account_id' => 'required|integer|exists:accounts,id,company_id,' . $company_id . ',deleted_at,NULL',
            'started_at' => ['required', 'date_format:Y-m-d H:i:s', 'before_or_equal:ended_at', $this->notOverlappingAReconciledOne()],
            'ended_at' => 'required|date_format:Y-m-d H:i:s|after_or_equal:started_at',
            'closing_balance' => 'required',
        ];
    }

    /**
     * The period can not overlap the one of a reconciled reconciliation of the account (Model::scopeReconciledOverlapping()).
     *
     * @return \Closure
     */
    protected function notOverlappingAReconciledOne(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $account_id = $this->request->get('account_id');
            $ended_at = $this->request->get('ended_at');

            if (empty($account_id) || empty($value) || empty($ended_at)) {
                return;
            }

            $reconciliation = $this->route('reconciliation');
            $except_id = ($reconciliation instanceof Model) ? $reconciliation->id : $reconciliation;

            try {
                $overlapping = Model::reconciledOverlapping($account_id, $value, $ended_at, $except_id)->first();
            } catch (\Exception $e) {
                // The date rules report a date that can't be read
                return;
            }

            if ($overlapping) {
                $fail(trans('reconciliations.overlapping', ['period' => $overlapping->period]));
            }
        };
    }
}
