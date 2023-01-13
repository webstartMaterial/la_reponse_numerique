<?php

namespace App\Controller;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry; 
use Symfony\Component\Mime\Address;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer, PersistenceManagerRegistry $doctrine): Response
    {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoie d'un email à Académie WS pour le notifier
            $email = (new TemplatedEmail())
            ->from(new Address('contact@academiews.fr', 'Académie WS - Contact'))
            ->to('contact@academiews.fr')
            ->subject('Message de ' . $contact->getFirstname() . ' ' . $contact->getLastname() . ' ' )
            ->htmlTemplate('contact/email.html.twig')
            ->context([
                'name' => $contact->getLastName(),
                'firstname' => $contact->getFirstName(),
                'adressEmail' => $contact->getEmail(),
                'phone' => ($contact->getPhone() == null) ? "non fourni" : $contact->getPhone(),
                'message' => $contact->getMessage(),
                'object' => $contact->getObject(),
            ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message à bien été envoyé.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'contactForm' => $form->createView(),
        ]);
    }

}
