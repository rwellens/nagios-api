<?php
/**
 * NagiosController.php
 *
 * @date        21/11/2018
 * @file        NagiosController.php
 */

namespace App\Controller;

use App\Service\Nagios as NagiosService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * NagiosController
 */
class NagiosController extends AbstractController
{

    /**
     * @param NagiosService $serviceNagios
     * @param string        $server
     *
     * @Route("/api/{server}", requirements={"server"="[0-9a-zA-Z_.-]*"}, methods="GET")
     *
     * @return JsonResponse
     */
    public function getDataAction(NagiosService $serviceNagios, $server = null)
    {
        $datData = $serviceNagios->getAll();

        if ($server) {

            $serverData = $serviceNagios->getOne($server);

            if (!$serverData) {
                return $this->json(sprintf('no server %s', $server), 404);
            }

            return $this->json($serverData);
        }

        return $this->json($datData);
    }


}