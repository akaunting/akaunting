<?php

namespace Laravel\Sanctum;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class NewAccessToken implements Arrayable, Jsonable
{
    /**
     * The access token instance.
     *
     * @var \Laravel\Sanctum\PersonalAccessToken
     */
    public $accessToken;

    /**
     * The plain text version of the token.
     *
     * @var string
     */
    public $plainTextToken;

    /**
     * Create a new access token result.
     *
     * @param  \Laravel\Sanctum\PersonalAccessToken  $accessToken
     * @param  string  $plainTextToken
     * @return void
     */
    public function __construct(PersonalAccessToken $accessToken, string $plainTextToken)
    {
        $this->accessToken = $accessToken;
        $this->plainTextToken = $plainTextToken;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'accessToken' => $this->accessToken,
            'plainTextToken' => $this->plainTextToken,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
