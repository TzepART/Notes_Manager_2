<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Sector;
use AppBundle\Entity\User;
use AppBundle\Form\CircleType;
use AppBundle\Model\ListNotesModel;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Route("/", name="notes_manager.circle.list", requirements={"id"="\d+"})
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
     * list of notes by circle
     * @Route("/{id}/", name="notes_manager.circle.note_list_by_circle", requirements={"id"="\d+"})
     * @Method("GET")
     * @param Circle $circle
     * @return Response
     */
    public function listByCircleAction(Circle $circle)
    {
        $listNotesModel = new ListNotesModel();
        $listNotesModel->setSelectCircle($circle);
        $this->get('app.note_notes_manager')->updateListNotesModelByUser($listNotesModel,$this->getUser());

        return $this->render('@App/Note/list.html.twig',['listNotesModel' => $listNotesModel]);
    }


    /**
     * View of circle
     * @Route("/view/{id}/", name="notes_manager.circle.view", requirements={"id"="\d+"})
     * @Method("GET")
     * @param Circle $circle
     * @return array
     * @Template()
     */
    public function viewAction(Circle $circle)
    {
        return ['circle' => $circle];
    }

    /**
     * Edit of circle
     * @Route("/edit/{id}/", name="notes_manager.circle.edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @param Circle $circle
     * @return array
     * @Template()
     */
    public function editAction(Circle $circle)
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

            return $this->redirectToRoute('notes_manager.circle.edit',['id' => $circle->getId()]);
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
        // TODO add logic for change noteLabel's angles
        $originalSectors = $this->getOriginalSectorsByCirrcle($circle);

        $circleForm = $this->getCircleForm($circle);
        $circleForm->handleRequest($request);

        $circle = $circleForm->getData();


        if ($circleForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $countSectors = $circle->getSectors()->count();

            /** @var Sector $sector */
            foreach ($originalSectors as $sector) {
                if (false === $circle->getSectors()->contains($sector)) {
                    $category = $sector->getCategory();
                    $em->remove($sector);
                    $em->remove($category);
                }
            }

            if($countSectors > 0) {

                $diffAngle = 360 / $countSectors;
                $beginAngle = 0;
                $endAngle = $diffAngle;

                /** @var Sector $sector */
                foreach ($circle->getSectors() as $index => $sector) {
                    //if sector don't exists create sector and category
                    //if exist only update angles
                    if(!$originalSectors->contains($sector)){
                        $category = new Category();
                        $category->setName($sector->getName());
                        $em->persist($category);

                        $sector->setCategory($category)
                            ->setBeginAngle($beginAngle)
                            ->setEndAngle($endAngle)
                            ->setCircle($circle);
                        $em->persist($sector);
                    }else{
                        $sector->setBeginAngle($beginAngle)
                            ->setEndAngle($endAngle);
                        $em->persist($sector);
                    }


                    $beginAngle += $diffAngle;
                    $endAngle += $diffAngle;
                }
            }else{
                //TODO actions if sectors aren't defined
            }


            $em->persist($circle);
            $em->flush();

            return $this->redirectToRoute('notes_manager.circle.edit',['id' => $circle->getId()]);
        }

        return $this->render('@App/Circle/edit.html.twig', [
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
        $user_id = (int) $request->get('user_id');
        $countLayers = (int) $request->get('countLayers');

        if (
            !empty($name = $request->get('name'))
            || $user_id > 0
            || $countLayers > 0

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
            'method' => 'POST',
            'attr' => [
                'class' => 'jumbotron col-md-6 col-md-offset-3 create_circle_form'
            ],
        ]);
    }

    /**
     * @param Circle $circle
     * @return ArrayCollection
     */
    private function getOriginalSectorsByCirrcle(Circle $circle)
    {
        $em = $this->getDoctrine()->getManager();

        $arOriginalSectors = $em->getRepository(Sector::class)->findBy(['circle' => $circle]);
        $originalSectors = new ArrayCollection();

        // Create an ArrayCollection of the current Sector objects in the database
        foreach ($arOriginalSectors as $originalSector) {
            $originalSectors->add($originalSector);
        }
        return $originalSectors;
    }


}
