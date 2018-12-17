<?php
/**
 * NagiosCfgController.php
 *
 * @date        06/12/2018
 * @file        NagiosCfgController.php
 */

namespace App\Controller;

use App\Service\NagiosCfg;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * NagiosDatController
 */
class NagiosCfgController extends AbstractController
{

    /**
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

        if(!$cfgData){
            throw $this->createNotFoundException();
        }

        return $this->json($cfgData);
    }


    /**
     * @param Request   $request
     * @param NagiosCfg $serviceNagios
     * @param string    $type
     *
     * @return JsonResponse
     */
    public function create(Request $request, NagiosCfg $serviceNagios, string $type)
    {
        $cfgData = $serviceNagios->create($type, $request->getContent());

        return $this->json($cfgData);
    }

    /**
     * @param Request     $request
     * @param NagiosCfg   $serviceNagios
     * @param string|null $type
     * @param string|null $name
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, NagiosCfg $serviceNagios, string $type, $name)
    {
        $serviceNagios->delete($type, $name);

        return $this->json('deleted', 204);
    }


}