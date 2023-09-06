<?php

namespace Http\Message;

/**
 * Factory for PSR-7 Request and Response.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @deprecated since version 1.1, use Psr\Http\Message\RequestFactoryInterface and Psr\Http\Message\ResponseFactoryInterface instead.
 */
interface MessageFactory extends RequestFactory, ResponseFactory
{
}
