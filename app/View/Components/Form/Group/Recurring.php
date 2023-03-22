<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Component;
use App\Utilities\Date;

class Recurring extends Component
{
    public $type;

    public $frequency;
    public $frequencies = [];

    public $customFrequency = '';
    public $customFrequencies = [];

    public $limit = '';
    public $limits = [];

    public $startedValue = '';
    public $limitCount = '';
    public $limitDateValue = '';

    public $sendEmailShow;
    public $sendEmail;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type = '',
        $frequency = '',
        $frequencies = [],

        $customFrequency = '',
        $customFrequencies = [],

        $limit = '',
        $limits = [],

        $startedValue = '',
        $limitCount = '',
        $limitDateValue = '',

        $sendEmailShow = true,
        $sendEmail = false
    ) {
        $this->type = $this->getType($type);
        $this->frequency = $this->getFrequency($frequency);
        $this->frequencies = $this->getFrequencies($frequencies);

        $this->customFrequency = $this->getCustomFrequency($customFrequency);
        $this->customFrequencies = $this->getCustomFrequencies($customFrequencies);

        $this->limit = $this->getLimit($limit);
        $this->limits = $this->getLimits($limits);

        $this->startedValue = $this->getStartedValue($startedValue);
        $this->limitCount = $this->getLimitCount($limitCount);
        $this->limitDateValue = $this->getLimitDateValue($limitDateValue);

        $this->sendEmailShow = $this->getSendEmailShow($sendEmailShow);
        $this->sendEmail = $this->getSendEmail($sendEmail);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.group.recurring');
    }

    protected function getType($type)
    {
        if (! empty($type)) {
            return $type;
        }

        return 'invoice';
    }

    protected function getFrequency($frequency)
    {
        if (! empty($frequency)) {
            return $frequency;
        }

        return 'monthly';
    }

    protected function getFrequencies($frequencies)
    {
        if (! empty($frequencies)) {
            return $frequencies;
        }

        return [
            'daily' => trans('recurring.daily'),
            'weekly' => trans('recurring.weekly'),
            'monthly' => trans('recurring.monthly'),
            'yearly' => trans('recurring.yearly'),
            'custom' => trans('recurring.custom'),
        ];
    }

    protected function getCustomFrequency($customFrequency)
    {
        if (! empty($customFrequency)) {
            return $customFrequency;
        }

        return 'monthly';
    }

    protected function getCustomFrequencies($customFrequencies)
    {
        if (! empty($customFrequencies)) {
            return $customFrequencies;
        }

        return [
            'daily' => trans('recurring.days'),
            'weekly' => trans('recurring.weeks'),
            'monthly' => trans('recurring.months'),
            'yearly' => trans('recurring.years'),
        ];
    }

    protected function getLimit($limit)
    {
        if (! empty($limit)) {
            return $limit;
        }

        return 'never';
    }

    protected function getLimits($limits)
    {
        if (! empty($limits)) {
            return $limits;
        }

        return [
            'after' => trans('recurring.after'),
            'on' => trans('recurring.on'),
            'never' => trans('recurring.never'),
        ];
    }

    protected function getStartedValue($startedValue)
    {
        if (! empty($startedValue)) {
            return $startedValue;
        }

        return Date::now()->toDateString();
    }

    protected function getLimitCount($limitCount)
    {
        if (! empty($limitCount)) {
            return $limitCount;
        }

        return 0;
    }

    protected function getLimitDateValue($limitDateValue)
    {
        if (! empty($limitDateValue)) {
            return $limitDateValue;
        }

        return Date::now()->toDateString();
    }

    protected function getSendEmailShow($sendEmailShow)
    {
        if (! empty($sendEmailShow)) {
            return $sendEmailShow;
        }

        return false;
    }

    protected function getSendEmail($sendEmail)
    {
        if (! empty($sendEmail)) {
            return $sendEmail;
        }

        return false;
    }
}
