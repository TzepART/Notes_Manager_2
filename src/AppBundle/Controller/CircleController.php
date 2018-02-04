<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Circle;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * CircleController
 * @Route("/circle")
 */
class CircleController extends Controller
{
    /**
     * Create circle
     * @Route("/create/", name="notes_manager.circle.api.create")
     * @Method("GET")
     * @ApiDoc(
     *  description="Method for create circle",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of circle"},
     *      {"name"="user_id", "dataType"="integer", "required"=true, "description"="User"},
     *      {"name"="countLayers", "dataType"="integer", "required"=true, "description"="count of Layers"}
     *  },
     *  section="Circle",
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $result = [];
        $status = JsonResponse::HTTP_BAD_REQUEST;

        if (
            !empty($name = $request->get('name'))
            && !empty($user_id = $request->get('user_id'))
            && !empty($countLayers = $request->get('countLayers'))

        ) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);
            if($user instanceof User){
                $circle = new Circle();
                $circle->setName($name)
                    ->setUser($user)
                    ->setCountLayer($countLayers);

                $this->get('doctrine.orm.entity_manager')->persist($circle);
                $this->get('doctrine.orm.entity_manager')->flush();

                $status = JsonResponse::HTTP_CREATED;
            }
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }

}
