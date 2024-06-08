<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistryController extends AbstractController
{
    /**
     * @Route("/reg", name="app_registry")
     */
    public function reg(Request $request, UserPasswordHasherInterface $passEncoder): Response
    {
        $regform = $this->createFormBuilder()
        ->add('username', TextType::class, 
        ['label' => "Employee"])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password']
        ])
        ->add('register', SubmitType::class)
        ->getForm()
        ;

        $regform->handleRequest($request);
        if ($regform->isSubmitted()) {
            $inputFromForm = $regform->getData();
            $user = new User();
            $user->setUsername($inputFromForm['username']);
            $user->setPassword(
                $passEncoder->hashPassword($user, $inputFromForm['password'])
            );
            // save data to db
            $entityMngr = $this->getDoctrine()->getManager();
            $entityMngr->persist($user);
            $entityMngr->flush();
            return $this->redirect($this->generateUrl('home'));

        }

        return $this->render('registry/index.html.twig', [
            'regform' => $regform->createView()
        ]);
    }
}
