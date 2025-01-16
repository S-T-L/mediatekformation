<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author estel
 */
class FormationValidationsTest extends KernelTestCase {
    

    // création d'une nouvelle formation
    public function getFormation(): Formation {
        return (new Formation())
                        ->setTitle('Nouvelle formation')
                        ->setPublishedAt(new DateTime("2025/12/12"));
    }

    public function testValidDateFormation() {
        // Test avec une date passée (hier)
        $this->assertErrors($this->getFormation()->setPublishedAt(new \DateTime('yesterday')), 0, "Hier devrait être une date valide.");

        // Test avec la date d'aujourd'hui
        $this->assertErrors($this->getFormation()->setPublishedAt(new \DateTime('today')), 0, "Aujourd'hui devrait être une date valide.");

        
    }

    public function testNonValidDateFormation() {
    // Test avec une date dans 1 an dans le futur
    $formation = $this->getFormation()->setPublishedAt(new \DateTime('+1 year'));
    $this->assertErrors($formation, 1, "Une date dans le futur (1 an) devrait échouer.");
}


   public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message = "") {
    self::bootKernel();
    $validator = self::getContainer()->get(ValidatorInterface::class);
    $errors = $validator->validate($formation);  // On valide ici l'entité

    // On vérifie qu'on a bien le nombre attendu d'erreurs
    $this->assertCount($nbErreursAttendues, $errors, $message);
}


    //put your code here
}
