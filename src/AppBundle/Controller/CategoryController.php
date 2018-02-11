<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Sector;
use AppBundle\Form\CategoryType;
use AppBundle\Model\ListNotesModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * CategoryController
 * @Route("/category")
 */
class CategoryController extends Controller
{

    /**
     * list of notes by note
     * @Route("/{id}/", name="notes_manager.category.note_list_by_category", requirements={"id"="\d+"})
     * @Method("GET")
     * @param Category $category
     * @return Response
     */
    public function listByCategoryAction(Category $category)
    {
        $listNotesModel = new ListNotesModel();
        $listNotesModel->setSelectCategory($category);
        $this->get('app.note_notes_manager')->updateListNotesModelByUser($listNotesModel,$this->getUser());

        return $this->render('@App/Note/list.html.twig',['listNotesModel' => $listNotesModel]);
    }

    /**
     * View of category
     * @Route("/view/{id}/", name="notes_manager.category.view", requirements={"id"="\d+"})
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
     *      {"name"="sector_id", "dataType"="integer", "required"=true, "description"="Sector"}
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
            && !empty($sector_id = $request->get('sector_id'))

        ) {
            $sector = $this->getDoctrine()->getRepository(Sector::class)->find($sector_id);
            if($sector instanceof Sector){
                $category = new Category();
                $category->setName($name)
                         ->setSector($sector);

                $this->get('doctrine.orm.entity_manager')->persist($category);
                $this->get('doctrine.orm.entity_manager')->flush();

                $status = JsonResponse::HTTP_CREATED;
            }
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
