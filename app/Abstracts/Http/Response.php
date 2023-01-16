<?php

namespace App\Abstracts\Http;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Response implements Responsable
{
    protected $accepts = ['json', 'rss'];

    protected $view;

    protected $data;

    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function toJson()
    {
        return response()->json([
            'success'   => true,
            'error'     => false,
            'data'      => Arr::first($this->data),
            'message'   => '',
        ]);
    }

    public function toHtml()
    {
        return view($this->view, $this->data);
    }

    public function toResponse($request)
    {
        foreach ($this->accepts as $accept) {
            $request_method = 'expects' . Str::studly($accept);
            $response_method = 'to' . Str::studly($accept);

            if (!method_exists($request, $request_method) || !method_exists($this, $response_method)) {
                continue;
            }

            if ($request->{$request_method}()) {
                return $this->{$response_method}();
            }
        }

        return $this->toHtml();
    }
}
