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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * NoteLabelController
 * @Route("/note-label")
 */
class NoteLabelController extends Controller
{
    /**
     * Create noteLabel
     * @Route("/create/", name="notes_manager.note-label.create")
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
    public function createAction(Request $request)
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

}
