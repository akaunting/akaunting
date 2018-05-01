<?php

namespace App\Models\Common;

use App\Models\Model;
use DateTime;
use DateTimeZone;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;

class Recurring extends Model
{

    protected $table = 'recurring';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'recurable_id', 'recurable_type', 'frequency', 'interval', 'started_at', 'count'];


    /**
     * Get all of the owning recurable models.
     */
    public function recurable()
    {
        return $this->morphTo();
    }

    public function schedule()
    {
        $config = new ArrayTransformerConfig();
        $config->enableLastDayOfMonthFix();

        $transformer = new ArrayTransformer();
        $transformer->setConfig($config);

        return $transformer->transform($this->rule());
    }

    public function rule()
    {
        // 0 means infinite
        $count = ($this->count == 0) ? 999 : $this->count;

        $rule = (new Rule())
            ->setStartDate(new DateTime($this->started_at, new DateTimeZone(setting('general.timezone'))))
            ->setTimezone(setting('general.timezone'))
            ->setFreq(strtoupper($this->frequency))
            ->setInterval($this->interval)
            ->setCount($count);

        return $rule;
    }
}
