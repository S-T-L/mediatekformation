<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Entity;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;



/**
 * Description of FormationTest
 *
 * @author estel
 */
class FormationTest extends TestCase 
{

    public function testGetPublishedAtString() {

        $formation = new Formation();

        // cas avec une Date valide
        $date = new DateTime('2025-01-16 09:20:00');
        $formation->setPublishedAt($date);
        $this->assertEquals('16/01/2025', $formation->getPublishedAtString());

        // cas avec une Date nulle
        $formation->setPublishedAt(null);
        $this->assertEquals('', $formation->getPublishedAtString());
    }

    //put your code here
}
