<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Entity\OAuth2\Client;
use App\Entity\User;
use App\Form\ApplicationType;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    /**
     * @Route("/profile/application", name="profile_application_index", methods={"GET"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $applications = $user->getApplications();

        return $this->render('profile/application/index.html.twig', [
            'applications' => $applications,
        ]);
    }

    /**
     * @Route("/profile/application/new", name="profile_application_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClientManagerInterface $clientManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Client $client */
        $client = $clientManager->createClient();

        $form = $this->createForm(ApplicationType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setOwner($user);

            $clientManager->updateClient($client);

            return $this->redirectToRoute('profile_application_index');
        }

        return $this->render('profile/application/new.html.twig', [
            'application' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/application/{id}", name="profile_application_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('profile/application/show.html.twig', [
            'application' => $client,
        ]);
    }

    /**
     * @Route("/profile/application/{id}/edit", name="profile_application_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ApplicationType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_application_index', [
                'id' => $client->getId(),
            ]);
        }

        return $this->render('profile/application/edit.html.twig', [
            'application' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/application/{id}", name="profile_application_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile_application_index');
    }
}
