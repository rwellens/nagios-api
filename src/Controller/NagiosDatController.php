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
     * @Route("/api/dat/dat/{server}", requirements={"server"="[0-9a-zA-Z_.-]*"}, methods="GET")
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


    /**
     * @param NagiosService $serviceNagios
     * @param string        $server
     *
     * @Route("/api/dat/{server}", requirements={"server"="[0-9a-zA-Z_.-]*"}, methods="DELETE")
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteServer(NagiosService $serviceNagios, string $server)
    {
        if ($serviceNagios->delete($server)) {

            return $this->json(['action' => 'deleted', 'name' => $server], 204);
        };

        return $this->json(['Host does not exist!']);
    }

    /**
     * @param Request       $request
     * @param NagiosService $serviceNagios
     *
     * @return JsonResponse
     * @Route("/api/dat", methods="POST")
     * @throws \Exception
     */
    public function addServer(Request $request, NagiosService $serviceNagios)
    {
        $host = $request->get('host');
        $ip = $request->get('ip');

        if (empty($host) || !$serviceNagios->validateIp($ip)) {
            return $this->json(['Invalid data'], 400);
        }

        if ($serviceNagios->add($host, $ip)) {

            return $this->json(['action' => 'created', 'name' => $host, 'ip' => $ip], 201);
        };


        return $this->json(['Host not added'], 500);

    }


}