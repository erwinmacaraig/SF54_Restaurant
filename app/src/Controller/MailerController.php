<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MailerController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function sendEmail(MailerInterface $mailer, Request $request): Response
    {
        $emailForm = $this->createFormBuilder()
                     ->add('message', TextareaType::class, [
                        'attr' => array('rows' => 5)
                     ])
                     ->add("submit", SubmitType::class)
                     ->getForm();

        $emailForm->handleRequest($request);
        if($emailForm->isSubmitted())
        {
            $data = $emailForm->getData();
            $text = ($data['message']);
            $tisch = 'table 1';
            // $text = 'Bitter taste was experienced';
            $email = (new TemplatedEmail())
                     ->from('combat.tester.enthusiast@gmail.com')
                     ->to('erwin_macaraig@yahoo.com')
                     ->subject('News')                 
                     ->htmlTemplate('mailer/mail.html.twig')
                     ->context([
                        'tisch' => $tisch,
                        'text' => $text
                     ])
                     ;
                    
            $mailer->send($email);
            $this->addFlash('message', 'Message was sent successfully');
            return $this->redirect($this->generateUrl('mail'));
            
        }
        return $this->render('mailer/index.html.twig'
    , [
        'emailForm' => $emailForm->createView()
    ]);
        
    }
}
