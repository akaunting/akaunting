<div class="accordion">
    <div class="card border-1 box-shadow-none">
        <div class="card-header background-none collapsed" id="accordion-recurring-and-more-header" data-toggle="collapse" data-target="#accordion-recurring-and-more-body" aria-expanded="false" aria-controls="accordion-recurring-and-more-body">
            <h4 class="mb-0">{{ trans('general.recurring_and_more') }}</h4>
        </div>

        <div id="accordion-recurring-and-more-body" class="collapse hide" aria-labelledby="accordion-recurring-and-more-header">
            <div class="card-body">
                <div class="row">
                    @stack('recurring_row_start')
                    @if (!$hideRecurring)
                        <div class="{{ $recurring_class }}">
                            {{ Form::recurring('create', null, 'col-md-12') }}
                        </div>
                    @endif
                    @stack('recurring_row_end')

                    @stack('more_row_start')
                    @if (!$hideCategory && !$hideAttachment)
                    <div class="{{ $more_class }}">
                        @if (!$hideCategory)
                            {{ Form::selectRemoteAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, setting('default.' . $categoryType . '_category'), ['required' => 'required', 'path' => route('modals.categories.create') . '?type=' . $categoryType, 'remote_action' => route('categories.index'). '?type=' . $categoryType], 'col-md-12') }}
                        @endif

                        @if (!$hideAttachment)
                            {{ Form::fileGroup('attachment', trans('general.attachment'), '', ['dropzone-class' => 'form-file'], null, 'col-md-12') }}
                        @endif
                    </div>
                    @endif
                    @stack('more_row_end')
                </div>
            </div>
        </div>
    </div>
</div>
