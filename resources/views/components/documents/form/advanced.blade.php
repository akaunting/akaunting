<div class="accordion">
    <div class="card">
        <div class="card-header" id="accordion-recurring-and-more-header" data-toggle="collapse" data-target="#accordion-recurring-and-more-body" aria-expanded="false" aria-controls="accordion-recurring-and-more-body">
            <h4 class="mb-0">{{ trans('general.recurring_and_more') }}</h4>
        </div>

        <div id="accordion-recurring-and-more-body" class="collapse hide" aria-labelledby="accordion-recurring-and-more-header">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        @if (!$hideRecurring)
                            {{ Form::recurring('create') }}
                        @endif
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        @if (!$hideCategory)
                            {{ Form::selectRemoteAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, setting('default.' . $category_type . '_category'), ['required' => 'required', 'path' => route('modals.categories.create') . '?type=' . $category_type, 'remote_action' => route('categories.index'). '?type=' . $category_type], 'col-md-12') }}
                        @endif

                        @if (!$hideAttachment)
                            {{ Form::fileGroup('attachment', trans('general.attachment')) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
