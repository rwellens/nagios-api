<?php
/**
 * NagiosDatController.php
 *
 * @date        21/11/2018
 * @file        NagiosDatController.php
 */

namespace App\Controller;

use App\Service\NagiosDat as NagiosService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * NagiosDatController
 */
class NagiosDatController extends AbstractController
{

    /**
     * @param NagiosService $serviceNagios
     * @param string        $server
     *
     * @return JsonResponse
     */
    public function getDataAction(NagiosService $serviceNagios, string $server = null)
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