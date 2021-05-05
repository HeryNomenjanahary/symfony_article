<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController{

    /**
     * @var [PropertyRepository]
     */
    private $repository;

    /**
     * @var [EntityManagerInterface]
     */
    private $manager;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     *@Route("/admin", name="admin.property.index")
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig',compact('properties'));
    }

    /**
     *@Route("/admin/property/create", name="admin.property.new")
     * @return void
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($property);
            $this->manager->flush();
            $this->addFlash('success',"Ajout terminé avec success");
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()       
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
    */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->flush();
            $this->addFlash('success',"Modification terminé avec success");
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()       
        ]);
    }

    /**
     * @Route("/admin/property/{id}" , name="admin.property.delete", methods="DELETE")
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid("delete" . $property->getId(),$request->get('_token'))){
            $this->manager->remove($property);
            $this->manager->flush();
            $this->addFlash('success',"Suppression terminé avec success");
        }
        return $this->redirectToRoute('admin.property.index');
    }

}