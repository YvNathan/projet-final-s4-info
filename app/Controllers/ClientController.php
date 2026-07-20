<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;

class ClientController extends BaseController
{
   public function index()
    {
        $clientModel = new ClientModel();

        $client = $clientModel->find(session()->get('client_id'));

        return view('client/home', [
            'client' => $client
        ]);
    }

    public function doDepot()
{
    $montant = (float)$this->request->getPost('montant');

    $transactionModel = new TransactionModel();

    try {
        $transactionModel->createDepot(
            session()->get('client_numero'),
            date('Y-m-d H:i:s'),
            $montant
        );

        return redirect()->to('/home')
            ->with('success', 'Dépôt effectué avec succès.');
    } catch (\Throwable $e) {
        return redirect()->to('/home')
            ->with('error', $e->getMessage());
    }
}

public function doRetrait()
{
    $montant = (float)$this->request->getPost('montant');

    $transactionModel = new TransactionModel();

    try {
        $transactionModel->createRetrait(
            session()->get('client_numero'),
            date('Y-m-d H:i:s'),
            $montant
        );

        return redirect()->to('/home')
            ->with('success', 'Retrait effectué avec succès.');
    } catch (\Throwable $e) {
        return redirect()->to('/home')
            ->with('error', $e->getMessage());
    }
}

public function doTransfert()
{
    $montant = (float)$this->request->getPost('montant');
    $numero = $this->request->getPost('numero');

    $transactionModel = new TransactionModel();

    try {
        $transactionModel->createTransfert(
            session()->get('client_numero'),
            date('Y-m-d H:i:s'),
            $montant,
            $numero
        );

        return redirect()->to('/home')
            ->with('success', 'Transfert effectué avec succès.');
    } catch (\Throwable $e) {
        return redirect()->to('/home')
            ->with('error', $e->getMessage());
    }
}
}
