<?php

use Symfony\Component\HttpFoundation\Request;
use MyBooks\Domain\Author;
use MyBooks\Domain\Book;

// Home page
$app->get('/', function () use ($app)
{
    $books = $app['dao.book']->findAll();
    return $app['twig']->render('index.html.twig', array('books' => $books));
});


// Book details 
$app->match('/book/{id}', function ($id, Request $request) use ($app) {
    $book = $app['dao.book']->find($id);
    return $app['twig']->render('book.html.twig', array(
        'book' => $book));
});