Usage with nginx
=============

If you are using nginx as a webserver, it will try to buffer the response.
So you'll want to disable this with a custom header:

.. code-block:: php
    header('X-Accel-Buffering: no');
    # or with the Response class from Symfony
    $response->headers->set('X-Accel-Buffering', 'no');

Alternatively, you can tweak the
`fastcgi cache parameters <https://nginx.org/en/docs/http/ngx_http_fastcgi_module.html#fastcgi_buffers>`_
within nginx config.

See `original issue <https://github.com/maennchen/ZipStream-PHP/issues/77>`_.