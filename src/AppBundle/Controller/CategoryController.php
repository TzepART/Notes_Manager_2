<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Sector;
use AppBundle\Entity\User;
use AppBundle\Form\CategoryType;
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
 * CategoryController
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * list of categories
     * @Route("/", name="notes_manager.category.list")
     * @Method("GET")
     * @return array
     * @Template()
     */
    public function listAction()
    {
        $user = $this->getUser();

        if($user instanceof User){
            $categories = [];
            $circles =  $this->getDoctrine()->getRepository(Circle::class)->findBy(['user'=>$user]);
            foreach ($circles as $index => $circle) {
                /** @var Sector $sector */
                foreach ($circle->getSectors() as $sector) {
                    $categories[] = $sector->getCategory();
                }
            }
        }else{
            throw new AccessDeniedHttpException();
        }

        return ['categories' => $categories];
    }

    /**
     * View of category
     * @Route("/view/{id}/", name="notes_manager.category.view")
     * @Method("GET")
     * @param Category $category
     * @return array
     * @Template()
     */
    public function viewAction(Category $category)
    {
        $categoryForm = $this->getCategoryForm($category);

        return ['form' => $categoryForm->createView()];
    }

    /**
     * Create of category
     * @Route("/create/", name="notes_manager.category.create")
     * @Method("GET")
     * @return array
     * @Template()
     */
    public function createAction()
    {
        $categoryForm = $this->getCategoryForm();
        return [
            'form' => $categoryForm->createView()
        ];
    }

    /**
     * create of categories
     * @Route("/create/", name="notes_manager.category.add")
     * @Method("POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addCategoryAction(Request $request)
    {
        $categoryForm = $this->getCategoryForm();

        $categoryForm->handleRequest($request);

        /** @var Category $category */
        $category = $categoryForm->getData();

        if ($categoryForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('notes_manager.category.view',['id' => $category->getId()]);
        }

        return $this->render('@App/Category/create.html.twig', [
            'form' => $categoryForm->createView()
        ]);
    }

    /**
     * create of categories
     * @Route("/update/{id}/", name="notes_manager.category.update")
     * @Method("POST")
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse|Response
     */
    public function editCategoryAction(Request $request, Category $category)
    {
        $categoryForm = $this->getCategoryForm($category);

        $categoryForm->handleRequest($request);

        $category = $categoryForm->getData();

        if ($categoryForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('notes_manager.category.view',['id' => $category->getId()]);
        }

        return $this->render('@App/Category/view.html.twig', [
            'form' => $categoryForm->createView()
        ]);
    }


    /**
     * Create category
     * @Route("/api/create/", name="notes_manager.category.api.create")
     * @Method("GET")
     * @ApiDoc(
     *  description="Method for create category",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of category"},
     *      {"name"="user_id", "dataType"="integer", "required"=true, "description"="User"},
     *      {"name"="countLayers", "dataType"="integer", "required"=true, "description"="count of Layers"}
     *  },
     *  section="Category",
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
                $category = new Category();
                $category->setName($name)
                    ->setUser($user)
                    ->setCountLayer($countLayers);

                $this->get('doctrine.orm.entity_manager')->persist($category);
                $this->get('doctrine.orm.entity_manager')->flush();

                $status = JsonResponse::HTTP_CREATED;
            }
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }


    /**
     * Full update category
     * @Route("/api/update/{id}/", name="notes_manager.category.api.full_update")
     * @Method("PUT")
     * @ApiDoc(
     *  description="Method for full update category",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of category"},
     *      {"name"="user_id", "dataType"="integer", "required"=true, "description"="User"},
     *      {"name"="countLayers", "dataType"="integer", "required"=true, "description"="count of Layers"}
     *  },
     *  section="Category",
     * )
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function fullUpdateApiAction(Request $request, Category $category)
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
                    $category->setUser($user);
                }else{
                    $category->setUser(null);
                }
            }

            if($countLayers > 0){
                $category->setCountLayer($countLayers);
            }else{
                $category->setCountLayer(null);
            }

            $category->setName($name);

            $this->get('doctrine.orm.entity_manager')->persist($category);
            $this->get('doctrine.orm.entity_manager')->flush();

            $status = JsonResponse::HTTP_OK;
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }


    /**
     * @param Category $category
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryForm($category = null)
    {
        if($category instanceof Category){
            $action = $this->container->get('router')->generate('notes_manager.category.update',['id' => $category->getId()]);
        }else{
            $category = new Category();
            $action = $this->container->get('router')->generate('notes_manager.category.add');
        }

        return $this->container->get('form.factory')->create(CategoryType::class, $category, [
            'action' => $action,
            'method' => 'POST'
        ]);
    }


}
