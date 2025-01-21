<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {
    /**
     * 
     */
    const PAGES_FORMATIONS = "pages/formations.html.twig";

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Constructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Page formations
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }
    /**
     * Trie les enregistrements selon $champ $ordre $table
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }
    
    /**
     * Filtre les enregistrements selon $champ et $ordre
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
           
            $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
            $categories = $this->categorieRepository->findAll();
        }
        return $this->render(self::PAGES_FORMATIONS, [
                    'formations' => $formations,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }
    
    /**
     * Affiche les détails d'une formation à partir de $id
     * @param type $id
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
                    'formation' => $formation,
        ]);
    }
}
