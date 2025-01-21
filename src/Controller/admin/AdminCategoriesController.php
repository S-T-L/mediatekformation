<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author estel
 */
class AdminCategoriesController extends AbstractController {

    /**
     * Accès aux formations
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Accès aux catégories
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Route Page admin catégorie
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
                    'formations' => $formations,
                    'categories' => $categories,
        ]);
    }

    /**
     * Ajout d'une catégorie 
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categorie/ajout', name: 'admin.categorie.ajout', methods: ['POST'])]
    public function ajout(Request $request): Response {
        $name = $request->get("name");

        if (empty($name)) {
            $this->addFlash('danger', 'Le nom de la catégorie est obligatoire.');
            return $this->redirectToRoute('admin.categories');
        }

        $existCategorie = $this->categorieRepository->findOneByName($name);

        if ($existCategorie) {
            $this->addFlash('danger', 'La catégorie "' . $name . '" existe déjà.');
        } else {
            $categories = new Categorie();
            $categories->setName($name);
            $this->categorieRepository->add($categories, true);
            $this->addFlash('success', 'La catégorie "' . $name . '" a été ajoutée avec succès.');
        }

        return $this->redirectToRoute('admin.categories');
    }

    /**
     * Suppression d'une catégorie, redirection vers la page admin
     * @param Categorie $categorie
     * @return Response
     */
    #[Route('/admin/categorie/suppr{id}', name: 'admin.categorie.suppr')]
    public function suppr(Categorie $categorie): Response {

        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
}
