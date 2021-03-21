<?php

namespace App\Traits;

use App\Models\Document\Document;
use Illuminate\Support\Collection;

/**
 * @deprecated
 * @see Documents
 */
trait Sales
{
    use Documents;
    /**
     * Generate next invoice number
     *
     * @deprecated
     * @see Documents::getNextDocumentNumber()
     */
    public function getNextInvoiceNumber(): string
    {
        return $this->getNextDocumentNumber(Document::INVOICE_TYPE);
    }

    /**
     * Increase the next invoice number
     *
     * @deprecated
     * @see Documents::increaseNextDocumentNumber()
     */
    public function increaseNextInvoiceNumber(): void
    {
        $this->increaseNextDocumentNumber(Document::INVOICE_TYPE);
    }

    /**
     * Get a collection invoice statuses
     *
     * @deprecated
     * @see Documents::getInvoiceStatuses()
     */
    public function getInvoiceStatuses(): Collection
    {
        return $this->getDocumentStatuses(Document::INVOICE_TYPE);
    }

    /**
     * @deprecated
     * @see Documents::getDocumentFileName()
     */
    public function getInvoiceFileName(Document $invoice, string $separator = '-', string $extension = 'pdf'): string
    {
        return $this->getDocumentFileName($invoice, $separator, $extension);
    }

    /**
     * @deprecated
     * @see Documents::getSafeDocumentNumber()
     */
    public function getSafeInvoiceNumber(Document $invoice, string $separator = '-'): string
    {
        return $this->getSafeDocumentNumber($invoice, $separator);
    }
}
