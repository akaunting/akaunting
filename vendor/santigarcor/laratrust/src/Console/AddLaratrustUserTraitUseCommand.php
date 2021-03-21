<?php

namespace Laratrust\Console;

use Traitor\Traitor;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Laratrust\Traits\LaratrustUserTrait;

class AddLaratrustUserTraitUseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laratrust:add-trait';

    /**
     * Trait added to User model
     *
     * @var string
     */
    protected $targetTrait = LaratrustUserTrait::class;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $models = $this->getUserModels() ;

        foreach ($models as $model) {
            if (!class_exists($model)) {
                $this->error("Class $model does not exist.");
                return;
            }

            if ($this->alreadyUsesLaratrustUserTrait($model)) {
                $this->error("Class $model already uses LaratrustUserTrait.");
                continue;
            }

            Traitor::addTrait($this->targetTrait)->toClass($model);
        }

        $this->info("LaratrustUserTrait added successfully to {$models->implode(', ')}");
    }

    /**
     * Check if the class already uses LaratrustUserTrait.
     *
     * @param  string  $model
     * @return bool
     */
    protected function alreadyUsesLaratrustUserTrait($model)
    {
        return in_array(LaratrustUserTrait::class, class_uses($model));
    }

    /**
     * Get the description of which clases the LaratrustUserTrait was added.
     *
     * @return string
     */
    public function getDescription()
    {
        return "Add LaratrustUserTrait to {$this->getUserModels()->implode(', ')} class";
    }

    /**
     * Return the User models array.
     *
     * @return array
     */
    protected function getUserModels()
    {
        return new Collection(Config::get('laratrust.user_models', []));
    }
}
