<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/annonces")
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="annonces_index", methods={"GET"})
     */
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET", "POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        //on recup les images
         $images=$form->get('images')->getData();
         //on boucle les images
         foreach ($images as $image) {
             //on génère un nouveau nom de fichier
             $fichier=md5(uniqid()).'.'.$image->guessExtension();
             //on copie dans uploads
             $image->move(
             $this->getParameter('images_directory'),
             $fichier
             );
             //stockage en BDD
             $img=new Images();
             $img->setName($fichier);
             $annonce->addImage($img);

         }

            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonces/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_show", methods={"GET"})
     */
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonces_edit", methods={"GET", "POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on recup les images
         $images=$form->get('images')->getData();
         //on boucle les images
         foreach ($images as $image) {
             //on génère un nouveau nom de fichier
             $fichier=md5(uniqid()).'.'.$image->guessExtension();
             //on copie dans uploads
             $image->move(
             $this->getParameter('images_directory'),
             $fichier
             );
             //stockage en BDD
             $img=new Images();
             $img->setName($fichier);
             $annonce->addImage($img);

         }
            $entityManager->flush();

            return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_delete", methods={"POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
    }
 /**
    * @Route("/supprime/image/{id}", name="annonces_delete_image")
    * IsGranted("ROLE_ADMIN")
    */
    public function deleteImage(Images $image,Request $request){
       
         $nom= $image->getName(); 
           unlink($this->getParameter('images_directory').'/'.$nom);
          $em =$this->getDoctrine()->getManager();
            $em->remove($image);
         $em->flush();
        return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
           }


}