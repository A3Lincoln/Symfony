<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Form\AlbumEditType;
use App\Form\AlbumType;
use App\Form\ArtistType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/album")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/{artist_id}", name="album_index")
     */
    public function index(AlbumRepository $albumRepository,
                          ArtistRepository $artistRepository,
                          Request $request, $artist_id): Response
    {
        $artist = $artistRepository->findById($artist_id);

        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findByArtist($artist),
            'entityName' => 'album',
           'artist_id' => $artist_id
           // 'artistName' =>$artist->getName(),
        ]);
    }

    /**
     * @Route("/new/{artist_id}", name="album_new", methods={"GET","POST"})
     */
    public function new(Request $request, $artist_id, ArtistRepository $artistRepository): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);


        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $entityManager = $this->getDoctrine()->getManager();
            $artist = $artistRepository->findById($artist_id);
            $album->setArtist($artist);
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album_index', array('artist_id'=>$artist_id));
        }

        return $this->render('album/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
            'entityName' => 'album',
            'artist_id'=>$artist_id
        ]);
    }


    /**
     * @Route("/{id}/edit", name="album_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Album $album): Response
    {
        $form = $this->createForm(AlbumEditType::class, $album);

        $artist_id = $form->getData()->getArtist()->getId();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('album_index', array('artist_id'=>$artist_id) );
        }

        return $this->render('album/edit.html.twig', [
            'entity' => $album,
            'form' => $form->createView(),
            'entityName' => 'album',
            'artist_id' =>$artist_id,

        ]);
    }

    /*НЕ РАБОТАЕТ*/


    /**
     * @Route("/{id}", name="album_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, AlbumRepository $albumRepository): Response
    {
        $album = $albumRepository->findById($id);
        //dump($song);
        //$song_id = $song->getSong()->getId();
        //dump($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))); die;
        //if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($album);
        $entityManager->flush();
        //}

        return $this->json([]);
        //return $this->redirectToRoute('song_index', array('song_id'=>$song_id));
    }
}
