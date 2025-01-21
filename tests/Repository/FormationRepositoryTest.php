<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;

/**
 * tests d'intégration sur FormationRepository
 *
 * @author estel
 */
class FormationRepositoryTest extends KernelTestCase {

    /**
     * Récuperer l'instance du repository
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    /**
     * Récupération du nombre d'enregistrements de la table Formation
     */
    public function testNbFormations() {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }

    /**
     * Création d'une instance de formation pour les tests
     * @return Formation
     */
    public function newFormation(): Formation {
        $formation = (new Formation())
                ->setTitle("test Titre de la formation")
                ->setDescription("test Description de la formation")
                ->setPublishedAt(new DateTime("2025-01-17"));
        return $formation;
    }

    /**
     * Test de l'ajout d'une formation
     */
    public function testAddFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    /**
     * test de suppression d'une formation
     */
    public function testRemoveFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }
    /**
     * test de la fonction de tri d'un champ ASC DESC
     */
    public function testFindAllOrderBy() {
        //ASC
        $repository = $this->recupRepository();
        $formationsAsc = $repository->findAllOrderBy("title", "ASC");
        $nbFormationsAsc = count($formationsAsc);
        $this->assertEquals(237, $nbFormationsAsc);
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", $formationsAsc[0]->getTitle());
        //DESC
        $formationsDesc = $repository->findAllOrderBy("title", "DESC");
        $nbFormationsDesc = count($formationsDesc);
        $this->assertEquals(237, $nbFormationsDesc);
        $this->assertEquals("UML : Diagramme de paquetages", $formationsDesc[0]->getTitle());
        
        
    }
    /**
     * filtre les formations avec une valeur spécifique
     */
     public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "Eclipse");
        $nbFormations = count($formations);
        $this->assertEquals(9, $nbFormations);
        $this->assertEquals("Eclipse n°8 : Déploiement", $formations[0]->getTitle());
    }
    
    /**
     * Test de la récupération de la formation la plus récente
     */
    public function testFindAllLasted(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(1);
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals(new DateTime("2025/01/17 00:00:00 "), $formations[0]->getPublishedAt());
    }
    /**
     * Test de la récupération des formations d'une playlist selon son id
     */
     public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllForOnePlaylist(2);
        $nbFormations = count($formations);
        $this->assertEquals(11, $nbFormations);
        $this->assertEquals("C# : présentation des objets graphiques",$formations[0]->getTitle());
    }
}
