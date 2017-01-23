<?php

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @param EngineInterface $templateEngine
     */
    public function __construct(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * @param Request $request
     * @param string $page
     * @return Response
     */
    public function showAction(Request $request, $page)
    {
        return $this->templateEngine->renderResponse(sprintf('default/%s.html.twig', $page));
    }
}
