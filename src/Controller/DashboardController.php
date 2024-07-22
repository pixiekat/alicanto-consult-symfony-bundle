<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsultBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController {

  public function __construct(private EntityManagerInterface $entityManager) {}

  #[Route('/', name: 'alicanto_consult_dashboard')]
  public function index(): Response {
    return $this->render('@PixiekatAlicantoConsultBundle/dashboard/index.html.twig', []);
  }
}
