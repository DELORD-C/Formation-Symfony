<?php

namespace App\Controller\DataTables;

use App\Repository\UserRepository;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DTController extends AbstractController
{
    public function showAction(Request $request, DataTableFactory $dataTableFactory, UserRepository $rep): Response
    {
//        $table = $dataTableFactory->create()
//            ->add('id', TextColumn::class)
//            ->add('email', TextColumn::class)
//            ->createAdapter(ORMAdapter::class, [
//                'entity' => User::class,
//        ])
//        ->handleRequest($request);

        $table = $dataTableFactory->create()
            ->add('firstName', TextColumn::class)
            ->add('lastName', TextColumn::class)
            ->createAdapter(ArrayAdapter::class, [
                ['firstName' => 'Donald', 'lastName' => 'Trump'],
                ['firstName' => 'Barack', 'lastName' => 'Obama'],
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('dataTables/list.html.twig', ['datatable' => $table]);
    }
}