<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Sector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * SectorController
 * @Route("/sector")
 */
class SectorController extends Controller
{

    /**
     * Create sector
     * @Route("/api/create/", name="notes_manager.sector.api.create")
     * @Method("POST")
     * @ApiDoc(
     *  description="Method for create sector",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="category_id", "dataType"="integer", "required"=true, "description"="Id parent's category"},
     *      {"name"="circle_id", "dataType"="integer", "required"=true, "description"="Id parent's circle"},
     *      {"name"="beginAngle", "dataType"="integer", "required"=true, "description"="Begin Angle"},
     *      {"name"="endAngle", "dataType"="integer", "required"=true, "description"="End Angle"}
     *  },
     *  section="Sector",
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createApiAction(Request $request)
    {
        $result = [];
        $status = JsonResponse::HTTP_BAD_REQUEST;

        if(
            !empty($category_id = $request->get('category_id'))
            && !empty($circle_id = $request->get('circle_id'))
            && !empty($beginAngle = $request->get('beginAngle'))
            && !empty($endAngle = $request->get('endAngle'))
        ){
            $circle = $this->getDoctrine()->getRepository(Circle::class)->find((int) $circle_id);
            $category = $this->getDoctrine()->getRepository(Category::class)->find((int) $category_id);

            if($circle instanceof Circle && $category instanceof Category){
                $sector = new Sector();
                $sector->setCategory($category)
                    ->setCircle($circle)
                    ->setBeginAngle((float) $beginAngle)
                    ->setEndAngle((float) $endAngle)
                ;
                $this->get('doctrine.orm.entity_manager')->persist($sector);
                $this->get('doctrine.orm.entity_manager')->flush();

                $status = JsonResponse::HTTP_CREATED;
            }
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }


    /**
     * Get sector
     * @Route("/api/{id}/", name="notes_manager.sector.api.get_sector")
     * @Method("GET")
     * @ApiDoc(
     *  description="Method for get sector",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  section="Sector",
     * )
     *
     * @param Sector $sector
     * @return JsonResponse
     */
    public function getApiAction(Sector $sector)
    {
        $result = [
            'id' => $sector->getId(),
            'category_id' => $sector->getCategory()->getId(),
            'circle_id' => $sector->getCircle()->getId(),
            'beginAngle' => $sector->getBeginAngle(),
            'endAngle' => $sector->getEndAngle(),
        ];

        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }


    /**
     * Full update sector
     * @Route("/api/update/{id}/", name="notes_manager.sector.api.update_full")
     * @Method("PUT")
     * @ApiDoc(
     *  description="Method for full update sector",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="category_id", "dataType"="integer", "required"=true, "description"="Id parent's category"},
     *      {"name"="circle_id", "dataType"="integer", "required"=true, "description"="Id parent's circle"},
     *      {"name"="beginAngle", "dataType"="integer", "required"=true, "description"="Begin Angle"},
     *      {"name"="endAngle", "dataType"="integer", "required"=true, "description"="End Angle"}
     *  },
     *  section="Sector",
     * )
     *
     * @param Request $request
     * @param Sector $sector
     * @return JsonResponse
     */
    public function updateFullApiAction(Request $request, Sector $sector)
    {
        $result = [];
        $category_id = $request->get('category_id');
        $circle_id = $request->get('circle_id');
        $beginAngle = $request->get('beginAngle');
        $endAngle = $request->get('endAngle');

        $category = $this->getDoctrine()->getRepository(Category::class)->find((int) $category_id);
        $circle = $this->getDoctrine()->getRepository(Circle::class)->find((int) $circle_id);

        $sector->setCategory($category)
            ->setCircle($circle)
            ->setBeginAngle((float) $beginAngle)
            ->setEndAngle((float) $endAngle);


        $this->get('doctrine.orm.entity_manager')->persist($sector);
        $this->get('doctrine.orm.entity_manager')->flush();

        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }

    /**
     * Update sector
     * @Route("/api/update/{id}/", name="notes_manager.sector.api.update")
     * @Method("PATCH")
     * @ApiDoc(
     *  description="Method for update sector",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="category_id", "dataType"="integer", "required"=true, "description"="Id parent's category"},
     *      {"name"="circle_id", "dataType"="integer", "required"=true, "description"="Id parent's circle"},
     *      {"name"="beginAngle", "dataType"="integer", "required"=true, "description"="Begin Angle"},
     *      {"name"="endAngle", "dataType"="integer", "required"=true, "description"="End Angle"}
     *  },
     *  section="Sector",
     * )
     *
     * @param Request $request
     * @param Sector $sector
     * @return JsonResponse
     */
    public function updateApiAction(Request $request, Sector $sector)
    {
        $result = [];
        $update = false;
        $category_id = $request->get('category_id');
        $circle_id = $request->get('circle_id');
        $beginAngle = (float) $request->get('beginAngle');
        $endAngle = (float) $request->get('endAngle');

        if(!empty($category_id)){
            $update = true;
            $category = $this->getDoctrine()->getRepository(Category::class)->find((int) $category_id);
            if($category instanceof Category){
                $sector->setCategory($category);
            }
        }

        if(!empty($circle_id)){
            $update = true;
            $circle = $this->getDoctrine()->getRepository(Circle::class)->find((int) $circle_id);
            if($circle instanceof Circle){
                $sector->setCircle($circle);
            }
        }

        if($beginAngle > 0){
            $update = true;
            $sector->setBeginAngle($beginAngle);
        }

        if($endAngle > 0){
            $update = true;
            $sector->setEndAngle($endAngle);
        }

        if($update){
            $this->get('doctrine.orm.entity_manager')->persist($sector);
            $this->get('doctrine.orm.entity_manager')->flush();
            $response = new JsonResponse($result,JsonResponse::HTTP_OK);
        }else{
            $response = new JsonResponse($result,JsonResponse::HTTP_NO_CONTENT);
        }

        return $response;
    }


    /**
     * Delete sector
     * @Route("/api/delete/{id}/", name="notes_manager.sector.api.delete")
     * @Method("DELETE")
     * @ApiDoc(
     *  description="Method for delete sector",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  section="Sector",
     * )
     *
     * @param Sector $sector
     * @return JsonResponse
     */
    public function deleteApiAction(Sector $sector)
    {
        $result = [];
        $this->get('doctrine.orm.entity_manager')->remove($sector);
        $this->get('doctrine.orm.entity_manager')->flush();
        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }


}
