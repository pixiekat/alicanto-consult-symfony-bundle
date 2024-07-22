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

class GroupController extends AbstractController {

  public function __construct(
    private EntityManagerInterface $entityManager,
    private Logger $logger,
    private Services\GroupManager $groupManager,
  ) {}

  #[Route('/groups', name: 'alicanto_consult_groups_list')]
  public function list(
    Request $request
  ): Response {
    $sort = $request->query->get('sort') ?? 'name';
    $direction = $request->query->get('direction') ?? 'ASC';

    $groups = $this->groupManager->getAll($sort, $direction);
    return $this->render('@PixiekatAlicantoConsult/groups/list.html.twig', [
      'groups' => $groups,
    ]);
  }

  #[Route('/group/add', name: 'alicanto_consult_groups_add')]
  public function add_group(
    Request $request
  ): Response {
    $group = new Entity\Group();
    $form = $this->createForm(Form\GroupFormType::class, $group);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $result = $this->groupManager->add($group);
      if (!$result) {
        $this->addFlash('error', 'Failed to add group');
        return $this->redirectToRoute('alicanto_consult_groups_add');
      }
      $this->addFlash('success', 'Group added successfully');
      return $this->redirectToRoute('alicanto_consult_groups_list');
    }
    return $this->render('@PixiekatAlicantoConsult/groups/add.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/group/{id}/edit', name: 'alicanto_consult_groups_edit', requirements: ['id' => '\d+'])]
  public function edit_group(
    Entity\Group $group,
    Request $request
  ): Response {
    $form = $this->createForm(Form\GroupFormType::class, $group);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      try {
        $result = $this->groupManager->update($group);
        if (!$result) {
          $this->addFlash('error', 'Failed to update group');
          return $this->redirectToRoute('alicanto_consult_groups_edit', ['id' => $group->getId()]);
        }
        $this->addFlash('success', 'Group updated successfully');
        return $this->redirectToRoute('alicanto_consult_groups_list');
      }
      catch (\Exception $e) {
        $this->logger->error('Failed to update group: ' . $e->getMessage());
        $this->addFlash('error', 'Failed to update group');
      }
    }
    return $this->render('@PixiekatAlicantoConsult/groups/edit.html.twig', [
      'form' => $form->createView(),
      'group' => $group,
    ]);
  }

  #[Route('/group/{id}/delete', name: 'alicanto_consult_groups_delete', requirements: ['id' => '\d+'])]
  public function delete_group(
    Entity\Group $group,
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
        return $this->redirectToRoute('alicanto_consult_groups_list');
      }
      if ($form->get('submit')->isClicked()) {
        try {
          $result = $this->groupManager->delete($group);
          if (!$result) {
            $this->addFlash('error', 'Failed to delete group');
            return $this->redirectToRoute('alicanto_consult_groups_list');
          }
          $this->addFlash('success', 'Group deleted successfully');
        }
        catch (\Exception $e) {
          $this->logger->error('Failed to delete group: ' . $e->getMessage());
          $this->addFlash('error', 'Failed to delete group');
        }
        return $this->redirectToRoute('alicanto_consult_groups_list');
      }
    }

    return $this->render('@PixiekatAlicantoConsult/groups/delete.html.twig', [
      'form' => $form->createView(),
      'group' => $group,
    ]);
  }
}
