<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Transfer as Base;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;
use File;
use Illuminate\Support\Facades\Log;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Storage;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class TransferTemplate extends Component
{
    use DateTime;
    use Transactions;

    public $transfer;

    /** @var array */
    public $payment_methods;

    /** @var bool */
    public $hideFromAccount;

    /** @var bool */
    public $hideFromAccountTitle;

    /** @var bool */
    public $hideFromAccountName;

    /** @var bool */
    public $hideFromAccountNumber;

    /** @var bool */
    public $hideFromAccountBankName;

    /** @var bool */
    public $hideFromAccountBankPhone;

    /** @var bool */
    public $hideFromAccountBankAddress;

    /** @var string */
    public $textFromAccountTitle;

    /** @var string */
    public $textFromAccountNumber;

    /** @var bool */
    public $hideToAccount;

    /** @var bool */
    public $hideToAccountTitle;

    /** @var bool */
    public $hideToAccountName;

    /** @var bool */
    public $hideToAccountNumber;

    /** @var bool */
    public $hideToAccountBankName;

    /** @var bool */
    public $hideToAccountBankPhone;

    /** @var bool */
    public $hideToAccountBankAddress;

    /** @var string */
    public $textToAccountTitle;

    /** @var string */
    public $textToAccountNumber;

    /** @var bool */
    public $hideDetails;

    /** @var bool */
    public $hideDetailTitle;

    /** @var bool */
    public $hideDetailDate;

    /** @var bool */
    public $hideDetailPaymentMethod;

    /** @var bool */
    public $hideDetailReference;

    /** @var bool */
    public $hideDetailDescription;

    /** @var bool */
    public $hideDetailAmount;

    /** @var string */
    public $textDetailTitle;

    /** @var string */
    public $textDetailDate;

    /** @var string */
    public $textDetailPaymentMethod;

    /** @var string */
    public $textDetailReference;

    /** @var string */
    public $textDetailDescription;

    /** @var string */
    public $textDetailAmount;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $transfer, array $payment_methods = [],
        bool $hideFromAccount = false, bool $hideFromAccountTitle = false, bool $hideFromAccountName = false, bool $hideFromAccountNumber = false,
        bool $hideFromAccountBankName = false, bool $hideFromAccountBankPhone = false, bool $hideFromAccountBankAddress = false,
        string $textFromAccountTitle = '', string $textFromAccountNumber = '',

        bool $hideToAccount = false, bool $hideToAccountTitle = false, bool $hideToAccountName = false, bool $hideToAccountNumber = false,
        bool $hideToAccountBankName = false, bool $hideToAccountBankPhone = false, bool $hideToAccountBankAddress = false,
        string $textToAccountTitle = '', string $textToAccountNumber = '',

        bool $hideDetails = false, bool $hideDetailTitle = false, bool $hideDetailDate = false, bool $hideDetailPaymentMethod = false,
        bool $hideDetailReference = false, bool $hideDetailDescription = false, bool $hideDetailAmount = false,
        string $textDetailTitle = '', string $textDetailDate = '', string $textDetailPaymentMethod = '', string $textDetailReference = '',
        string $textDetailDescription = '', string $textDetailAmount = ''
        ) {
        $this->transfer = $transfer;

        $this->payment_methods = ($payment_methods) ?: Modules::getPaymentMethods('all');

        // From account Hide
        $this->hideFromAccount = $hideFromAccount;
        $this->hideFromAccountTitle = $hideFromAccountTitle;
        $this->hideFromAccountName = $hideFromAccountName;
        $this->hideFromAccountNumber = $hideFromAccountNumber;
        $this->hideFromAccountBankName = $hideFromAccountBankName;
        $this->hideFromAccountBankPhone = $hideFromAccountBankPhone;
        $this->hideFromAccountBankAddress = $hideFromAccountBankAddress;

        // From account text
        $this->textFromAccountTitle = $this->getTextFromAccountTitle($textFromAccountTitle);
        $this->textFromAccountNumber = $this->getTextFromAccountNumber($textFromAccountNumber);

        // To account Hide
        $this->hideToAccount = $hideToAccount;
        $this->hideToAccountTitle = $hideToAccountTitle;
        $this->hideToAccountName = $hideToAccountName;
        $this->hideToAccountNumber = $hideToAccountNumber;
        $this->hideToAccountBankName = $hideToAccountBankName;
        $this->hideToAccountBankPhone = $hideToAccountBankPhone;
        $this->hideToAccountBankAddress = $hideToAccountBankAddress;

        // To account text
        $this->textToAccountTitle = $this->getTextToAccountTitle($textToAccountTitle);
        $this->textToAccountNumber = $this->getTextToAccountNumber($textToAccountNumber);

        // Detail Information Hide checker
        $this->hideDetails = $hideDetails;
        $this->hideDetailTitle = $hideDetailTitle;
        $this->hideDetailDate = $hideDetailDate;
        $this->hideDetailPaymentMethod = $hideDetailPaymentMethod;
        $this->hideDetailReference = $hideDetailReference;
        $this->hideDetailDescription = $hideDetailDescription;
        $this->hideDetailAmount = $hideDetailAmount;

        // Releated Information Text
        $this->textDetailTitle = $this->getTextDetailTitle($textDetailTitle);
        $this->textDetailDate = $this->getTextDetailDate($textDetailDate);
        $this->textDetailPaymentMethod = $this->getTextDetailPaymentMethod($textDetailPaymentMethod);
        $this->textDetailReference = $this->getTextDetailReference($textDetailReference);
        $this->textDetailDescription = $this->getTextDetailDescription($textDetailDescription);
        $this->textDetailAmount = $this->getTextDetailAmount($textDetailAmount);
    }

    protected function getTextFromAccountTitle($textToAccountTitle)
    {
        if (!empty($textToAccountTitle)) {
            return $textToAccountTitle;
        }

        return 'transfers.from_account';
    }

    protected function getTextFromAccountNumber($textFromAccountNumber)
    {
        if (!empty($textFromAccountNumber)) {
            return $textFromAccountNumber;
        }

        return 'accounts.number';
    }

    protected function getTextToAccountTitle($textFromAccountTitle)
    {
        if (!empty($textFromAccountTitle)) {
            return $textFromAccountTitle;
        }

        return 'transfers.to_account';
    }

    protected function getTextToAccountNumber($textToAccountNumber)
    {
        if (!empty($textToAccountNumber)) {
            return $textToAccountNumber;
        }

        return 'accounts.number';
    }

    protected function getTextDetailTitle($textDetailTitle)
    {
        if (!empty($textDetailTitle)) {
            return $textDetailTitle;
        }

        return 'transfers.details';
    }

    protected function getTextDetailDate($textDetailDate)
    {
        if (!empty($textDetailDate)) {
            return $textDetailDate;
        }

        return 'general.date';
    }

    protected function getTextDetailPaymentMethod($textDetailPaymentMethod)
    {
        if (!empty($textDetailPaymentMethod)) {
            return $textDetailPaymentMethod;
        }

        return 'general.payment_methods';
    }

    protected function getTextDetailReference($textDetailReference)
    {
        if (!empty($textDetailReference)) {
            return $textDetailReference;
        }

        return 'general.reference';
    }

    protected function getTextDetailDescription($textDetailDescription)
    {
        if (!empty($textDetailDescription)) {
            return $textDetailDescription;
        }

        return 'general.description';
    }

    protected function getTextDetailAmount($textDetailAmount)
    {
        if (!empty($textDetailAmount)) {
            return $textDetailAmount;
        }

        return 'general.amount';
    }
}
