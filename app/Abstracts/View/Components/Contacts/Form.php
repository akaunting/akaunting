<?php

namespace App\Abstracts\View\Components\Contacts;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;

abstract class Form extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'contact';
    public const DEFAULT_TYPE = 'customer';
    public const DEFAULT_PLURAL_TYPE = 'customers';

    /* -- Main Start -- */
    public $type;

    public $contact;

    public $model;
    /* -- Main End -- */

    /* -- Content Start -- */
    public $formId;

    public $formRoute;

    public $formMethod;

    /** @var bool */
    public $hideSectionGeneral;

    /** @var bool */
    public $hideSectionBilling;

    /** @var bool */
    public $hideSectionAddress;

    /** @var bool */
    public $hideSectionPersons;

    /** @var string */
    public $textSectionGeneralTitle;

    /** @var string */
    public $textSectionGeneralDescription;

    /** @var bool */
    public $hideName;

    /** @var string */
    public $textName;

    /** @var string */
    public $classNameFromGroupClass;

    /** @var bool */
    public $hideEmail;

    /** @var string */
    public $textEmail;

    /** @var bool */
    public $hidePhone;

    /** @var string */
    public $textPhone;

    /** @var bool */
    public $hideWebsite;

    /** @var string */
    public $textWebsite;

    /** @var bool */
    public $hideReference;

    /** @var string */
    public $textReference;

    /** @var bool */
    public $hideCanLogin;

    /** @var bool */
    public $hideLogo;

    /** @var string */
    public $textSectionBillingTitle;

    /** @var string */
    public $textSectionBillingDescription;

    /** @var bool */
    public $hideTaxNumber;

    /** @var string */
    public $textTaxNumber;

    /** @var bool */
    public $hideCurrency;

    /** @var string */
    public $textSectionAddressTitle;

    /** @var string */
    public $textSectionAddressDescription;

    /** @var bool */
    public $hideAddress;

    /** @var string */
    public $textAddress;

    /** @var bool */
    public $hideCity;

    /** @var string */
    public $textCity;

    /** @var bool */
    public $hideZipCode;

    /** @var string */
    public $textZipCode;

    /** @var bool */
    public $hideState;

    /** @var string */
    public $textState;

    /** @var bool */
    public $hideCountry;

    /** @var string */
    public $textSectionPersonsTitle;

    /** @var string */
    public $textSectionPersonsDescription;

    /** @var string */
    public $cancelRoute;
    /* -- Content End -- */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $model = false, $contact = false,
        string $formId = 'contact', $formRoute = '', $formMethod = '',
        bool $hideSectionGeneral = false, bool $hideSectionBilling = false, bool $hideSectionAddress = false, bool $hideSectionPersons = false,
        string $textSectionGeneralTitle = '', string $textSectionGeneralDescription = '',
        bool $hideName = false, string $textName = '', string $classNameFromGroupClass = '',
        bool $hideEmail = false, string $textEmail = '',
        bool $hidePhone = false, string $textPhone = '',
        bool $hideWebsite = false, string $textWebsite = '',
        bool $hideReference = false, string $textReference = '',
        bool $hideCanLogin = false,
        bool $hideLogo = false,
        string $textSectionBillingTitle = '', string $textSectionBillingDescription = '',
        bool $hideTaxNumber = false, string $textTaxNumber = '',
        bool $hideCurrency = false,
        string $textSectionAddressTitle = '', string $textSectionAddressDescription = '',
        bool $hideAddress = false, string $textAddress = '',
        bool $hideCity = false, string $textCity = '',
        bool $hideZipCode = false, string $textZipCode = '',
        bool $hideState = false, string $textState = '',
        bool $hideCountry = false,
        string $textSectionPersonsTitle = '', string $textSectionPersonsDescription = '',
        string $cancelRoute = ''
    ) {
        $this->type = $type;

        $this->model = ! empty($model) ? $model : $contact;
        $this->contact = $this->model;

        /* -- Content Start -- */
        $this->formId = $formId;
        $this->formRoute = $this->getFormRoute($type, $formRoute, $this->model);
        $this->formMethod = $this->getFormMethod($type, $formMethod, $this->model);

        $this->hideSectionGeneral = $hideSectionGeneral;
        $this->hideSectionBilling = $hideSectionBilling;
        $this->hideSectionAddress = $hideSectionAddress;
        $this->hideSectionPersons = $hideSectionPersons;

        /* -- General Start -- */
        $this->textSectionGeneralTitle = $this->getTextSectionGeneralTitle($type, $textSectionGeneralTitle);
        $this->textSectionGeneralDescription = $this->getTextSectionGeneralDescription($type, $textSectionGeneralDescription);

        $this->hideName = $hideName;
        $this->textName = $this->getTextName($type, $textName);
        $this->classNameFromGroupClass = $this->getClassNameFormGroupClass($type, $classNameFromGroupClass);

        $this->hideEmail = $hideEmail;
        $this->textEmail = $this->getTextEmail($type, $textEmail);

        $this->hidePhone = $hidePhone;
        $this->textPhone = $this->getTextPhone($type, $textPhone);

        $this->hideWebsite = $hideWebsite;
        $this->textWebsite = $this->getTextWebsite($type, $textWebsite);

        $this->hideReference = $hideReference;
        $this->textReference = $this->getTextReference($type, $textReference);

        $this->hideCanLogin = $hideCanLogin;
        $this->hideLogo = $hideLogo;
        /* -- General End -- */

        /* -- Billing Start -- */
        $this->textSectionBillingTitle = $this->getTextSectionBillingTitle($type, $textSectionBillingTitle);
        $this->textSectionBillingDescription = $this->getTextSectionBillingDescription($type, $textSectionBillingDescription);

        $this->hideTaxNumber = $hideTaxNumber;
        $this->textTaxNumber = $this->getTextTaxNumber($type, $textTaxNumber);

        $this->hideCurrency = $hideCurrency;
        /* -- Billing End -- */

        /* -- Address Start -- */
        $this->textSectionAddressTitle = $this->getTextSectionAddressTitle($type, $textSectionAddressTitle);
        $this->textSectionAddressDescription = $this->getTextSectionAddressDescription($type, $textSectionAddressDescription);

        $this->hideAddress = $hideAddress;
        $this->textAddress = $this->getTextAddress($type, $textAddress);

        $this->hideCity = $hideCity;
        $this->textCity = $this->getTextCity($type, $textCity);

        $this->hideZipCode = $hideZipCode;
        $this->textZipCode = $this->getTextZipCode($type, $textZipCode);

        $this->hideState = $hideState;
        $this->textState = $this->getTextState($type, $textState);

        $this->hideState = $hideTaxNumber;
        /* -- Address End -- */

        /* -- Persons Start -- */
        $this->textSectionPersonsTitle = $this->getTextSectionPersonsTitle($type, $textSectionPersonsTitle);
        $this->textSectionPersonsDescription = $this->getTextSectionPersonsDescription($type, $textSectionPersonsDescription);
        /* -- Persons End -- */

        /* -- Buttons Start -- */
        $this->cancelRoute = $this->getCancelRoute($type, $cancelRoute);
        /* -- Buttons End -- */
        /* -- Content End -- */

        // Set Parent data
        $this->setParentData();
    }

    /* -- Content Start -- */
    /* -- General Start -- */
    protected function getTextSectionGeneralTitle($type, $textSectionGeneralTitle)
    {
        if (! empty($textSectionGeneralTitle)) {
            return $textSectionGeneralTitle;
        }

        $translation = $this->getTextFromConfig($type, 'section_general_title', 'general');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.general';
    }

    protected function getTextSectionGeneralDescription($type, $textSectionGeneralDescription)
    {
        if (! empty($textSectionGeneralDescription)) {
            return $textSectionGeneralDescription;
        }

        $translation = $this->getTextFromConfig($type, 'section_general_description', 'form_description.general');

        if (! empty($translation)) {
            return $translation;
        }

        return 'customers.form_description.general';
    }

    protected function getTextName($type, $textName)
    {
        if (! empty($textName)) {
            return $textName;
        }

        $translation = $this->getTextFromConfig($type, 'name', 'name');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.name';
    }

    protected function getClassNameFormGroupClass($type, $classNameFromGroupClass)
    {
        if (! empty($classNameFromGroupClass)) {
            return $classNameFromGroupClass;
        }

        $class = $this->getClassFromConfig($type, 'name');

        if (! empty($class)) {
            return $class;
        }

        return 'sm:col-span-6';
    }

    protected function getTextEmail($type, $textEmail)
    {
        if (! empty($textEmail)) {
            return $textEmail;
        }

        $translation = $this->getTextFromConfig($type, 'email', 'email');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.email';
    }

    protected function getTextPhone($type, $textPhone)
    {
        if (! empty($textPhone)) {
            return $textPhone;
        }

        $translation = $this->getTextFromConfig($type, 'phone', 'phone');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.phone';
    }

    protected function getTextWebsite($type, $textWebsite)
    {
        if (! empty($textWebsite)) {
            return $textWebsite;
        }

        $translation = $this->getTextFromConfig($type, 'website', 'website');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.website';
    }

    protected function getTextReference($type, $textReference)
    {
        if (! empty($textReference)) {
            return $textReference;
        }

        $translation = $this->getTextFromConfig($type, 'reference', 'reference');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.reference';
    }
    /* -- General End -- */

    /* -- Billing Start -- */
    protected function getTextSectionBillingTitle($type, $textSectionBillingTitle)
    {
        if (! empty($textSectionBillingTitle)) {
            return $textSectionBillingTitle;
        }

        $translation = $this->getTextFromConfig($type, 'section_billing_title');

        if (! empty($translation)) {
            return $translation;
        }

        return 'items.billing';
    }

    protected function getTextSectionBillingDescription($type, $textSectionBillingDescription)
    {
        if (! empty($textSectionBillingDescription)) {
            return $textSectionBillingDescription;
        }

        $translation = $this->getTextFromConfig($type, 'section_billing_description');

        if (! empty($translation)) {
            return $translation;
        }

        return 'customers.form_description.billing';
    }

    protected function getTextTaxNumber($type, $textTaxNumber)
    {
        if (! empty($textTaxNumber)) {
            return $textTaxNumber;
        }

        $translation = $this->getTextFromConfig($type, 'tax_number', 'tax_number');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.tax_number';
    }
    /* -- Billing End -- */

    /* -- Address Start -- */
    protected function getTextSectionAddressTitle($type, $textSectionAddressTitle)
    {
        if (! empty($textSectionAddressTitle)) {
            return $textSectionAddressTitle;
        }

        $translation = $this->getTextFromConfig($type, 'section_address_title', 'address');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.address';
    }

    protected function getTextSectionAddressDescription($type, $textSectionAddressDescription)
    {
        if (! empty($textSectionAddressDescription)) {
            return $textSectionAddressDescription;
        }

        $translation = $this->getTextFromConfig($type, 'section_address_description');

        if (! empty($translation)) {
            return $translation;
        }

        return 'customers.form_description.address';
    }

    /* -- Persons Start -- */
    protected function getTextSectionPersonsTitle($type, $textSectionPersonsTitle)
    {
        if (! empty($textSectionPersonsTitle)) {
            return $textSectionPersonsTitle;
        }

        $translation = $this->getTextFromConfig($type, 'section_contact_persons_title', 'contact_persons');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.contact_persons';
    }

    protected function getTextSectionPersonsDescription($type, $textSectionPersonsDescription)
    {
        if (! empty($textSectionPersonsDescription)) {
            return $textSectionPersonsDescription;
        }

        $translation = $this->getTextFromConfig($type, 'section_contact_persons_description');

        if (! empty($translation)) {
            return $translation;
        }

        return 'customers.form_description.contact_persons';
    }

    protected function getTextAddress($type, $textAddress)
    {
        if (! empty($textAddress)) {
            return $textAddress;
        }

        $translation = $this->getTextFromConfig($type, 'address', 'address');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.address';
    }

    protected function getTextCity($type, $textCity)
    {
        if (! empty($textCity)) {
            return $textCity;
        }

        $translation = $this->getTextFromConfig($type, 'city', 'cities');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.cities';
    }

    protected function getTextZipCode($type, $textZipCode)
    {
        if (! empty($textZipCode)) {
            return $textZipCode;
        }

        $translation = $this->getTextFromConfig($type, 'zip_code', 'zip_code');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.zip_code';
    }

    protected function getTextState($type, $textState)
    {
        if (! empty($textState)) {
            return $textState;
        }

        $translation = $this->getTextFromConfig($type, 'state', 'state');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.state';
    }
    /* -- Address End -- */
    /* -- Content End -- */
}
