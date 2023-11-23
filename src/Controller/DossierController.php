<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DossierController extends AbstractController
{
    #[Route('/chatons/{nomDuDossier}', name: 'app_dossier')]
    public function index($nomDuDossier,Request $request): Response
    {
        $chemin="Photos/$nomDuDossier";
        //on vérifie que le dossier existe
        $fs=new Filesystem();
        if (!$fs->exists($chemin)) {
            throw $this->createNotFoundException("Le dossier $nomDuDossier n'existe pas");
        }

        $form=$this->createFormBuilder()
            ->add("photo", FileType::class, ["label" => "Choisissez une photo"])
            ->add("ajouter", SubmitType::class, ["label" => "Envoyer"])
            ->getForm();

        //Traitement du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->GetData();

            $data["photo"]->move($chemin, $data["photo"]->getClientOriginalName());
        }

        //je vais constituer le modèle à envoyer à la vue
        $finder= new Finder();
        $finder->files()->in($chemin);

        return $this->render('dossier/index.html.twig', [
            'nomDuDossier' => $nomDuDossier,
            'fichiers' => $finder,
            'formulaire' => $form->createView(),
        ]);
    }
}
