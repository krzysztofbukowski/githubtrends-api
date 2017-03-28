<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 28/03/2017
 */

namespace Github;


use Zend\Http\Response;

interface ClientInterface
{

    /**
     * Sends a GET request to the given endpoint
     *
     * @param string $path
     *
     * @return Response
     */
    public function get(string $path);

    /**
     * Sends a HEAD request to the given endpoint
     *
     * @param string $path
     *
     * @return Response
     */
    public function head(string $path);
}