<?php

namespace App\Controller;

use App\Entity\Cases;
use App\Entity\Files;
use App\Form\CasesType;
use App\Repository\CasesRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(): Response
    {
        return $this->render('cases/index.html.twig', [
            'cases' => $this->getDoctrine()->getRepository(Cases::class)->findAll(),
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

            $files = $form->get('files')->getData();
            /**
             * @var UploadedFile $file
             */
            foreach($files as $file){
                $uploadesFileName = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
                $directory = $this->getParameter('files_directory');
                $file->move(
                    $directory,
                    $uploadesFileName
                );

                $uploadesFile = new Files();
                $uploadesFile->setName($uploadesFileName);
                $uploadesFile->setLocation($directory.'/'.$uploadesFileName);
                $case->addFile($uploadesFile);
            }
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

    /**
     * @Route("/datatable/ajax", name="cases_ajax", methods={"GET"})
     */
    public function getCasesJSON(CasesRepository $casesRepository)
    {
        $cases= $casesRepository->findAllCases();
        return new JsonResponse(["data" =>$cases]);
    }
}
