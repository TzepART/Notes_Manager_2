<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use AppBundle\Entity\User;
use AppBundle\Form\NoteType;
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
 * NoteController
 * @Route("/note")
 */
class NoteController extends Controller
{

    /**
     * List of notes
     * @Route("/", name="notes_manager.note.list")
     * @Method("GET")
     * @return array
     * @Template()
     */
    public function listAction()
    {
        $user = $this->getUser();

        if($user instanceof User){
            $notes = $this->getDoctrine()->getRepository(Note::class)->findBy(['user'=>$user]);
        }else{
            throw new AccessDeniedHttpException();
        }

        return ['notes' => $notes];
    }

    /**
     * View of note
     * @Route("/view/{id}/", name="notes_manager.note.view")
     * @Method("GET")
     * @param Note $note
     * @return array
     * @Template()
     */
    public function viewAction(Note $note)
    {
        $noteForm = $this->getNoteForm($note);

        return ['form' => $noteForm->createView()];
    }

    /**
     * Create of note
     * @Route("/create/", name="notes_manager.note.create")
     * @Method("GET")
     * @return array
     * @Template()
     */
    public function createAction()
    {
        $noteForm = $this->getNoteForm();
        return [
            'form' => $noteForm->createView()
        ];
    }

    /**
     * create of notes
     * @Route("/create/", name="notes_manager.note.add")
     * @Method("POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addNoteAction(Request $request)
    {
        $noteForm = $this->getNoteForm();

        $noteForm->handleRequest($request);

        /** @var Note $note */
        $note = $noteForm->getData();

        if ($noteForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $note->setUser($this->getUser());

            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_manager.note.view',['id' => $note->getId()]);
        }

        return $this->render('@App/Note/create.html.twig', [
            'form' => $noteForm->createView()
        ]);
    }

    /**
     * create of notes
     * @Route("/update/{id}/", name="notes_manager.note.update")
     * @Method("POST")
     * @param Request $request
     * @param Note $note
     * @return RedirectResponse|Response
     */
    public function editNoteAction(Request $request, Note $note)
    {
        $noteForm = $this->getNoteForm($note);

        $noteForm->handleRequest($request);

        $note = $noteForm->getData();

        if ($noteForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_manager.note.view',['id' => $note->getId()]);
        }

        return $this->render('@App/Note/view.html.twig', [
            'form' => $noteForm->createView()
        ]);
    }


    /**
     * Create note
     * @Route("/api/create/", name="notes_manager.note.api.create")
     * @Method("POST")
     * @ApiDoc(
     *  description="Method for create note",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="title", "dataType"="string", "required"=true, "description"="Title of note"},
     *      {"name"="text", "dataType"="text", "required"=true, "description"="Text of note"}
     *  },
     *  section="Note",
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createApiAction(Request $request)
    {
        $result = [];

        if(!empty($title = $request->get('title')) && !empty($text = $request->get('text'))){
            $note = new Note();
            $note->setTitle($title)
                ->setText($text);
            $this->get('doctrine.orm.entity_manager')->persist($note);
            $this->get('doctrine.orm.entity_manager')->flush();

            $response = new JsonResponse($result, JsonResponse::HTTP_CREATED);
        }else{
            $response = new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST);
        }

        return $response;
    }


    /**
     * Get note
     * @Route("/api/{id}/", name="notes_manager.note.api.get_note")
     * @Method("GET")
     * @ApiDoc(
     *  description="Method for get note",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  section="Note",
     * )
     *
     * @param Note $note
     * @return JsonResponse
     */
    public function getApiAction(Note $note)
    {
        $result = [
            'id' => $note->getId(),
            'title' => $note->getTitle(),
            'text' => $note->getText(),
            'date_create' => $note->getCreatedAt()->getTimestamp(),
            'date_update' => $note->getUpdatedAt()->getTimestamp(),
        ];

        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }


    /**
     * Full update note
     * @Route("/api/update/{id}/", name="notes_manager.note.api.update_full")
     * @Method("PUT")
     * @ApiDoc(
     *  description="Method for full update note",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="title", "dataType"="string", "required"=true, "description"="Title of note"},
     *      {"name"="text", "dataType"="text", "required"=true, "description"="Text of note"}
     *  },
     *  section="Note",
     * )
     *
     * @param Request $request
     * @param Note $note
     * @return JsonResponse
     */
    public function updateFullApiAction(Request $request, Note $note)
    {
        $result = [];
        $title = $request->get('title');
        $text = $request->get('text');

        $note->setTitle($title)
            ->setText($text);
        $this->get('doctrine.orm.entity_manager')->persist($note);
        $this->get('doctrine.orm.entity_manager')->flush();

        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }

    /**
     * Update note
     * @Route("/api/update/{id}/", name="notes_manager.note.api.update")
     * @Method("PATCH")
     * @ApiDoc(
     *  description="Method for update note",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="title", "dataType"="string", "required"=true, "description"="Title of note"},
     *      {"name"="text", "dataType"="text", "required"=true, "description"="Text of note"}
     *  },
     *  section="Note",
     * )
     *
     * @param Request $request
     * @param Note $note
     * @return JsonResponse
     */
    public function updateApiAction(Request $request, Note $note)
    {
        $result = [];
        $update = false;
        $title = $request->get('title');
        $text = $request->get('text');

        if(!empty($title)){
            $update = true;
            $note->setTitle($title);
        }
        if(!empty($text)){
            $update = true;
            $note->setText($text);
        }

        if($update){
            $this->get('doctrine.orm.entity_manager')->persist($note);
            $this->get('doctrine.orm.entity_manager')->flush();
            $response = new JsonResponse($result,JsonResponse::HTTP_OK);
        }else{
            $response = new JsonResponse($result,JsonResponse::HTTP_NO_CONTENT);
        }

        return $response;
    }


    /**
     * Delete note
     * @Route("/api/delete/{id}/", name="notes_manager.note.api.delete")
     * @Method("DELETE")
     * @ApiDoc(
     *  description="Method for delete note",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  section="Note",
     * )
     *
     * @param Note $note
     * @return JsonResponse
     */
    public function deleteApiAction(Note $note)
    {
        $result = [];
        $this->get('doctrine.orm.entity_manager')->remove($note);
        $this->get('doctrine.orm.entity_manager')->flush();
        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }


    /**
     * @param Note $note
     * @return \Symfony\Component\Form\Form
     */
    public function getNoteForm($note = null)
    {
        if($note instanceof Note){
            $action = $this->container->get('router')->generate('notes_manager.note.update',['id' => $note->getId()]);
        }else{
            $note = new Note();
            $action = $this->container->get('router')->generate('notes_manager.note.add');
        }

        return $this->container->get('form.factory')->create(NoteType::class, $note, [
            'action' => $action,
            'method' => 'POST'
        ]);
    }


}
