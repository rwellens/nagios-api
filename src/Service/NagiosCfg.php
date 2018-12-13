<?php
/**
 * NagiosCfg.php
 *
 * @date        2018-12-13
 * @file        NagiosCfg.php
 */

namespace App\Service;

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

        return $filesData;
    }
}