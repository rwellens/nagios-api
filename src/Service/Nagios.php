<?php
/**
 * Nagios.php
 *
 * @date        27/11/2018
 * @file        Nagios.php
 */

namespace App\Service;

use NagiosDat\DatParser;

/**
 * Nagios
 */
class Nagios
{
    /**
     * @var DatParser
     */
    protected $parser;

    protected $datData = [];

    /**
     * Nagios constructor.
     *
     * @param DatParser $parser
     */
    public function __construct(DatParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        if (empty($this->datData)) {
            $this->datData = $this->parser->toArray();
        }

        return $this->datData;
    }


    /**
     * @param $server
     *
     * @return array
     */
    public function getOne(string $server): array
    {
        $datData = $this->getAll();

        $return = [];
        if (isset($datData['machines'][$server]) || isset($datData['services'][$server])) {

            $return['machines'][$server] = $datData['machines'][$server];
            $return['services'][$server] = $datData['services'][$server];

        }

        return $return;
    }


}