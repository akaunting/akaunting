<?php

namespace Laratrust\Traits;

use Illuminate\Support\Facades\Config;

trait LaratrustTeamTrait
{
    use LaratrustDynamicUserRelationsCalls;

    /**
     * Morph by Many relationship between the role and the one of the possible user models.
     *
     * @param  string $relationship
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function getMorphByUserRelation($relationship)
    {
        return $this->morphedByMany(
            Config::get('laratrust.user_models')[$relationship],
            'user',
            Config::get('laratrust.tables.role_user'),
            Config::get('laratrust.foreign_keys.team'),
            Config::get('laratrust.foreign_keys.user')
        );
    }

    /**
     * Boots the team model and attaches event listener to
     * remove the many-to-many records when trying to delete.
     * Will NOT delete any records if the team model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootLaratrustTeamTrait()
    {
        static::deleting(function ($team) {
            if (method_exists($team, 'bootSoftDeletes') && !$team->forceDeleting) {
                return;
            }

            foreach (array_keys(Config::get('laratrust.user_models')) as $key) {
                $team->$key()->sync([]);
            }
        });
    }
}
