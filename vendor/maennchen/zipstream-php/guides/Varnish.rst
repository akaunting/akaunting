Usage with Varnish
=============

Serving a big zip with varnish in between can cause random stream close.
This can be solved by adding attached code to the vcl file.

To avoid the problem, add the following to your varnish config file:

.. code-block::
    sub vcl_recv {
        # Varnish can’t intercept the discussion anymore
        # helps for streaming big zips
        if (req.url ~ "\.(tar|gz|zip|7z|exe)$") {
            return (pipe);
        }
    }
    # Varnish can’t intercept the discussion anymore
    # helps for streaming big zips
    sub vcl_pipe {
        set bereq.http.connection = "close";
        return (pipe);
    }
