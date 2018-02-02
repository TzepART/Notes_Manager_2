<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * NoteController
 * @Route("/note")
 */
class NoteController extends Controller
{
    /**
     * Create note
     * @Route("/create/", name="notes_manager.note.create")
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
    public function createAction(Request $request)
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
     * @Route("/{id}/", name="notes_manager.note.get_note")
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
    public function getAction(Note $note)
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
     * @Route("/update/{id}/", name="notes_manager.note.update_full")
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
    public function updateFullAction(Request $request, Note $note)
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
     * @Route("/update/{id}/", name="notes_manager.note.update")
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
    public function updateAction(Request $request, Note $note)
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
     * @Route("/delete/{id}/", name="notes_manager.note.delete")
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
    public function deleteAction(Note $note)
    {
        $result = [];
        $this->get('doctrine.orm.entity_manager')->remove($note);
        $this->get('doctrine.orm.entity_manager')->flush();
        $response = new JsonResponse($result,JsonResponse::HTTP_OK);

        return $response;
    }

}
