<?php

namespace App\Traits;

use App\Models\Document\Document;
use Illuminate\Support\Collection;

/**
 * @deprecated
 * @see Documents
 */
trait Purchases
{
    use Documents;
    /**
     * Generate next bill number
     *
     * @deprecated
     * @see Documents::getNextDocumentNumber()
     */
    public function getNextBillNumber(): string
    {
        return $this->getNextDocumentNumber(Document::BILL_TYPE);
    }

    /**
     * Increase the next bill number
     *
     * @deprecated`1
     * @see Documents::increaseNextDocumentNumber()
     */
    public function increaseNextBillNumber(): void
    {
        $this->increaseNextDocumentNumber(Document::BILL_TYPE);
    }

    /**
     * Get a collection bill statuses
     *
     * @deprecated
     * @see Documents::getBillStatuses()
     */
    public function getBillStatuses(): Collection
    {
        return $this->getDocumentStatuses(Document::BILL_TYPE);
    }

    /**
     * @deprecated
     * @see Documents::getDocumentFileName()
     */
    public function getBillFileName(Document $bill, string $separator = '-', string $extension = 'pdf'): string
    {
        return $this->getDocumentFileName($bill, $separator, $extension);
    }

    /**
     * @deprecated
     * @see Documents::getSafeDocumentNumber()
     */
    public function getSafeBillNumber(Document $bill, string $separator = '-'): string
    {
        return $this->getSafeDocumentNumber($bill, $separator);
    }
}
