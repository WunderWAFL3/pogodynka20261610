<?php

namespace App\Controller;

use App\Entity\WeatherRecord;
use App\Form\WeatherRecordType;
use App\Repository\WeatherRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weather/record')]
final class WeatherRecordController extends AbstractController
{
    #[Route(name: 'app_weather_record_index', methods: ['GET'])]
    public function index(WeatherRecordRepository $weatherRecordRepository): Response
    {
        return $this->render('weather_record/index.html.twig', [
            'weather_records' => $weatherRecordRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_record_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weatherRecord = new WeatherRecord();
        $form = $this->createForm(WeatherRecordType::class, $weatherRecord, [
            'validation_groups' => ['Default', 'create'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weatherRecord);
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_record/new.html.twig', [
            'weather_record' => $weatherRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_record_show', methods: ['GET'])]
    public function show(WeatherRecord $weatherRecord): Response
    {
        return $this->render('weather_record/show.html.twig', [
            'weather_record' => $weatherRecord,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WeatherRecord $weatherRecord, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeatherRecordType::class, $weatherRecord, [
            'validation_groups' => ['Default', 'edit'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_record/edit.html.twig', [
            'weather_record' => $weatherRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_record_delete', methods: ['POST'])]
    public function delete(Request $request, WeatherRecord $weatherRecord, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weatherRecord->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weatherRecord);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather_record_index', [], Response::HTTP_SEE_OTHER);
    }
}
