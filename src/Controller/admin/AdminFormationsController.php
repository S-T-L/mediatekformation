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
 * Description of AdminFormationsController
 *
 * @author estel
 */
class AdminFormationsController extends AbstractController {

    const PAGES_FORMATIONS_ADMIN = "admin/admin.formations.html.twig";

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
     * 
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * 
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
     * 
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/admin/tri/{champ}/{ordre}/{table}', name: 'admin.formation.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS_ADMIN, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * 
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
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
    
    #[Route('/admin/suppr/{id}', name: 'admin.formations.suppr')]
    public function suppr(int $id) : Response
    {
        $formation = $this->formationRepository->find($id);
        if($formation){
            //récupération de la playlist associée à la formation
            $playlist = $formation->getPlaylist();
            //si la formation est liée à une playlist retrait de celle ci
            if($playlist){
                $playlist->removeFormation($formation);
            }
            //Suppression dans la bdd
        $this->formationRepository->remove($formation);
        }
        
        
        return $this->redirectToRoute('admin.formations');
    }
    
    #[Route('/admin/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response{
        $formation = $this->formationRepository->find($id);
        $formformation = $this->createForm(FormationType::class, $formation);
        
        $formformation->handleRequest($request);
        if($formformation->isSubmitted()&& $formformation->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formformation->createView()
        ]);
    }
    
    #[Route('/admin/ajout', name: 'admin.formation.ajout')]
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formformation = $this->createForm(FormationType::class, $formation);
        
        $formformation->handleRequest($request);
        if($formformation->isSubmitted()&& $formformation->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formformation->createView()
        ]);
    }

    
}
