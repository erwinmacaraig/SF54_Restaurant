<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

    /**
     * @Route("/dish", name="dish.")
     */
class DishController extends AbstractController
{
    /**
     * @Route("/", name="app_dish")
     */
    public function index(DishRepository $dishRepository): Response
    {
        $dishes = $dishRepository->findAll();

        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }

    /**
     * @Route("/create", name="dish_create")
     */
    public function create(Request $request){
        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $image = $request->files->get('dish')['attachment'];
            if($image) {
                $dateinname = md5(uniqid()). '.'.$image->guessClientExtension();
            }
            $image->move(
                $this->getParameter('uploaded_images_dir'),
                $dateinname
            );
            $dish->setImage($dateinname);
            $em->persist($dish);
            $em->flush();
           
            return $this->redirect($this->generateUrl('dish.app_dish'));
        }
        
        
        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Request $request, $id, DishRepository $dishRepository){
        // the first one we need is an entity manager
        $em = $this->getDoctrine()->getManager();
        //search the record with the help of &id
        $dish = $dishRepository->find($id);
        $em->remove($dish);
        $em->flush();
        $this->addFlash('success', 'Dish was removed successfully');
        return $this->redirect($this->generateUrl('dish.app_dish'));
    }

    /**
     * @Route("/show/{id}", name="show")     
     */
    public function showImage($id, DishRepository $dishRepository) {
        $em = $this->getDoctrine()->getManager();
        $dish = $dishRepository->find($id);
        return $this->render('dish/show.html.twig', [
             "dish" => $dish
         ]);

    }
}
