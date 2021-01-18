<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    /**
     * list all Tasks
     *
     * @Route("/tasks", name="task_list")
     * 
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findAll()]);
    }

    /**
     * Create new task
     *
     * @Route("/tasks/create", name="task_create")
     * 
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setAuthor($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * edit a Task
     *
     * @Route("/tasks/{id}/edit", name="task_edit", methods={"GET", "POST"})
     *
     * @param $id 
     * @param Request $request
     * @return Response
     */
    public function editTask(Task $task, Request $request, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskType::class, $task);             
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * Toogle task function
     * 
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     *
     * @param Task $task
     * @return Response
     */
    public function toggleTask(Task $task): Response
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        if($task->isDone())
        {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }
        else
        {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));

        }        

        return $this->redirectToRoute('task_list');
    }

    /**
     * Delete a task
     *
     * @Route("/tasks/{id}/delete", name="task_delete")
     * 
     * @param Task $task
     * @return Response
     */
    public function deleteTask(Task $task): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $task, 'Vous n\'êtes pas autorisé à supprimer cette tâche.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
