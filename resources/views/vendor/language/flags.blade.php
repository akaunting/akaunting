@foreach (language()->allowed() as $code => $name)
    <a href="#!" class="list-group-item list-group-item-action">
        <div class="row align-items-center">
        <div class="col-auto">
            <img src="{{ asset('vendor/akaunting/language/src/Resources/assets/img/flags/'. language()->country($code) .'.png') }}" alt="{{ $name }}" width="{{ config('language.flags.width') }}" />
        </div>
            <div class="col ml--2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-sm">
                        {{ $name }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </a>
@endforeach

