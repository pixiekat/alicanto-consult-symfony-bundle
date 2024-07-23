<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Form;
use Pixiekat\AlicantoConsult\Services;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {

  public function __construct(
    private EntityManagerInterface $entityManager,
    private Logger $logger,
    private Services\UserManager $userManager,
  ) {}

  #[Route('/users', name: 'alicanto_consult_users_list')]
  public function list(
    Request $request
  ): Response {
    $sort = $request->query->get('sort') ?? 'id';
    $direction = $request->query->get('direction') ?? 'ASC';

    $users = $this->userManager->getAll($sort, $direction);
    return $this->render('@PixiekatAlicantoConsult/users/list.html.twig', [
      'users' => $users,
    ]);
  }

  #[Route('/user/add', name: 'alicanto_consult_users_add')]
  public function add_user(
    Request $request
  ): Response {
    $user = new Entity\User();
    $form = $this->createForm(Form\UserFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
      return $this->redirectToRoute('alicanto_consult_users_list');
    }

    if ($form->isSubmitted() && $form->isValid()) {
      $this->userManager->setUser($user);
      $result = $this->userManager->add($user);
      if (!$result) {
        $this->addFlash('error', 'Failed to add user');
        return $this->redirectToRoute('alicanto_consult_users_add');
      }
      $this->addFlash('success', 'User added successfully');
      return $this->redirectToRoute('alicanto_consult_users_list');
    }
    return $this->render('@PixiekatAlicantoConsult/users/add.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/user/{id}/edit', name: 'alicanto_consult_user_edit', requirements: ['id' => '\d+'])]
  public function edit_user(
    Entity\User $user,
    Request $request
  ): Response {
    $form = $this->createForm(Form\UserFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
      return $this->redirectToRoute('alicanto_consult_users_list');
    }

    if ($form->isSubmitted() && $form->isValid()) {
      try {
        $this->userManager->setUser($user);
        $result = $this->userManager->update($user);
        if (!$result) {
          $this->addFlash('error', 'Failed to update user');
          return $this->redirectToRoute('alicanto_consult_user_edit', ['id' => $user->getId()]);
        }
        $this->addFlash('success', 'User updated successfully');
        return $this->redirectToRoute('alicanto_consult_users_list');
      }
      catch (\Exception $e) {
        $this->logger->error('Failed to update user: ' . $e->getMessage());
        $this->addFlash('error', 'Failed to update user');
      }
    }
    return $this->render('@PixiekatAlicantoConsult/users/edit.html.twig', [
      'form' => $form->createView(),
      'user' => $user,
    ]);
  }

  #[Route('/user/{id}/delete', name: 'alicanto_consult_user_delete', requirements: ['id' => '\d+'])]
  public function delete_user(
    Entity\User $user,
    Request $request,
  ): Response {

    $form = $this->createFormBuilder()
      ->add('submit', FormTypes\SubmitType::class, [
        'label' => 'Delete',
        'attr' => [
          'class' => 'btn-danger',
        ],
      ])
      ->add('cancel', FormTypes\SubmitType::class, [
        'label' => 'Cancel',
        'attr' => [
          'class' => 'btn-link',
        ],
      ])
    ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->get('cancel')->isClicked()) {
        return $this->redirectToRoute('alicanto_consult_users_list');
      }
      if ($form->get('submit')->isClicked()) {
        try {
          $this->userManager->setUser($user);
          $result = $this->userManager->delete($user);
          if (!$result) {
            $this->addFlash('error', 'Failed to delete user: ' . $e->getMessage());
            return $this->redirectToRoute('alicanto_consult_user_list');
          }
          $this->addFlash('success', 'User deleted successfully');
        }
        catch (\Exception $e) {
          $this->logger->error('Failed to delete user: ' . $e->getMessage());
          $this->addFlash('error', 'Failed to delete user');
        }
        return $this->redirectToRoute('alicanto_consult_users_list');
      }
    }

    return $this->render('@PixiekatAlicantoConsult/users/delete.html.twig', [
      'form' => $form->createView(),
      'user' => $user,
    ]);
  }
}
