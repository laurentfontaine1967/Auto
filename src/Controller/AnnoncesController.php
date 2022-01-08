<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/annonces")
*/
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="annonces_index", methods={"GET"})
     */
    public function index( Request $request,AnnoncesRepository $annoncesRepository ,PaginatorInterface $paginatorInterface): Response
    {

        $data= $annoncesRepository->findAll();
        $annonces = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );


        return $this->render("annonces/index.html.twig", [
            'annonces' => $annonces
        ]);

        
        // return $this->render('annonces/index.html.twig', [
        //     'annonces' => $annoncesRepository->findAll(),
        // ]);



    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->GetUser();
       
        if(!$user)
        {
            return $this->redirectToRoute('annonces_index');
        }

        $userRole =$user->getRoles();
       
        if($userRole[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute('annonces_index');
        }

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
     */
    public function edit(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {    
        
        $user = $this->GetUser();
        if(!$user)
        {
            return $this->redirectToRoute('annonces_index');
        }

        $UserRole =$user->getRoles();
        if($UserRole != "ROLE_ADMIN")
        {
            return $this->redirectToRoute('annonces_index');
        }
   

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
     */
    public function delete(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        $user = $this->GetUser();
        if(!$user)
        {
            return $this->redirectToRoute('annonces_index');
        }

        $UserRole =$user->getRoles();
        if($UserRole !== "ROLE-ADMIN")
        {
            return $this->redirectToRoute('annonces_index');
        }

        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
    }
 /**
    * @Route("/supprime/image/{id}", name="annonces_delete_image")
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