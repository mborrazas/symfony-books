<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @Route("/books", name="books_get")
     */
    public function list(Request $request)
    {
        $this->logger->info('List action called');
        $title = $request->get('title', 'Alegria');
        $response = new JsonResponse();
        $response->setData(["hola" => true]);
        return $response;
    }

    /*
     * @Route("/book/create", name="create_book")
     */

    public function createBook(Request $request, EntityManagerInterface $em){
        $book = new Book();
        $title = $request->get('title', null);
        $response = new JsonResponse();
        if(empty($title)){
            $response->setData([
                'success' => false,
                'error' => 'Title cannot be empty',
                'data' => null
            ]);
            return $response;
        }
        $book->setTitle($title);
        $em->persist($book);
        $em->flush();
        $response->setData([
           'success' => true,
           'data' => [
               [
                   'id' => $book->getId(),
                   'title' => $book->getTitle()
               ]
           ]
        ]);
        return $response;
    }
}
