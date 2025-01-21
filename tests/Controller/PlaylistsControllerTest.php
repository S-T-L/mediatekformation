<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 *
 * @author estel
 */
class PlaylistsControllerTest extends WebTestCase {

    /**
     * Test d'accès à la page des playlists 
     */
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Test du tri ASC des playlist selon leurs noms 
     */
    public function testPlaylistsTriAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/name/ASC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }

    /**
     * Test du tri DESC des playlist selon leurs noms 
     */
    public function testPlaylistsTriADesc() {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/name/DESC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#');
    }

    /**
     * Test le tri ASC selon le nombre de formations
     */
    public function testTriNbFormationsAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/nbFormations/ASC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');
    }

    /**
     * Test le tri DESC selon le nombre de formations
     */
    public function testTriNbFormationsDesc() {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/nbFormations/DESC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }

    /**
     * Teste le filtre des playlists selon la valeur renseignée
     */
    public function testFiltrePlaylists() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'programmation'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(5, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
        $this->assertSelectorTextContains('h5', 'programmation');
    }
    /**
     * Test du filtre des catégories selon la valeur sélectionnée
     */
    public function testFiltreCategories() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/recherche/id/categories');
        $form = $crawler->filter('.categorie-form')->form();
        $form['recherche'] = '8';
        $crawler = $client->submit($form);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(8, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Cours Curseurs');
    }
    
    /**
     * Test du lien qui dirige l'utilisateur vers la page de détail de la playlist
     */
    public function testLinkFormations() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
    }
    
}
