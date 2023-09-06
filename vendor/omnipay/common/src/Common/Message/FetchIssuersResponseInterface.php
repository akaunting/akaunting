<?php
/**
 * Fetch Issuers Response interface
 */

namespace Omnipay\Common\Message;

/**
 * Fetch Issuers Response interface
 *
 * This interface class defines the functionality of a response
 * that is a "fetch issuers" response.  It extends the ResponseInterface
 * interface class with some extra functions relating to the
 * specifics of a response to fetch the issuers from the gateway.
 * This happens when the gateway needs the customer to choose a
 * card issuer.
 *
 */
interface FetchIssuersResponseInterface extends ResponseInterface
{
    /**
     * Get the returned list of issuers.
     *
     * These represent banks which the user must choose between.
     *
     * @return \Omnipay\Common\Issuer[]
     */
    public function getIssuers();
}
