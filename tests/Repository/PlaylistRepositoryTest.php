<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author estel
 */
class PlaylistRepositoryTest extends KernelTestCase {
    /**
     * récupération de Repository Playlist
     * @return PlaylistRepository
     */
     public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    /**
     * récupère le nombre de playlists
     */
     public function testNbPlaylists(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(27, $nbPlaylists);
    }
    /**
     * création d'une nouvelle playlist
     * @return Playlist
     */
      public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
                   ->setName(" test Titre de la playlist")
                   ->setDescription("test Description de la playlist");
        return $playlist;
    }
    /**
     * test de l'ajout d'une playlist
     */
     public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    /**
     * test de suppression d'une playlist
     */
     public function testRemovePlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylist = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylist - 1, $repository->count([]), "Erreur lors de la suppression de la playlist");
    }
    //put your code here
}
