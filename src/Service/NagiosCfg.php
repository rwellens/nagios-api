<?php
/**
 * NagiosCfg.php
 *
 * @date        2018-12-13
 * @file        NagiosCfg.php
 */

namespace App\Service;

use NagiosCfg\CfgIterator;
use NagiosCfg\Converter;
use Symfony\Component\Finder\Finder;

/**
 * NagiosCfg
 */
class NagiosCfg
{

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var string
     */
    protected $cfgDirectory;

    /**
     * @var Converter
     */
    protected $converter;


    /**
     * NagiosCfg constructor.
     *
     * @param Converter $converter
     * @param Finder    $finder
     * @param string    $cfgDirectory
     */
    public function __construct(Converter $converter, Finder $finder, string $cfgDirectory)
    {
        $this->finder = $finder;
        $this->cfgDirectory = $cfgDirectory;
        $this->converter = $converter;
    }


    public function fetchAll($type = null, $name = null)
    {
        $finder = $this->finder->files()->in($this->cfgDirectory)->name('*.cfg');

        $filesData = [];

        foreach ($finder as $file) {
            $filePath = $file->getRealPath();

            $fileData = $this->converter->cfgFileToArray($filePath);

            foreach ($fileData as $blockType => $block) {
                if ($type === null || $blockType === $type) {
                    foreach ($block as $blockName => $blockData) {
                        $blockData['filePath'] = $filePath;
                        $filesData[$blockType][$blockName] = $blockData;
                    }
                }
                if (isset($blockName) && $blockName === $name) {
                    return $filesData[$blockType][$blockName];
                }
            }
        }

        if (isset($name)) {
            return null;
        }

        return $filesData;
    }

    public function create(string $type, string $data)
    {
        $arrayData = $this->validAndDecodeJson($data);

        $name = $arrayData[CfgIterator::$typesName[$type]];

        $arrayCleaned[$type][$name] = $arrayData;

        $cfgString = $this->converter->arrayToCfgString($arrayCleaned);

        $filePath = $this->getFilePath($type, $name);


        file_put_contents($filePath, $cfgString);

        return $this->converter->cfgFileToArray($filePath);

    }

    private function validAndDecodeJson(string $arrayData)
    {
        return json_decode($arrayData, true);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(string $type, string $name)
    {
        $cfgData = $this->fetchAll($type, $name);

        if (!empty($cfgData) && unlink($cfgData['filePath'])) {
            return true;
        }

        throw new \Exception('Unknown cfg config');
    }

    /**
     * @param string $type
     * @param        $name
     *
     * @return string
     */
    protected function getFilePath(string $type, $name): string
    {
        $fileName = $type . '_' . $name . '.cfg';
        $directory = $this->cfgDirectory . '/' . $type . 's/';
        $filePath = $directory . $fileName;

        if (!realpath($directory)) {
            mkdir($directory, 0700, true);
        }

        return $filePath;
}
}