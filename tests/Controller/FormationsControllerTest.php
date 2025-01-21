<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTest
 *
 * @author estel
 */
class FormationsControllerTest extends WebTestCase {

    private const FORMATIONSPATH = '/formations';

    /**
     * Test l'accès à la page des formations
     */
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONSPATH);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Test le tri ASC selon le nom des playlists
     */
    public function testPlaylistsTriAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');
    }

    /**
     * Test le tri DESC selon le nom des playlists
     */
    public function testPlaylistsTriDesc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');
    }

    /**
     *  Teste le tri ASC sur le titre des formations
     */
    public function testFormationsTriAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    /**
     *  Teste le tri DESC sur le titre des formations
     */
    public function testFormationsTriDesc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/DESC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }

    /**
     *  Teste le tri ASC sur la date de publication
     */
    public function testDateTriAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Cours UML (8 à 11 / 33) : diagramme de classes');
    }

    /**
     *  Teste le tri DESC sur la date de publication
     */
    public function testDateTriDesc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Test Formation');
    }

    /**
     * Test le filtre des formations selon la valeur renseignée 
     */
    public function testFiltreFormations() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONSPATH);
        //simulation de la soumission du form
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Python'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(19, $crawler->filter('h5'));
        //verifie que la formation corresponde
        $this->assertSelectorTextContains('h5', 'Python n°18 : Décorateur singleton');
    }

    /**
     * Test le filtre des playlists selon la valeur renseignée
     */
    public function testFiltrePlaylists() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONSPATH);
        //simulation de la soumission du form
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'MCD'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(31, $crawler->filter('h5'));
        //verifie que la formation corresponde
        $this->assertSelectorTextContains('h5', 'MCD exercice 18 : sujet 2006 (cas Credauto)');
    }

    /**
     * Test sur le filtre des catégorie selon la valeur selectionnée
     */
    public function testFiltreCategories() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('.filtre-categorie')->form();
        $form['recherche'] = '2';
        $crawler = $client->submit($form);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(10, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Eclipse n°2 : rétroconception avec ObjectAid');
    }

    /**
     * Test du lien qui dirige l'utilisateur vers la page de détail de la formation
     */
    public function testLinkFormations() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink("formations en miniature");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
    }
}
