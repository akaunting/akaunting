<?php namespace Bkwld\Cloner;

// Deps
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;

/**
 * Core class that traverses a model's relationships and replicates model
 * attributes
 */
class Cloner {

	/**
	 * @var AttachmentAdapter
	 */
	private $attachment;

	/**
	 * @var Events
	 */
	private $events;

	/**
	 * @var string
	 */
	private $write_connection;

	/**
	 * DI
	 *
	 * @param AttachmentAdapter $attachment
	 */
	public function __construct(AttachmentAdapter $attachment = null,
		Events $events = null) {
		$this->attachment = $attachment;
		$this->events = $events;
	}

	/**
	 * Clone a model instance and all of it's files and relations
	 *
	 * @param  Illuminate\Database\Eloquent\Model $model
	 * @param  Illuminate\Database\Eloquent\Relations\Relation $relation
	 * @return Illuminate\Database\Eloquent\Model The new model instance
	 */
	public function duplicate($model, $relation = null) {
		$clone = $this->cloneModel($model);

		$this->dispatchOnCloningEvent($clone, $relation, $model);

		if ($relation) {
            if (!is_a($relation, 'Illuminate\Database\Eloquent\Relations\BelongsTo')) {
                $relation->save($clone);
            }
		} else {
			$clone->save();
		}

		$this->duplicateAttachments($model, $clone);
		$clone->save();

		$this->cloneRelations($model, $clone);
		
		$this->dispatchOnClonedEvent($clone, $model);
		
		return $clone;
	}

	/**
	 * Clone a model instance to a specific database connection
	 *
	 * @param  Illuminate\Database\Eloquent\Model $model
	 * @param  string $connection A Laravel database connection
	 * @return Illuminate\Database\Eloquent\Model The new model instance
	 */
	public function duplicateTo($model, $connection) {
		$this->write_connection = $connection; // Store the write database connection
		$clone = $this->duplicate($model); // Do a normal duplicate
		$this->write_connection = null; // Null out the connection for next run
		return $clone;
	}

	/**
	 * Create duplicate of the model
	 *
	 * @param  Illuminate\Database\Eloquent\Model $model
	 * @return Illuminate\Database\Eloquent\Model The new model instance
	 */
	protected function cloneModel($model) {
		$exempt = method_exists($model, 'getCloneExemptAttributes') ?
			$model->getCloneExemptAttributes() : null;
		$clone = $model->replicate($exempt);
		if ($this->write_connection) $clone->setConnection($this->write_connection);
		return $clone;
	}

	/**
	 * Duplicate all attachments, given them a new name, and update the attribute
	 * value
	 *
     * @param  Illuminate\Database\Eloquent\Model $model
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @return void
	 */
	protected function duplicateAttachments($model, $clone) {
		if (!$this->attachment || !method_exists($clone, 'getCloneableFileAttributes')) return;
		foreach($clone->getCloneableFileAttributes() as $attribute) {
			if (!$original = $model->getAttribute($attribute)) continue;
			$clone->setAttribute($attribute, $this->attachment->duplicate($original, $clone));
		}
	}

	/**
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @param  Illuminate\Database\Eloquent\Relations\Relation $relation
	 * @param  Illuminate\Database\Eloquent\Model $src The orginal model
	 * @param  boolean $child
	 * @return void
	 */
	protected function dispatchOnCloningEvent($clone, $relation = null, $src = null, $child = null)
	{
		// Set the child flag
		if ($relation) $child = true;

		// Notify listeners via callback or event
		if (method_exists($clone, 'onCloning')) $clone->onCloning($src, $child);
		$this->events->dispatch('cloner::cloning: '.get_class($src), [$clone, $src]);
	}

    /**
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @param  Illuminate\Database\Eloquent\Model $src The orginal model
	 * @return void
	 */
	protected function dispatchOnClonedEvent($clone, $src)
	{
		// Notify listeners via callback or event
		if (method_exists($clone, 'onCloned')) $clone->onCloned($src);
		$this->events->dispatch('cloner::cloned: '.get_class($src), [$clone, $src]);
	}

	/**
	 * Loop through relations and clone or re-attach them
	 *
	 * @param  Illuminate\Database\Eloquent\Model $model
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @return void
	 */
	protected function cloneRelations($model, $clone) {
		if (!method_exists($model, 'getCloneableRelations')) return;
		foreach($model->getCloneableRelations() as $relation_name) {
			$this->duplicateRelation($model, $relation_name, $clone);
		}
	}

	/**
	 * Duplicate relationships to the clone
	 *
	 * @param  Illuminate\Database\Eloquent\Model $model
	 * @param  string $relation_name
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @return void
	 */
	protected function duplicateRelation($model, $relation_name, $clone) {
		$relation = call_user_func([$model, $relation_name]);
		if (is_a($relation, 'Illuminate\Database\Eloquent\Relations\BelongsToMany')) {
			$this->duplicatePivotedRelation($relation, $relation_name, $clone);
		} else $this->duplicateDirectRelation($relation, $relation_name, $clone);
	}

	/**
	 * Duplicate a many-to-many style relation where we are just attaching the
	 * relation to the dupe
	 *
	 * @param  Illuminate\Database\Eloquent\Relations\Relation $relation
	 * @param  string $relation_name
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @return void
	 */
	protected function duplicatePivotedRelation($relation, $relation_name, $clone) {

		// If duplicating between databases, do not duplicate relations. The related
		// instance may not exist in the other database or could have a different
		// primary key.
		if ($this->write_connection) return;

		// Loop trough current relations and attach to clone
		$relation->get()->each(function ($foreign) use ($clone, $relation_name) {
			$pivot_attributes = Arr::except($foreign->pivot->getAttributes(), [
				$foreign->pivot->getRelatedKey(),
				$foreign->pivot->getForeignKey(),
				$foreign->pivot->getCreatedAtColumn(),
				$foreign->pivot->getUpdatedAtColumn()
			]);

	        if ($foreign->pivot->incrementing) {
				unset($pivot_attributes[$foreign->pivot->getKeyName()]);
	        }

			$clone->$relation_name()->attach($foreign, $pivot_attributes);
		});
	}

	/**
	 * Duplicate a one-to-many style relation where the foreign model is ALSO
	 * cloned and then associated
	 *
	 * @param  Illuminate\Database\Eloquent\Relations\Relation $relation
	 * @param  string $relation_name
	 * @param  Illuminate\Database\Eloquent\Model $clone
	 * @return void
	 */
	protected function duplicateDirectRelation($relation, $relation_name, $clone) {
		$relation->get()->each(function($foreign) use ($clone, $relation_name) {
			$cloned_relation = $this->duplicate($foreign, $clone->$relation_name());
            if (is_a($clone->$relation_name(), 'Illuminate\Database\Eloquent\Relations\BelongsTo')) {
                $clone->$relation_name()->associate($cloned_relation);
                $clone->save();
            }
        });
	}
}
