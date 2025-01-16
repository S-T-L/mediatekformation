<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;

/**
 * Description of FormationRepositoryTest
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
     * Création d'une instance de formation
     * @return Formation
     */
    public function newFormation(): Formation {
        $formation = (new Formation())
                ->setTitle("Titre de la formation")
                ->setDescription("Description de la formation")
                ->setPublishedAt(new DateTime('now'));
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
}
