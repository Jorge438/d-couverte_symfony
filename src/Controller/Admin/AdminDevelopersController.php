<?php

namespace App\Controller\Admin;

use App\Entity\Developers;
use App\Form\DeveloperFormType;
use App\Repository\DevelopersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminDevelopersController extends AbstractController {

    /**
     * @var DevelopersRepository
     */

     private $repository;

    /**
     * @var EntityManagerInterface
     */ 

     private $em;

    public function __construct(DevelopersRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin_developer")
     */
    public function index(){
        $developers = $this->repository->findAll();
        return $this->render('admin/index.html.twig', compact('developers'));
    }

    /**
     * @Route("/admin/developer/edit/{id}", name="admin_developer_edit", methods="GET|POST")
     */
    public function edit(Developers $developer, Request $request){
        $form = $this->createForm(DeveloperFormType::class,$developer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('admin_developer');
        }

        return $this->render('admin/edit.html.twig', [
            "form_title" => "Modifier un développeur",
            'developer' => $developer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/developer/new", name="admin_developer_new")
     */

    public function new(Request $request){
        $developer = new Developers;
        $form = $this->createForm(DeveloperFormType::class,$developer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($developer);
            $this->em->flush();
            return $this->redirectToRoute('admin_developer');
        }

        return $this->render('admin/new.html.twig', [
            "form_title" => "Ajouter un développeur",
            'developer' => $developer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/developer/{id}", name="admin_developer_remove", methods="DELETE")
     */    
    public function remove(Developers $developer, Request $request){
        if ($this->isCsrfTokenValid('delete' . $developer->getId(), $request->get('_token'))){
            $this->em->remove($developer);
            $this->em->flush();
        }
        return $this->redirectToRoute('admin_developer');
    }

}