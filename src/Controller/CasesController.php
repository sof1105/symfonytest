<?php

namespace App\Controller;

use App\Entity\Cases;
use App\Form\CasesType;
use App\Repository\CasesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cases")
 */
class CasesController extends AbstractController
{
    /**
     * @Route("/", name="cases_index", methods={"GET"})
     */
    public function index(CasesRepository $casesRepository): Response
    {
        return $this->render('cases/index.html.twig', [
            'cases' => $casesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cases_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $case = new Cases();
        $form = $this->createForm(CasesType::class, $case);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($case);
            $entityManager->flush();

            return $this->redirectToRoute('cases_index');
        }

        return $this->render('cases/new.html.twig', [
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cases_show", methods={"GET"})
     */
    public function show(Cases $case): Response
    {
        return $this->render('cases/show.html.twig', [
            'case' => $case,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cases_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cases $case): Response
    {
        $form = $this->createForm(CasesType::class, $case);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cases_index');
        }

        return $this->render('cases/edit.html.twig', [
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cases_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cases $case): Response
    {
        if ($this->isCsrfTokenValid('delete'.$case->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($case);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cases_index');
    }
}
