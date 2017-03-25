<?php
namespace Utils;
/**
 *
 * Class for calculating uptime of the apache server
 * It checks time when the apache pid file was created and calculates its lifetime in seconds
 *
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 24/03/2017
 */
class Uptime
{
    const HTTPD_PID = '/var/run/httpd/httpd.pid';

    /**
     * Calculates lifetime of the given file.
     * It returns the number of seconds passed since the file was created
     *
     * @param string $path Path to the file
     *
     * @return int
     */
    public function calculate(string $path = null): int {
        return time() - filemtime($path);
    }
}