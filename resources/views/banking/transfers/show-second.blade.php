@extends('layouts.admin')

@section('content')
<div class="row" style="font-size: inherit !important;">
  <div class="col-4 col-lg-2">
    From Account
    <br> <strong><span class="float-left long-texts mwpx-200 transaction-head-text">
        {{ $transfer->expense_transaction->account->name }}
      </span></strong> <br><br>
  </div>
  <div class="col-4 col-lg-6">
    To Account
    <br> <strong><span class="float-left long-texts mwpx-300 transaction-head-text">
        {{ $transfer->income_transaction->account->name }}
      </span></strong> <br><br>
  </div>
  <div class="col-4 col-lg-2">
    Amount
    <br> <strong><span class="float-left long-texts mwpx-100 transaction-head-text">
        @money($transfer->amount, $transfer->from_currency_code, true)
      </span></strong> <br><br>
  </div>
  <div class="col-4 col-lg-2">
    Date
    <br> <strong><span class="float-left long-texts mwpx-100 transaction-head-text">
        {{ $transfer->transferred_at }}
      </span></strong> <br><br>
  </div>
</div>

<div class="card show-card" style="padding: 0px 15px; border-radius: 0px; box-shadow: rgba(0, 0, 0, 0.2) 0px 4px 16px;">
  <div class="card-body show-card-body">
  <table>
      <tr>
        <td style="padding-bottom: 0; padding-top: 32px;">
          <h2 class="text-center text-uppercase" style="font-size: 16px;">
            TRANSFER DETAIL
          </h2>
        </td>
      </tr>
    </table>
    <table class="border-bottom-1">
      <tr>
        <td style="width: 70%; padding-top:0; padding-bottom:45px;">
          <table>
            <tr>
              <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                Date:
              </td>
              <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                {{ $transfer->transferred_at }}
              </td>
            </tr>
            <tr>
              <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                Payment Method:
              </td>
              <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                {{ $transfer->payment_method }}
              </td>
            </tr>
            <tr>
              <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                Reference:
              </td>
              <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                {{ $transfer->reference }}
              </td>
            </tr>
            <tr>
              <td style="width: 20%; padding-bottom:3px; font-size:14px; font-weight: bold;">
                Description:
              </td>
              <td class="border-bottom-1" style="width:80%; padding-bottom:3px; font-size:14px;">
                {{ $transfer->description }}
              </td>
            </tr>
          </table>
        </td>
        <td style="width:30%; padding-top:32px; padding-left: 25px;" valign="top">
          <table>
            <tr>
              <td style="background-color: #6da252; -webkit-print-color-adjust: exact; font-weight:bold !important; display:block;">
                <h5 class="text-muted mb-0 text-white" style="font-size: 20px; color:#ffffff; text-align:center; margin-top: 16px;">
                  Amount
                </h5>
                <p class="font-weight-bold mb-0 text-white" style="font-size: 26px; color:#ffffff; text-align:center;">
                  @money($transfer->amount, $transfer->from_currency_code, true)
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table class="border-bottom-1" style="width: 100%;">
      <tbody>
        <tr>
          <td style="width: 60%; padding-bottom: 15px;">
            <h2 class="mb-1" style="font-size: 16px;">
              Sender Account
            </h2>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">{{ $transfer->expense_transaction->account->name }}</p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">
              Account Number: {{ $transfer->from_account_id}}
            </p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">{{ $transfer->expense_transaction->account->bank_name }}</p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">
              {{ $transfer->expense_transaction->account->bank_phone }}
            </p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">{{ $transfer->expense_transaction->account->bank_address }}</p>
          </td>
        </tr>
      </tbody>
    </table>
    <table style="width: 100%; margin-top:15px;">
      <tbody>
        <tr>
          <td style="width: 60%; padding-bottom: 15px;">
            <h2 class="mb-1" style="font-size: 16px;">
              Recipient Account
            </h2>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">{{ $transfer->income_transaction->account->name }}</p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">
              Account Number: {{ $transfer->to_account_id }}
            </p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">{{ $transfer->income_transaction->account->bank_name }}</p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">
              {{ $transfer->income_transaction->account->bank_phone }}
            </p>
            <p style="margin: 0px; padding: 0px; font-size: 14px;">{{ $transfer->income_transaction->account->bank_address }}</p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="card show-card" style="padding: 0px 15px; border-radius: 0px; box-shadow: rgba(0, 0, 0, 0.2) 0px 4px 16px;">
<div class="card-body show-card-body">
<div class="form-group col-md-6">
  {!! Form::label('invoice_template', trans_choice('general.templates', 1), ['class' => 'form-control-label']) !!}

  <div class="input-group">
    <button type="button" class="btn btn-block btn-outline-primary" @click="onTemplate">
      {{ trans('settings.invoice.choose_template') }}
    </button>
  </div>
</div>
</div>
</div>

@endsection

@push('scripts_start')
<link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
@endpush