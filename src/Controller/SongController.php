<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongEditType;
use App\Form\SongType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/song")
 */
class SongController extends AbstractController
{
    /**
     * @Route("/{album_id}", name="song_index")
     */
    public function index(SongRepository $songRepository,
                          AlbumRepository $albumRepository,
                          Request $request, $album_id): Response
    {

        //$song = $songRepository->findById($song_id);
        $album=$albumRepository->findById($album_id);
        return $this->render('song/index.html.twig', [
            'songs' => $songRepository->findByAlbum($album),
            'entityName' => 'song',
            'album_id' => $album_id
            // 'songName' =>$song->getName(),
        ]);
    }

    /**
     * @Route("/sba/{artist_id}", name="songs_by_artist")
     */
    public function songsByArtist(SongRepository $songRepository,
                          AlbumRepository $albumRepository,
                          ArtistRepository $artistRepository,
                          Request $request, $artist_id): Response
    {
        dump($artist_id);
        $artist = $artistRepository->findById($artist_id);
        $albums = $albumRepository->findByArtist($artist);
        dump($artist);
        dump($albums);
        $songs = $songRepository->findByAlbums($albums);
        dump($songs);
        return $this->render('songsByArtist.html.twig', [
            'songs' => $songs,

        ]);
    }

    /**
     * @Route("/new/{album_id}", name="song_new", methods={"GET","POST"})
     */
    public function new(Request $request, $album_id, AlbumRepository $albumRepository): Response
    {

        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);


        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $entityManager = $this->getDoctrine()->getManager();

            $album = $albumRepository->findById($album_id);

            $song->setAlbum($album);
            $entityManager->persist($song);
            $entityManager->flush();

            return $this->redirectToRoute('song_index', array('album_id'=>$album_id));
        }

        return $this->render('song/new.html.twig', [
            'song' => $song,
            'form' => $form->createView(),
            'entityName' => 'song',
            'album_id'=>$album_id
        ]);
    }


    /**
     * @Route("/{id}/edit", name="song_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Song $song): Response
    {
        $form = $this->createForm(SongEditType::class, $song);

        $album_id = $form->getData()->getAlbum()->getId();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('song_index', array('album_id'=>$album_id) );
        }

        return $this->render('song/edit.html.twig', [
            'entity' => $song,
            'form' => $form->createView(),
            'entityName' => 'song',
            'album_id' =>$album_id,

        ]);
    }



    /**
     * @Route("/{id}", name="song_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, SongRepository $songRepository): Response
    {
        $song = $songRepository->findById($id);
        //dump($song);
        //$song_id = $song->getSong()->getId();
        //dump($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))); die;
        //if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($song);
        $entityManager->flush();
        //}

        return $this->json([]);
        //return $this->redirectToRoute('song_index', array('song_id'=>$song_id));
    }
}
