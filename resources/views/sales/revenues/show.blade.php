@extends('layouts.admin')

@section('title', trans('invoices.revenue_made'))

@section('new_button')
@can('create-sales-revenues')

<a href="{{ route('revenues.create') }}" class="btn btn-white btn-sm">{{ trans('general.add_new') }}</a>
@endcan
@endsection

@section('content')
<div id="app">
  <div class="row mt-3 w-100 mb-3" style="font-size: unset; padding: 0px 15px;">
    <div class="d-flex w-100" style="padding: 1.5rem;">
      <div class="row w-100 justify-content-between" style="font-size: unset;">
        <div class="show-head">
          {{ trans_choice('general.accounts', 1) }}
          <strong><span class="float-left">
              Account name
            </span></strong>
        </div>
        <div class="show-head">
          {{ trans_choice('general.categories', 1) }}
          <strong><span class="float-left">
              Category name
            </span></strong>
        </div>
        <div class="show-head">
          {{ trans_choice('general.customers', 1) }}
          <strong><span class="float-left">
              Customer name</span></strong>
        </div>
        <div class="show-head">
          {{ trans('general.amount') }}
          <strong><span class="float-left">
            </span></strong>
        </div>
        <div class="show-head">
          {{ trans('general.date') }}
          <strong><span class="float-left">
            </span></strong>
        </div>
      </div>
    </div>
  </div>
  <div class="card show-card">
    <div class="card-body show-card-body">
      <div class="row border-bottom-1 pt-6 pb-6">
        <div class="col-16">
          <div class="text company"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAYAAADDPmHLAAAACXBIWXMAAA7EAAAOxAGVKw4bAAANp0lEQVR4nO2dfXAd1XXAf+dJtoUkjEwMZsAYWyME6cj5IP5A0frtUNuF2DID04lpQgJuJ2NQkk5G23/qdEhJ3Ib+tZo2MzFRSj7aSantTDHBdmgqwuzzKk6AIU3RJI7ikT/iCDIosQBhG1vS6R9vpZH0vvX27b73tL8Z/2Hd3XvPe+e8u3fPPfccocowTKseaFbVVhFpUdU1InIjcAOwHNUmRRpFWDzzPlUuCzqGyCgwAryhqsMickpVT4rIIDDkOvaF4D9V6ZCwBSgGw7RiCs2AIdAOrPMU31iK8VR1zDOEVxSOA64kjWKyFOMFQcUZgGFajaq6RWA7IluA1SGLdBrVPkSOAH2uY4+FLE9BVIQBGHGrXmEbwgPAPQIl+YUXi8IY8DzKfoGjbqL8HxdlawCGaQG0AV3ATmB5qAIVzghwANgHDLiOHbI46Sk7AzBMKwZ0At1AHIiFK1HRTAIJoAc4XG7rhbIxACNu1aqwU2CPqraJlI1ovqCqiMiAwhOiHHAT9njYMkEZGIBhWijcJ8pehLaw5QkEZUCFxwQOhf1oCNUAOkzrDklOjfEw5QiRhEJ3v2O/GpYAoRiAYVpNquwVYTfMdsgsQC6r0ivCY65jjwY9eKAG0BG3ADpF2AesDHLsCuCcKl3A4f5EcI+FwFbYhmktFXhKRJ8lUn46VoroswJPGaa1NKhBA5kBDNNaB3wPaA1ivCpgEHjQdexXSj1QSQ3AMC1U2S3CPwN1pRyrCrmkyhdE6C3lm0JNqTo2TKsO+JoIXwZqSzVOFVMrwg7ghlWr2184e+Z4SfwGJZkBOuLdTQgHBdlSiv4XGor2oXy8P9Hj+1uC7wZgmNYqlCMLxqkTFMoAwnbXsc/62a2vBmCYVivw34S/RVutnAbudh170K8OfTMAw7RuR3kB4Ua/+oxIgzKMsNl17BN+dOeLARim1YryYqT8gEgawV1+zARFG4BhWqsAh2jaD5rTgFnsmqAoA+iIdzcJcixa8IWEMqDopmLeDubtCjZMqw7hYKT8EBHaEA56Ppd5MS9HkBeu9TVBds534Ah/EKQZWL5qdfuRs2eOF3z/vGYAVXYDj87n3oiS8Kink4IpeA3gbewcI/LtlxuXgE2FbiAVZADeNuXLRLt65cogsN517LfzvSHvNUBH3ELg6wiRf798eR/Kiptvaf/Bb/NcDxSyBuhEdNe8xIoIjqSOOvO+PJ+LDNNqAl4jiuSpFM4Ba/OJMcxrn94L4Axc+cuWNbL7M9v44AebqYlV1vmQiclJfvGLIXr/9Sjnzwd+XHClKnuBv851Yc4ZwAvdPk7A0buLFtXSu+8LtLRU9vbCb07+jke6/oUrVwI/B3JZoT1XyHnWn5VhWnhx+4GHbm/ccFvFKx/g1pab2LjhtjCGXizQ4zntMpLVABTuI6RDG9df3xTGsCUhxM8S93SYkYwGYMStWkk+RyIqGFH2GnEr41ovowGosDPa6KkChDYVMu7ZpDUAw7RiAntKJ1VEkAjs8Y7dp5BpBuhU1ejXXyV4ukzrHEp5Nnirxu5KPJ8/MTHJz146weho6nt3Y+NVfLT9T6itze79Hh+f4CfHf8nY2MWUtqamRjZuuJ2amsrySXi67DZM6wdzD5mkWxy0UaHHtZ/e/yLf6D2asf2Tn7iLrkeye0m/+dQP+Y+nX8zY/sjubXzqk5vnLWOIxEnqdmDmH9OZcleGv5c9g4O/y9F+Lo8+sl+Ta4wyJkZStyl/nMaIW/WQecUYUfHs9HQ8zSwDUNhG5WXjisif5Z6Op5k91Sfz8EVUM3N0PG0Ahmk1AvcELlBE0Nzj6RqYYQBe+tWyzMCZL7FY9lfXWB5byrmuyTVGuePpeDqqKzajYXsoEvnI9o9tYOnSempqYin/GhrquHfHnTn7uHfHnTQ01KXtY+nSerZ/bEMAn6TEqE7ruhams3NWfKzf+vW38V8Hv8SFi++ltF1Vt5i6uty72mb8A2zccDsXL11Oaau/aglLlizyRdZQEdlimFbMdezJWgCFZqmSs31LliwqWkl1eRpLBbPaS7N/cuoRYIQpTUQoGOCtAbxiCxELiCmdT+0FrAtRFt/44x/f4dvf/RFvvfVuSltDQx27HtrKihXLsvbx+9+f5zv/9j+8++6llLZrrmngLx/+M6699mrfZA6RdQC1hmnVe2VWwhaoaL793R9x6NmfZGy/ePE9Hv/Sp7P2se8bh3nhx/+b9Zq/6f7zeclXTqhqq2Fa9TGguVQ1doIm3S+/kHa/+qgEPJ03x1Q1Oue3QFHV1piItIQtSEQ4iEhLTFXXhC1IRDio6pqYV1QxYgEiIjfGSFbUrAoaGrLnrMjV7lcfFcQNtVRRAMiuh7Zy8eJ7Gf0Aj+zOvd81dU0mP8Cuh7YWL2j5sLwW1SaqwAcAsGLFspzv+bm4eeV1/MNXdvkjULmj2hRTqsMHEFE4ijTG5lbRjlg4iLC4IsO/I/yjqip5/N9rp3j8y//OmyNvpbQta2rk7774CTZuuD1rHz976QT/+NWnOZ/mdNF1y6/h8b//NB9YWz2uk6qaAZ451J9W+QDnR8c4+P1jOfs4+P1jaZUP8ObIWzxzqL8oGcuNmCqpsU8VysRE9rrMExMTefSR/ZpcY1QSqlyOCRp4BqOI8kDQsRgigZcrjSgTREZjwEjYckSExkgMeCNsKSJC442Yqg6HLYVf3LLq+hztK/LoI/s1ucaoJFR1uFZEToUtiF88/NBWVt58HaNpMnM2Nl7F1i0fztnHZ7s6ubX1JsbeSZ8hZPOffsgXWcsBETlVq6onqyEgFKC2toa7t36kqD4WL15UHce/8kBVT8ZExLcihBGVhYgMxoAh1cgXsNDwdD4Ucx37QjQLLDwEGXQd+8LUXkBBdWYiqgBJ6jwGoMl08BELiCmdT80AboiyRISDC148gMAQyVq0q8OTJxhUlXfeucDEpM76e01MuPrqeqrllTgHpz2dJw3AdexJI97dh8hnwpWrtFy5Ms7ffvFbvPrzk6jONgAR4Y4Pt/BPX/0rFi2qqjiZdPS5jj0JMwNCRI6EJk5ADA29zksv/5rx8QkmJiZn/Rsfn+Cll3/N0NDrYYsZBNO6nhkR1KdQ1f6AK+O5A0LyuaaS8XTcN/X/aQNwHXsMeD4MoSIC5XlP18DcmEBlf+DiRATLHB3PMgCBo0QBItXMiKfjaWYZgJuwLwAHAhUpIkgOeDqeJl1Y+D6gekJfI6aYJKnbWaQzgAEgUXJxIoImwZxqIZDGALyaMj1zHSURlYuny5659YIg88mgwyKSYi0RlYmny8Pp2tIagOvYkwpPlFSqiMBQeGLK9TuXjE5vUQ4Ae4KqHioitN/5fpa/bykAa9v8P4B57bKrubcze8r4a5f5nwV0bdsaxq8kPYwjf3ib4z/9VcpeRMlQBiTLm13Wra8O07pP4Bn/pUrlLx4w+eyjO6p+N05V+fqTz/Gf+51gxoP7+x37UKb2rKeDBQ4R0BvBrS03Vb3yITnT3dpyU1DDJTwdZiSrAbiOjUI3VM8J4gXEZYXudCv/meTc+O537Fc74lavCJ/3TbQ0jI6OMTz8h1IOUTakK23rN6r09ifsV3Ndl9eca5hWE/AasLJYwSIC4Ryw1nXsnCe/88oQ4jr2qCpdoJGLuOzRSVW68lE+FJYi5jAq35mfUBGBkdRRWqdPOgpadhumtRR4GYhSzJcng8B617HfzveGgpJEeR0/CKTmUY0Im0vAg4UoH6Cm0FHOnjk+fPMt7W+KsKPQeyNKhyqf60/YzxV637zSxInQCzw5n3sjSsKTnk4KZt6uN8O06hR9TpCKrzhaySjaJ8gO17Hn9VguyvfaEe9uEuRYUBtGEXNQBhTd1J/omXemt6IyhfYnekYRtpM8VhYRLKcRthejfPAhVazr2GeBu1GqJtlU2ZP8ru/2vvui8CVXsOvYgwibIyMIAGUYYbPr2L4k9fAtWbTr2CcQ7iJ6HJSS0wh3uY59wq8Ofc0W7lmliaZGn0YUSfI7Nf365U/he7p417HPKrpJ0b7cV0fkg6J9im7y45k/l4I9gfnw2zM/vXTL6o8eJFmRrCoqk4fIk4I83J/oKUkQQUkMAODsmePjq1a3H1HldRG2UmXVSQLgkiqfE+ErrmOPl2qQQILwDNNaB3yPaBcxXwZJbuyUPHtbICVjvA+yHuVbUVBJNnQy+R2xPgjlQ0AzwBQdcQugU4R9ROFlczmXjLricH8ieyCnn4QSh22YVpMqe0XYDQu+buFlVXpFeCzfMC4/CTUQv8O07hDoAeJhyhEiCYXufid39G6pCLVsnPfBTYX7F5TzSBlQuB8ww1Q+hDwDzMSIW7Uq7BTYo6pt1XZKSFURkQGFJ0Q54CZK92pXCGX3LRumFQM6SZ5IilP5xS0nSR6v6wEOZzqlGxZlZwBTGKYF0AZ0ATtJehUriRGSp3L3AQO5jmiFRdkawEyMuFWvsA3hAeAegbIsee8lYXweZb/A0bkJmcqRijCAmRim1QhsQXU7IlsIP8H1aZKZN4+QzMFbUdlWK84AZmKYVkyhGTAE2lHWKdoqIiWZIVR1zKuu8oqXb98VGCq353ohVLQBpMMwrXqgWVVbRaRFVdeIyI3ADcByVJsUaRSZ7YBS5bKgY14p3RHgDVUdFpFTXmW1QZLKLvtpvRD+H3yobz2Aqs6XAAAAAElFTkSuQmCC" alt="Company - Brkcvn"></div>
        </div>
        <div class="col-42">
          <div class="text company lead">
            <h2 class="mb-1">{{ setting('company.name') }}</h2>

            <p class="mb-1">{{ trans('general.tax_number') }}: <strong>{{ setting('company.tax_number') }}</strong></p>

            <p class="mb-1"><a href="tel:{{ setting('company.phone') }}">{{ setting('company.phone') }}</a></p>

            <p class="mb-1"><a href="mailto:{{ setting('company.email') }}">{{ setting('company.email') }}</a></p>
          </div>
        </div>
      </div>
      <div class="row border-bottom-1 w-100 mt-6 pb-6 d-flex flex-column">
        <h2 class="text-center text-uppercase mb-6">{{ trans('invoices.revenue_made') }}</h2>
        <div class="d-flex">
          <div class="d-flex flex-column col-lg-7 pl-0">
            <div class="d-flex mt-3">
              <div class="text company show-company col-lg-4 pl-0">
                <p>{{ trans('general.date') }}:</p>

                <p>{{ trans_choice('general.accounts', 1) }}:</p>

                <p>{{ trans_choice('general.categories', 1) }}:</p>
                <p>{{ trans_choice('general.payment_methods', 1) }}:</p>
                <p>{{ trans('general.reference') }}:</p>
                <p>{{ trans('general.description') }}:</p>
              </div>
              <div class="text company col-lg-8 pr-0 show-company show-company-value">
                <p class="border-bottom-1"><strong>12.12.2022</strong></p>

                <p class="border-bottom-1"><strong>Account One</strong></p>
                <p class="border-bottom-1"><strong>Data Category</strong></p>
                <p class="border-bottom-1"><strong>Cash</strong></p>
                <p class="border-bottom-1"><strong>Lorem Ipsum</strong></p>
                <p><strong>Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</strong></p>
              </div>
            </div>
            <div class="text company mt-5">
              <h2>{{ trans('general.paid_by') }}</h2>
              <h4 class="mb-1"><a href="">Name</a></h4>
              <p class="mb-1">Adress</p>
            </div>
          </div>
          <div class="d-flex flex-column align-items-end col-lg-5 pr-0">
            <div class="card bg-success show-card-bg-success border-0 mt-4 mb-0">
              <div class="card-body">
                <div class="row">
                  <div class="col card-amount-badge text-center mt-3">
                    <h5 class="text-muted mb-0 text-white">{{ trans('general.amount') }}</h5>
                    <span class="h2 font-weight-bold mb-0 text-white">₺0,00</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row mt-3 mb-3">
        <div class="col-100">
          <div class="text">
            <h3>{{ trans('invoices.related_revenue') }}</h3>
            <table class="table table-flush table-hover mt-3">
              <thead class="thead-light">
                <tr class="border-bottom-1">
                  <th class="item text-left"><span>{{ trans_choice('general.numbers', 1) }}</span></th>
                  <th class="quantity"> {{ trans_choice('general.customers', 1) }}</th>
                  <th class="price">{{ trans('invoices.invoice_date') }}</th>
                  <th class="total">{{ trans('general.amount') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-bottom-1">
                  <td class="item"><a href="">BILL-123</a></td>
                  <td class="quantity">Vendor name</td>
                  <td class="price">12.12.2022</td>
                  <td class="total">₺6.000,00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row mt-3 mb-3 d-none">
        <p>{{ trans('invoices.overdue_revenue') }}: <strong style="font-weight: bold;">₺6.000,00</strong></p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0 pr-0">
    <div class="accordion">
      <div class="card">
        <div class="card-header" id="accordion-transactions-header" data-toggle="collapse" data-target="#accordion-transactions-body" aria-expanded="false" aria-controls="accordion-transactions-body">
          <h4 class="mb-0">{{ trans('invoices.revenue_history') }}</h4>
        </div>
        <div id="accordion-transactions-body" class="collapse hide" aria-labelledby="accordion-transactions-header">
          <div class="table-responsive">
            <table class="table table-flush table-hover">
              <thead class="thead-light">
                <tr class="row table-head-line">
                  <th class="col-xs-6 col-sm-6">{{ trans('general.revenue_date') }}</th>
                  <th class="col-xs-6 col-sm-6">{{ trans('general.created') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr class="row align-items-center border-top-1 tr-py">
                  <td class="col-xs-6 col-sm-6">
                    12.12.2022
                  </td>
                  <td class="col-xs-6 col-sm-6">
                    Created name
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts_start')
<link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

<script src="{{ asset('public/js/sales/revenues.js?v=' . version('short')) }}"></script>
@endpush
