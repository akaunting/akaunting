<?php

namespace App\Models\Setting;

use App\Abstracts\Model;
use Illuminate\Support\Str;

class EmailTemplate extends Model
{
    protected static ?\HTMLPurifier $bodyPurifier = null;

    protected $table = 'email_templates';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'alias', 'class', 'name', 'subject', 'body', 'params', 'created_from', 'created_by'];

    public function getBodyAttribute($value): string
    {
        return static::sanitizeBody($value);
    }

    public function setBodyAttribute($value): void
    {
        $this->attributes['body'] = static::sanitizeBody($value);
    }

    public static function sanitizeBody($value): string
    {
        $body = (string) ($value ?? '');

        if ($body === '') {
            return '';
        }

        try {
            return trim(static::getBodyPurifier()->purify($body));
        } catch (\Throwable $e) {
            return trim(strip_tags($body, '<p><br><strong><b><em><i><u><a><ul><ol><li><blockquote><h1><h2><h3><h4><h5><h6><table><thead><tbody><tfoot><tr><th><td><span><div><img>'));
        }
    }

    protected static function getBodyPurifier(): \HTMLPurifier
    {
        if (static::$bodyPurifier instanceof \HTMLPurifier) {
            return static::$bodyPurifier;
        }

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $config->set('URI.DisableJavaScript', true);
        $config->set('HTML.ForbiddenElements', ['script', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'option', 'meta', 'link']);

        static::$bodyPurifier = new \HTMLPurifier($config);

        return static::$bodyPurifier;
    }

    public function getTitleAttribute()
    {
        return trans($this->name);
    }

    public function getGroupAttribute()
    {
        if (Str::startsWith($this->alias, 'invoice_')) {
            $group = 'general.invoices';
        } elseif (Str::startsWith($this->alias, 'bill_')) {
            $group = 'general.bills';
        } elseif (Str::startsWith($this->alias, 'payment_')) {
            $group = 'general.payments';
        } else {
            $group = 'general.others';
        }

        return $group;
    }

    /**
     * Scope to only include email templates of a given alias.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        return $query->where('alias', $alias);
    }

    /**
     * Scope to only include email templates of a given module alias (class).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModuleAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';

        return $query->where('class', 'like', $class . '%');
    }
}
