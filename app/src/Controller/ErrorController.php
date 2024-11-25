<?php 

namespace Signature\Controller;

class ErrorController extends AbstractController
{
    public function notFound()
    {
        $this->render('404.html.twig');
    }
} 