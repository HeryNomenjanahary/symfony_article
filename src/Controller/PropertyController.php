<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController{

    /**
     * @var [PropertyRepository]
     */
    private $repository;

    /**
     * [EntityManagerInterface]
     */
    private $manager;


    public function __construct(PropertyRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * Undocumented function
     * @Route("/biens", name="property.index")
     */
    public function index(): Response
    {
        // $property = new Property();
            // $property->setTitle("Mon premier bien")
            //         ->setPrice(200000)
            //         ->setRooms(4)
            //         ->setBedrooms(3)
            //         ->setDescription('Une petite description')
            //         ->setSurface(60)
            //         ->setFloor(4)
            //         ->setHeat(1)
            //         ->setCity('Montpellier')
            //         ->setAddress('15 boulevard Gambetta')
            //         ->setPostalCode('34000');
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($property);
            // $entityManager->flush();

        // $repository = $this->getDoctrine()->getRepository(Property::class);

        $property = $this->repository->findAllVisible();
        // $property[0]->setSold(false);
        // $this->manager->flush();
        // dump($property);
        return $this->render('pages/property/index.html.twig',[
            'current_menu' => 'properties'
        ]);
    }

    /**
     * Undocumented function
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug":"[a-z0-9\-]*"})
     */
    public function show(Property $property,string $slug)
    {
        // $property = $this->repository->find($id);
        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show',[
                'id'   => $property->getId(),
                'slug' => $property->getSlug()
            ],301);
        }
        return $this->render('pages/property/show.html.twig',[
            'current_menu' => 'properties',
            'property' => $property
        ]);
    }

}