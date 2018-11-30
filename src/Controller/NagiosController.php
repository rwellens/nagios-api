<?php
/**
 * NagiosController.php
 *
 * @date        21/11/2018
 * @file        NagiosController.php
 */

namespace App\Controller;

use NagiosDat\DatIterator;
use NagiosDat\DatParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * NagiosController
 */
class NagiosController extends AbstractController
{

    /**
     * @Route("/api/{server}", requirements={"server"="[0-9a-zA-Z_.-]*"}, methods="GET")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDataAction(DatParser $parser, $server = null)
    {

        $datData = $parser->toArray();

        if ($server) {
            return $this->getOne($datData, $server);
        }

        return $this->json($datData);
    }


    /**
     * @param $datData
     * @param $server
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getOne($datData, $server)
    {
        if (isset($datData['machines'][$server]) || $datData['services'][$server]) {

            $return = [];

            $return['machines'][$server] = $datData['machines'][$server];
            $return['services'][$server] = $datData['services'][$server];

            return $this->json($return);
        };

        return $this->json(sprintf('no server %s', $server), 404);
    }

}