<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Volley\FaceBundle\Entity\Setting;
use Volley\FaceBundle\Form\SettingType;

/**
 * Setting controller.
 *
 */
class SettingController extends Controller
{
    /**
     * Displays a form to edit an existing Setting entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('VolleyFaceBundle:Setting:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Setting entity.
    *
    * @param Setting $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Setting $entity)
    {
        $form = $this->createForm(SettingType::class, $entity, array(
            'action' => $this->generateUrl('setting_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Setting entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VolleyFaceBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('setting_edit', array('id' => $id)));
        }

        return $this->render('VolleyFaceBundle:Setting:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
}
