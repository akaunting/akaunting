<div class="table-responsive">
    <table class="table table-flush table-hover">
        <thead class="thead-light">
            <tr class="row table-head-line">
                @if (!$hideBulkAction)
                    <th class="{{ $classBulkAction }}">
                        {{ Form::bulkActionAllGroup() }}
                    </th>
                @endif

                @stack('document_number_th_start')
                @if (!$hideDocumentNumber)
                    <th class="{{ $classDocumentNumber }}">
                        @stack('document_number_th_inside_start')

                        @sortablelink('document_number', trans_choice($textDocumentNumber, 1), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])

                        @stack('document_number_th_inside_end')
                    </th>
                @endif
                @stack('document_number_th_end')

                @stack('contact_name_th_start')
                @if (!$hideContactName)
                    <th class="{{ $classContactName }}">
                        @stack('contact_name_th_inside_start')

                        @sortablelink('contact_name', trans_choice($textContactName, 1))

                        @stack('contact_name_th_inside_end')
                    </th>
                @endif
                @stack('contact_name_th_end')

                @stack('amount_th_start')
                @if (!$hideAmount)
                    <th class="{{ $classAmount }}">
                        @stack('amount_th_inside_start')

                        @sortablelink('amount', trans('general.amount'))

                        @stack('amount_th_inside_end')
                    </th>
                @endif
                @stack('amount_th_end')

                @stack('issued_at_th_start')
                @if (!$hideIssuedAt)
                    <th class="{{ $classIssuedAt }}">
                        @stack('issued_at_th_inside_start')

                        @sortablelink('issued_at', trans($textIssuedAt))

                        @stack('issued_at_th_inside_end')
                    </th>
                @endif
                @stack('issued_at_th_end')

                @stack('due_at_th_start')
                @if (!$hideDueAt)
                    <th class="{{ $classDueAt }}">
                        @stack('due_at_th_inside_start')

                        @sortablelink('due_at', trans($textDueAt))

                        @stack('due_at_th_inside_end')
                    </th>
                @endif
                @stack('due_at_th_end')

                @stack('status_th_start')
                @if (!$hideStatus)
                    <th class="{{ $classStatus }}">
                        @stack('status_th_inside_start')

                        @sortablelink('status', trans_choice('general.statuses', 1))

                        @stack('status_th_inside_end')
                    </th>
                @endif
                @stack('status_th_end')

                @if (!$hideActions)
                    <th class="{{ $classActions }}">
                        <a>{{ trans('general.actions') }}</a>
                    </th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach($documents as $item)
                @include('partials.documents.index.card-table-row')
            @endforeach
        </tbody>
    </table>
</div>
