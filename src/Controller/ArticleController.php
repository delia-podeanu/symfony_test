<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\FileUploader;


use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;


class ArticleController extends AbstractController
{
    #[Route('/', name: 'article', methods:["GET"])]
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();  
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/new', name: 'create_article', methods:["GET","POST"])]
    public function create(Request $request, FileUploader $fileUploader): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class,$article);
        
        $form->handleRequest($request);

        $pictureFile = $form->get('pictureUrl')->getData();
        if ($pictureFile) {
            $pictureFileName = $fileUploader->upload($pictureFile);
            $article->setPictureUrl($pictureFileName);
        }

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article');
        }
        return $this->renderForm('article/create_article.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'edit_article', methods:["GET","POST"])]
    public function edit(Request $request, $id, FileUploader $fileUploader): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createForm(ArticleType::class,$article);
        
        $form->handleRequest($request);

        $pictureFile = $form->get('pictureUrl')->getData();
        if ($pictureFile) {
            $pictureFileName = $fileUploader->upload($pictureFile);
            $article->setPictureUrl($pictureFileName);
        }


        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article');
        }
        return $this->render('article/create_article.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'show_article', methods:["GET", "POST"])]
    public function show(Request $request,$id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        
        $error = null;
        $comment = new Comment();

        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['article'=>$id]);
        $form = $this->createForm(CommentType::class,$comment);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment = $form->getData();
            $comment -> setArticle($article);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }

        return $this->render('article/show_article.html.twig', [
            'article' => $article,
            'error' => $error,
            'form' => $form->createView(),
            'comments' => $comments,
        ]);
    }

    #[Route('/article/delete/{id}', name: 'delete_article', methods:["GET","DELETE"])]
    public function delete($id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();


       $response = new Response();
       return $response->send();
    }

    #[Route('/comment/delete/{id}', name: 'delete_comment', methods:["GET","DELETE"])]
    public function deleteComment($id): Response
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();


       $response = new Response();
       return $response->send();
    }
}
