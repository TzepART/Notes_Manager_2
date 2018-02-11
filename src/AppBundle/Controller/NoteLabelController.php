<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use AppBundle\Entity\NoteLabel;
use AppBundle\Entity\Sector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * NoteLabelController
 * @Route("/note-label")
 */
class NoteLabelController extends Controller
{
    /**
     * Create noteLabel
     * @Route("/api/create/", name="notes_manager.note-label.api.create")
     * @Method("POST")
     * @ApiDoc(
     *  description="Method for create noteLabel",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="angle", "dataType"="float", "required"=true, "description"="Angle of noteLabel"},
     *      {"name"="radius", "dataType"="float", "required"=true, "description"="Radius of noteLabel"},
     *      {"name"="note", "dataType"="integer", "required"=true, "description"="Linking note"},
     *      {"name"="sector", "dataType"="integer", "required"=true, "description"="Linking sector"}
     *  },
     *  section="NoteLabel",
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
            !empty($angle = $request->get('angle'))
            && !empty($radius = $request->get('radius'))
            && !empty($noteId = $request->get('note'))
            && !empty($sectorId = $request->get('sector'))
        ) {
            // TODO add model with validation
            $note = $this->getDoctrine()->getRepository(Note::class)->find($noteId);
            $sector = $this->getDoctrine()->getRepository(Sector::class)->find($sectorId);

            if(($note instanceof Note) && ($sector instanceof Sector)){
                $noteLabel = new NoteLabel();
                $noteLabel->setAngle($angle)
                    ->setRadius($radius)
                    ->setNote($note)
                    ->setSector($sector);

                $this->get('doctrine.orm.entity_manager')->persist($noteLabel);
                $this->get('doctrine.orm.entity_manager')->flush();
                $status = JsonResponse::HTTP_CREATED;
            }
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }


    /**
     * Update noteLabel
     * @Route("/api/update/{id}/", name="notes_manager.note-label.api.update")
     * @Method("PATCH")
     * @ApiDoc(
     *  description="Method for update noteLabel",
     *  https=true,
     *  headers={
     *     {
     *        "name"="X-Requested-With",
     *        "description"="X-Requested-With",
     *        "default"="XMLHttpRequest"
     *     }
     *   },
     *  parameters={
     *      {"name"="angle", "dataType"="float", "required"=true, "description"="Angle of noteLabel"},
     *      {"name"="radius", "dataType"="float", "required"=true, "description"="Radius of noteLabel"},
     *      {"name"="sector", "dataType"="integer", "required"=true, "description"="Linking sector"}
     *  },
     *  section="NoteLabel",
     * )
     *
     * @param Request $request
     * @param NoteLabel $noteLabel
     * @return JsonResponse
     */
    public function updateApiAction(Request $request, NoteLabel $noteLabel)
    {
        $result = [];
        $update = false;
        $em = $this->get('doctrine.orm.entity_manager');

        $angle = (int) $request->get('angle');
        $radius = (float) $request->get('radius');
        $sectorId = (int) $request->get('sector');

        if($angle > 0){
            $noteLabel->setAngle($angle);
            $update = true;
        }

        if($radius > 0){
            $noteLabel->setRadius($radius);
            $update = true;
        }

        if($sectorId > 0){
            if($sectorId != $noteLabel->getSector()->getId()){
                $sector = $this->getDoctrine()->getRepository(Sector::class)->find($sectorId);
                $noteLabel->setSector($sector);
                $note = $noteLabel->getNote();
                $note->setCategory($sector->getCategory());
                $em->persist($note);
                $update = true;
            }
        }

        if($update){
            $em->persist($noteLabel);
            $em->flush();
            $status = JsonResponse::HTTP_OK;
        }else{
            $status = JsonResponse::HTTP_NO_CONTENT;
        }

        $response = new JsonResponse($result, $status);

        return $response;
    }

}
