<?php
/**
 * NagiosCfgController.php
 *
 * @date        06/12/2018
 * @file        NagiosCfgController.php
 */

namespace App\Controller;

use App\Service\NagiosCfg;
use NagiosCfg\Converter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * NagiosDatController
 */
class NagiosCfgController extends AbstractController
{

    /**
     * @Route("/api/cfg/{type}/{name}",
     *     requirements=
     *     {
     *          "type"="host|service|contact|timeperiod|hostgroup|servicegroup|contactgroup"
     *      },
     *     name="get_one",
     *     methods="GET")
     *
     * @param NagiosCfg   $serviceNagios
     * @param string|null $type
     * @param string|null $name
     *
     * @return JsonResponse
     *
     */
    public function fetch(NagiosCfg $serviceNagios, string $type = null, string $name = null)
    {
        $cfgData = $serviceNagios->fetchAll($type, $name);

        return $this->json($cfgData);
    }


}