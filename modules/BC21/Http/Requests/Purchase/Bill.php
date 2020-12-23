<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\Document\Document;

/**
 * @deprecated
 * @see Document
 */
class Bill extends Document
{
    /**
     * @deprecated
     * @see Document::rules()
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['bill_number'] = $rules['document_number'];
        $rules['billed_at'] = $rules['issued_at'];

        unset($rules['document_number'], $rules['issued_at']);

        return $rules;
    }

    /**
     * @deprecated
     * @see Document::withValidator()
     */
    public function withValidator($validator)
    {
        parent::withValidator($validator);

        if ($validator->errors()->count()) {
            $this->request->set('billed_at', $this->request->get('issued_at'));
            $this->request->remove('issued_at');
        }
    }
}
