<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DossierController extends AbstractController
{
    #[Route('/chatons/{nomDuDossier}', name: 'app_dossier')]
    public function index($nomDuDossier): Response
    {
        //je vais constituer le modèle à envoyer à la vue
        $finder= new Finder();
        $finder->files()->in("Photos/$nomDuDossier");

        return $this->render('dossier/index.html.twig', [
            'nomDuDossier' => $nomDuDossier,
            'fichiers' => $finder,
        ]);
    }
}
