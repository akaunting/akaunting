<akaunting-dropzone-file-upload
    text-drop-file="{{ trans('general.form.drop_file') }}"
    text-choose-file="{{ trans('general.form.choose_file') }}"

    @if (! empty($attributes['dropzone-class']))
    class="{{ $attributes['dropzone-class'] }}"
    @endif

    @if (! empty($options))
    :options={{ json_encode($options) }}
    @endif

    @if (! empty($attributes['preview']))
    :preview={{ json_encode($attributes['preview']) }}
    @endif

    @if (! empty($multiple))
    multiple
    @endif

    @if (! empty($attributes['previewClasses']))
    preview-classes="{{ $attributes['previewClasses'] }}"
    @endif

    @if (! empty($attributes['singleWidthClasses']))
    single-width-classes="{{ $attributes['singleWidthClasses'] }}"
    @endif

    @if (! empty($attributes['url']))
    url="{{ $attributes['url'] }}"
    @endif

    @if (! empty($value))
        @php
            $attachments = [];
        @endphp

        @if (is_array($value))
            @foreach($value as $attachment)
                @php
                    $attachments[] = [
                        'id' => $attachment->id,
                        'name' => $attachment->filename . '.' . $attachment->extension,
                        'path' => route('uploads.get', $attachment->id),
                        'type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'downloadPath' => route('uploads.download', $attachment->id),
                    ];
                @endphp
            @endforeach
        @elseif ($value instanceof \Plank\Mediable\Media)
            @php
                $attachments[] = [
                    'id' => $value->id,
                    'name' => $value->filename . '.' . $value->extension,
                    'path' => route('uploads.get', $value->id),
                    'type' => $value->mime_type,
                    'size' => $value->size,
                    'downloadPath' => false,
                ];
            @endphp
        @else
            @php
                $attachment = \Plank\Mediable\Media::find($value);

                if (! empty($attachment)) {
                    $attachments[] = [
                        'id' => $attachment->id,
                        'name' => $attachment->filename . '.' . $attachment->extension,
                        'path' => route('uploads.get', $attachment->id),
                        'type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'downloadPath' => false,
                    ];
                }
            @endphp
        @endif

    :attachments="{{ json_encode($attachments) }}"
    @endif

    v-model="{{ ! empty($attributes['v-model']) ? $attributes['v-model'] : (! empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name : 'form.' . $name) }}"
></akaunting-dropzone-file-upload>
