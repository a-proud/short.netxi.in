<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ShortUrl;
use App\Entity\EntityRelation;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EntityRelationService;
use App\Form\ShortUrlType;

use Doctrine\DBAL\Connection;

class MainController extends AbstractController
{

//#[Route('/blog/{id}', name: 'blog_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[Route('/', name: 'app_main_index')]
    public function index(Request $request, EntityManagerInterface $em, EntityRelationService $entityRelationService): Response
    {
        $shortUrl = new ShortUrl();
        $conn = $em->getConnection();
        $viewData = [];
        
        $form = $this->createForm(ShortUrlType::class, new ShortUrl(), [
            'attr' => [
                'class' => 'responseformat responseformat-json',
                'data-submithandler' => 'shortUrlFormSubmit',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shortUrl->setHash(bin2hex(random_bytes(4)));
            $shortUrl->setTtl(90);
            $shortUrl->setUrl($form->get('url')->getData());
            $shortUrl->setCreated(new \DateTimeImmutable());
            $em->persist($shortUrl);
            $em->flush();

            $viewData['shortUrl'] = $request->getSchemeAndHttpHost().'/'.$shortUrl->getHash();

        }

        $viewData['title'] = 'Short URL Service';
        $viewData['favicon_href'] = '/favicon.ico';
        $viewData['htmllang'] = 'en';
        $viewData['form'] = $form->createView();

        return $this->renderRequestedFormat('main/components/url_short_form.html.twig', $viewData);
    }

    public function renderRequestedFormat(string $view, array $parameters = []): Response
    {
        $format = $this->container->get('request_stack')
                        ->getCurrentRequest()->headers->get('Accept');

        if ($format === 'application/json') {
            foreach ($parameters as &$param) {
                if (is_object($param) && !($param instanceof \JsonSerializable)) {
                    $param = null;
                }
            }
            unset($param);
            return $this->json($parameters);
        }

        return $this->render($view, $parameters);
    }


    #[Route('/{hash}', name: 'short_url_redirect', requirements: ['hash' => '[a-zA-Z0-9]{6,}'], priority: -10)]
    public function shortUrlRedirect(string $hash, EntityManagerInterface $em): Response
    {
        $shortUrl = $em->getRepository(ShortUrl::class)->findOneBy(['hash' => $hash]);

        if (!$shortUrl) {
            throw $this->createNotFoundException('Link not found');
        }

        return $this->redirect($shortUrl->getUrl());
    }

}