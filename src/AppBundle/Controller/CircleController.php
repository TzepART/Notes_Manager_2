<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Sector;
use AppBundle\Entity\User;
use AppBundle\Form\CircleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * CircleController
 * @Route("/circle")
 */
class CircleController extends Controller
{
    /**
     * list of circles
     * @Route("/", name="notes_manager.circle.list")
     * @Method("GET")
     * @return array
     * @Template()
     */
    public function listAction()
    {
        $user = $this->getUser();

        if($user instanceof User){
            $circles = $this->getDoctrine()->getRepository(Circle::class)->findBy(['user'=>$user]);
        }else{
            throw new AccessDeniedHttpException();
        }

        return ['circles' => $circles];
    }

    /**
     * View of circle
     * @Route("/view/{id}/", name="notes_manager.circle.view")
     * @Method("GET")
     * @param Circle $circle
     * @return array
     * @Template()
     */
    public function viewAction(Circle $circle)
    {
        $circleForm = $this->getCircleForm($circle);

        return ['form' => $circleForm->createView()];
    }

    /**
     * Create of circle
     * @Route("/create/", name="notes_manager.circle.create")
     * @Method("GET")
     * @return array
     * @Template()
     */
    public function createAction()
    {
        $circleForm = $this->getCircleForm();
        return [
            'form' => $circleForm->createView()
        ];
    }

    /**
     * create of circles
     * @Route("/create/", name="notes_manager.circle.add")
     * @Method("POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addCircleAction(Request $request)
    {
        $circleForm = $this->getCircleForm();

        $circleForm->handleRequest($request);

        /** @var Circle $circle */
        $circle = $circleForm->getData();

        if ($circleForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $countSectors = $circle->getSectors()->count();
            if($countSectors > 0) {

                $diffAngle = 360 / $countSectors;
                $beginAngle = 0;
                $endAngle = $diffAngle;

                /** @var Sector $sector */
                foreach ($circle->getSectors() as $index => $sector) {
                    $category = new Category();
                    $category->setName($sector->getName());
                    $em->persist($category);

                    $sector->setCategory($category)
                        ->setBeginAngle($beginAngle)
                        ->setEndAngle($endAngle)
                        ->setCircle($circle);
                    $em->persist($sector);

                    $beginAngle += $diffAngle;
                    $endAngle += $diffAngle;
                }
            }else{
                //TODO actions if sectors aren't defined
            }

            $circle->setUser($this->getUser());

            $em->persist($circle);
            $em->flush();

            return $this->redirectToRoute('notes_manager.circle.view',['id' => $circle->getId()]);
        }

        return $this->render('@App/Circle/create.html.twig', [
            'form' => $circleForm->createView()
        ]);
    }

    /**
     * create of circles
     * @Route("/update/{id}/", name="notes_manager.circle.update")
     * @Method("POST")
     * @param Request $request
     * @param Circle $circle
     * @return RedirectResponse|Response
     */
    public function editCircleAction(Request $request, Circle $circle)
    {
        $circleForm = $this->getCircleForm($circle);

        $circleForm->handleRequest($request);

        $circle = $circleForm->getData();

        if ($circleForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($circle);
            $em->flush();

            return $this->redirectToRoute('notes_manager.circle.view',['id' => $circle->getId()]);
        }

        return $this->render('@App/Circle/view.html.twig', [
            'form' => $circleForm->createView()
        ]);
    }


    /**
     * Create circle
     * @Route("/api/create/", name="notes_manager.circle.api.create")
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
    public function createApiAction(Request $request)
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


    /**
     * Full update circle
     * @Route("/api/update/{id}/", name="notes_manager.circle.api.full_update")
     * @Method("PUT")
     * @ApiDoc(
     *  description="Method for full update circle",
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
     * @param Circle $circle
     * @return JsonResponse
     */
    public function fullUpdateApiAction(Request $request, Circle $circle)
    {
        $result = [];
        $status = JsonResponse::HTTP_BAD_REQUEST;

        if (
            !empty($name = $request->get('name'))
            || !empty($user_id = $request->get('user_id'))
            || !empty($countLayers = $request->get('countLayers'))

        ) {
            if($user_id > 0){
                $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);
                if($user instanceof User){
                    $circle->setUser($user);
                }else{
                    $circle->setUser(null);
                }
            }

            if($countLayers > 0){
                $circle->setCountLayer($countLayers);
            }else{
                $circle->setCountLayer(null);
            }

            $circle->setName($name);

            $this->get('doctrine.orm.entity_manager')->persist($circle);
            $this->get('doctrine.orm.entity_manager')->flush();

            $status = JsonResponse::HTTP_OK;
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }


    /**
     * @param Circle $circle
     * @return \Symfony\Component\Form\Form
     */
    public function getCircleForm($circle = null)
    {
        if($circle instanceof Circle){
            $action = $this->container->get('router')->generate('notes_manager.circle.update',['id' => $circle->getId()]);
        }else{
            $circle = new Circle();
            $action = $this->container->get('router')->generate('notes_manager.circle.add');
        }

        return $this->container->get('form.factory')->create(CircleType::class, $circle, [
            'action' => $action,
            'method' => 'POST'
        ]);
    }


}
