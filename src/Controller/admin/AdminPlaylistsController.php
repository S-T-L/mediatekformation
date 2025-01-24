<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\FormationType;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur page admin Playlists
 *
 * @author estel
 */
class AdminPlaylistsController extends AbstractController {

    const PAGE_PLAYLISTS_ADMIN = "admin/admin.playlists.html.twig";

    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;

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
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     * route page admin playlist
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS_ADMIN, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * Suppression d'une playlist
     * @param Playlist $playlist
     * @return Response
     */
    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(Playlist $playlist): Response {

        $this->playlistRepository->remove($playlist);
        $this->addFlash('success', 'La suppression de la playlist  ' . $playlist->getName() . " a bien été prise en compte.");
        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * Tri des playlists selon le champ
     * @param type $champ
     * @param type $ordre
     * @return Response
     * @throws InvalidArgumentException
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbFormations":
                $playlists = $this->playlistRepository->findAllOrderByTotalNb($ordre);
                break;
            default:
                throw new InvalidArgumentException("Invalid sorting parameter: $champ");
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS_ADMIN, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * Filtre selon la valeur renseignée 
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS_ADMIN, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }

    /**
     * Accès à page playlist
     * @param type $id
     * @return Response
     */
    #[Route('/admin/playlists/playlist/{id}', name: 'admin.playlists.showone')]
    public function showOne($id): Response {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("admin/admin.playlist.edit.html.twig", [
                    'playlist' => $playlist,
                    'playlistcategories' => $playlistCategories,
                    'playlistformations' => $playlistFormations
        ]);
    }

    /**
     * Modification d'une playlist
     * @param Playlist $playlist
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(Playlist $playlist, Request $request): Response {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
             $this->addFlash('success','La playlist ' . $playlist->getName() . " a bien été modifiée.");
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render("admin/admin.playlist.edit.html.twig", [
                    'playlist' => $playlist,
                    'formPlaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * Ajout d'une playlist
     * @param Request $request
     * @return response
     */
    #[Route('/admin/playlist/ajout/', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): response {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
             $this->addFlash('success','La playlist ' . $playlist->getName() . " a été effectuée avec succès.");
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/admin.playlist.ajout.html.twig", [
                    'playlist' => $playlist,
                    'formPlaylist' => $formPlaylist->createView()
        ]);
    }
}
