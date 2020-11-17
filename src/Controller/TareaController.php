<?php

namespace App\Controller;

use App\Repository\TareasRepository;
use App\Entity\Tareas;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TareaController extends AbstractController
{
    /**
     * @Route("/tarea", name="app_listado_tarea")
     */
    public function index(TareasRepository $tareasRepository): Response
    {
        $tareas = $tareasRepository->findAll();
        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    /**
     * @Route("/tarea/create", name="app_crear_tarea")
     */
    public function create(Request $request): Response
    {
        $tarea = new Tareas();
        $descripcion = $request->request->get('descripcion',null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success','La tarea ha sido registrada exitosamente');
                return $this->redirectToRoute('tarea/index.html.twig');
            }else {
                $this->addFlash('warning','El campo descripcion es obligatorio');
            }
        }
        return $this->render('tarea/create.html.twig', [
            'tarea' => $tarea
        ]);
    }

    /**
     * @Route("/tarea/edit/{id}", name="app_editar_tarea")
     */
    public function edit(int $id, TareasRepository $tareasRepository, Request $request): Response
    {
        $tarea = $tareasRepository->findOneById($id);
        if( null === $tarea){
            throw $this->createNotFoundException();
        }
        $descripcion = $request->request->get('descripcion',null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success','La tarea ha sido actualizada exitosamente');
                return $this->redirectToRoute('app_listado_tarea');
            }else {
                $this->addFlash('warning','El campo descripcion es obligatorio');
            }
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/tarea/delete/{id}", name="app_eliminar_tarea", requirements={"id"="\d+"})
     */
    public function delete(Tareas $tarea): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tarea);
        $em->flush();
        $this->addFlash('success','Tarea eliminada exitosamente');

        return $this->redirectToRoute('app_listado_tarea');
    }
}
