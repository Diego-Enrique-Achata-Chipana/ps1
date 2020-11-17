<?php
namespace App\Service;

use App\Entity\Tareas;
use App\Repository\TareasRepository;
use Doctrine\ORM\EntityManagerInterface;

class TareaManager {

    private $em;
    private $tareasRepository;

    public function __construct(TareasRepository $tareasRepository ,EntityManagerInterface $em){
        $this->em = $em;
        $this->tareasRepository = $tareasRepository;

    }

    public function create(Tareas $tareas){
        $this->em->persist($tareas);
        $this->em->flush();
    }

    public function edit(Tareas $tareas):void 
    {
        $this->em->flush($tareas);
    }

    public function delete(Tareas $tareas):void 
    {
        $this->em->remove($tareas);
    }

    public function validar(Tareas $tareas)
    {
        $errores = [];
        if(empty($tareas->getDescripcion())){
            $errores[] = "campo 'descripcion' obligatorio";
        }
    }
}