<?php

namespace App\Controller;

use App\Form\ContactTypeForm;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactUsController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function ContactUs( Request $request, MailerInterface $mailer): Response
    {
    
      

        $form = $this->createForm(ContactTypeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->get('message')->getData();
            $subj= $form->get('sujet')->getData();
            $mail= $form->get('mail')->getData();


            $email = (new TemplatedEmail())
                ->from('ocmme3pommes@test.fr')
                ->to('ocmme3pommes@test.fr')
                ->subject('rubrique contact')
                ->htmlTemplate('email/contactUs.html.twig')
                ->context([
                    'message' => $message,
                    'sujet' => $subj,
                    'mail' => $mail,
                ]);


            $mailer->send($email);
            $this->addFlash('success', 'Votre email a bien été envoyé.');
            return $this->redirectToRoute('index');
        }


        return $this->render('contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}