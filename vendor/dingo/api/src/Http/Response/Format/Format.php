<?php

namespace Dingo\Api\Http\Response\Format;

abstract class Format
{
    /**
     * Illuminate request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Illuminate response instance.
     *
     * @var \Illuminate\Http\Response
     */
    protected $response;

    /*
     * Array of formats' options.
     *
     * @var array
     */
    protected $options;

    /**
     * Set the request instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Dingo\Api\Http\Response\Format\Format
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set the response instance.
     *
     * @param \Illuminate\Http\Response $response
     *
     * @return \Dingo\Api\Http\Response\Format\Format
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Set the formats' options.
     *
     * @param array $options
     *
     * @return \Dingo\Api\Http\Response\Format\Format
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Format an Eloquent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    abstract public function formatEloquentModel($model);

    /**
     * Format an Eloquent collection.
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     *
     * @return string
     */
    abstract public function formatEloquentCollection($collection);

    /**
     * Format an array or instance implementing Arrayable.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $content
     *
     * @return string
     */
    abstract public function formatArray($content);

    /**
     * Get the response content type.
     *
     * @return string
     */
    abstract public function getContentType();
}
