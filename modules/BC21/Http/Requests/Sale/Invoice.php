<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\Document\Document;

/**
 * @deprecated
 * @see Document
 */
class Invoice extends Document
{
    /**
     * @deprecated
     * @see Document::rules()
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['invoice_number'] = $rules['document_number'];
        $rules['invoiced_at'] = $rules['issued_at'];

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
            $this->request->set('invoiced_at', $this->request->get('issued_at'));
            $this->request->remove('issued_at');
        }
    }
}
