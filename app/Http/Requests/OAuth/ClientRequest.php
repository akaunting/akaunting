<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'         => 'required|string|max:191',
            'redirect'     => 'required|string',
            'confidential' => 'nullable|boolean',
        ];

        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            $rules['redirect'] = 'nullable|string';
        }

        return $rules;
    }

    /**
     * Get custom error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => trans('validation.required', ['attribute' => trans('oauth.client_name')]),
            'redirect.required' => trans('validation.required', ['attribute' => trans('oauth.redirect_url')]),
            'redirect.url' => trans('validation.url', ['attribute' => trans('oauth.redirect_url')]),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'         => trans('oauth.client_name'),
            'redirect'     => trans('oauth.redirect_url'),
            'confidential' => trans('oauth.confidential_client'),
        ];
    }

    /**
     * Prepare the data for validation.
     * Normalises the redirect field to a single string:
     *   - JSON array  → json_encode (multi-URL)
     *   - comma/space separated → json_encode (multi-URL)
     *   - single URL  → as-is
     * Also strips any invalid (non-URL) entries silently.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $raw = $this->input('redirect', '');

        if (empty($raw)) {
            return;
        }

        $urls = $this->parseRedirectUrls($raw);

        $this->merge([
            'redirect' => count($urls) > 1 ? json_encode($urls) : ($urls[0] ?? ''),
        ]);
    }

    /**
     * Parse redirect URLs from a raw string input.
     * Supports JSON arrays, comma-separated, newline-separated, or single URLs.
     *
     * @param  string  $input
     * @return array<string>
     */
    public function parseRedirectUrls(string $input): array
    {
        // Try JSON array first
        $decoded = json_decode($input, true);

        $candidates = is_array($decoded)
            ? $decoded
            : preg_split('/[\s,\n\r]+/', $input, -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_filter(
            array_map('trim', $candidates),
            fn (string $url) => filter_var($url, FILTER_VALIDATE_URL) !== false,
        ));
    }
}
