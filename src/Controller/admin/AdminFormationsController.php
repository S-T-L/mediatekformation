<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur page admin Formations
 *
 * @author estel
 */
class AdminFormationsController extends AbstractController {

    const PAGES_FORMATIONS_ADMIN = "admin/admin.formations.html.twig";

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
     * Création du contructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Route page admin formations
     * @return Response
     */
    #[Route('/admin', name: 'admin.formations')]
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS_ADMIN, [
                    'formations' => $formations,
                    'categories' => $categories,
        ]);
    }

    /**
     * Tri des formaitons en fonction du champs et ordre spécifié
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/admin/tri/{champ}/{ordre}/{table}', name: 'admin.formation.sort')]
    public function sort($champ, $ordre, $table = ""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS_ADMIN, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }

    /**
     * Filtre les formations en fonction de la valeur saisie par l'utilisateur
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS_ADMIN, [
                    'formations' => $formations,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }

    /**
     * Suppression d'une formation
     * @param Formation $formation
     * @return Response
     */
    #[Route('/admin/suppr/{id}', name: 'admin.formations.suppr')]
    public function suppr(Formation $formation): Response {
        if ($formation) {
            //récupération de la playlist associée à la formation
            $playlist = $formation->getPlaylist();
            //si la formation est liée à une playlist retrait de celle ci
            if ($playlist) {
                $playlist->removeFormation($formation);
            }
            //Suppression dans la bdd
            $this->formationRepository->remove($formation);
        }
        $this->addFlash('danger','La formation' . $formation->getTitle() . ' a été supprimée avec succès.');

        return $this->redirectToRoute('admin.formations');
    }

    /**
     * Modification d'une formation
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/edit/{id}', name: 'admin.formation.edit')]
    public function edit(Formation $formation, Request $request): Response {
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            $this->addFlash('success', 'La modification de la formation ' . $formation->getTitle() . ' a bien été prise en compte.');
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("admin/admin.formation.edit.html.twig", [
                    'formation' => $formation,
                    'formFormation' => $formFormation->createView()
        ]);
    }

    /**
     * Ajout d'une formation
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/ajout', name: 'admin.formation.ajout')]
    public function ajout(Request $request): Response {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            $this->addFlash('success','La formation ' . $formation->getTitle() . ' a été ajoutée au catalogue.');
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("admin/admin.formation.ajout.html.twig", [
                    'formation' => $formation,
                    'formFormation' => $formFormation->createView()
        ]);
    }
}
