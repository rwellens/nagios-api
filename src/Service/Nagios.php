<?php
/**
 * Nagios.php
 *
 * @date        27/11/2018
 * @file        Nagios.php
 */

namespace App\Service;

use NagiosDat\DatParser;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Validation;

/**
 * Nagios
 */
class Nagios
{
    /**
     * @var DatParser
     */
    protected $parser;
    /**
     * @var array
     */
    protected $datData = [];
    /**
     * @var string
     */
    protected $hostFileDir;


    /**
     * Nagios constructor.
     *
     * @param DatParser $parser
     * @param string    $hostFileDir
     */
    public function __construct(DatParser $parser, string $hostFileDir)
    {
        $this->parser = $parser;
        $this->hostFileDir = $hostFileDir;
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

    /**
     * @param string $server
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(string $server): bool
    {
        return unlink($this->getHostFilePath($server));
    }

    /**
     * @param      $server
     *
     * @param bool $mustExist
     *
     * @return string
     * @throws \Exception
     */
    protected function getHostFilePath($server, $mustExist = true): string
    {
        $filePath = $this->hostFileDir . '/' . $server . '_nagios2.cfg';
        if ($mustExist && !file_exists($filePath)) {
            throw new \Exception('Host does not exist!');
        }

        return $filePath;

    }


    /**
     * @param string $host
     * @param string $ip
     *
     * @return bool
     * @throws \Exception
     */
    public function add(string $host, string $ip): bool
    {
        $file = fopen($this->getHostFilePath($host, false), 'w');
        $txt = 'define host{
            use                     generic-host
            host_name               ' . $host . '
            alias                   ' . $host . '
            address                 ' . $ip . '
            }
        ';
        $writed = fwrite($file, $txt);
        fclose($file);

        return (bool) $writed;
    }


    /**
     * @param string $ip
     *
     * @return bool
     */
    public function validateIp(string $ip): bool
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($ip, [new Ip(['version' => Ip::ALL])]);

        return (0 === count($violations));
    }


}