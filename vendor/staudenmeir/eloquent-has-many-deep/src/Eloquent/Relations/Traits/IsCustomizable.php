<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits;

trait IsCustomizable
{
    /**
     * The custom callbacks to run at the end of the get() method.
     *
     * @var callable[]
     */
    protected array $postGetCallbacks = [];

    /**
     * The custom through key callback for an eager load of the relation.
     *
     * @var callable
     */
    protected $customThroughKeyCallback = null;

    /**
     * The custom constraints callback for an eager load of the relation.
     *
     * @var callable
     */
    protected $customEagerConstraintsCallback = null;

    /**
     * The custom matching callbacks for the eagerly loaded results.
     *
     * @var callable[]
     */
    protected array $customEagerMatchingCallbacks = [];

    /**
     * Set custom callbacks to run at the end of the get() method.
     *
     * @param callable[] $callbacks
     * @return $this
     */
    public function withPostGetCallbacks(array $callbacks): static
    {
        $this->postGetCallbacks = array_merge($this->postGetCallbacks, $callbacks);

        return $this;
    }

    /**
     * Set the custom through key callback for an eager load of the relation.
     *
     * @param callable $callback
     * @return $this
     */
    public function withCustomThroughKeyCallback(callable $callback): static
    {
        $this->customThroughKeyCallback = $callback;

        return $this;
    }

    /**
     * Set the custom constraints callback for an eager load of the relation.
     *
     * @param callable $callback
     * @return $this
     */
    public function withCustomEagerConstraintsCallback(callable $callback): static
    {
        $this->customEagerConstraintsCallback = $callback;

        return $this;
    }

    /**
     * Set a custom matching callback for the eagerly loaded results.
     *
     * @param callable $callback
     * @return $this
     */
    public function withCustomEagerMatchingCallback(callable $callback): static
    {
        $this->customEagerMatchingCallbacks[] = $callback;

        return $this;
    }
}
