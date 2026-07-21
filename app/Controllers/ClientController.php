<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\FraisOperationModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

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

    public function modifEpargne()
    {
        $clientModel = new ClientModel();
        $valeur = (float)$this->request->getPost('valeur');

        try {
            if ($clientModel->modifierClientEpargne(session()->get('client_id'), $valeur ) === false) {
                session()->setFlashdata('error', implode(' ', $clientModel->errors()));

                return redirect()->to('home');
            }
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('home');
        }

        session()->setFlashdata('success', 'Epargne modifié avec succès.');

        return redirect()->to('home');
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
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') === '1';

        if ($inclureFraisRetrait) {
            $fraisOperationModel = new FraisOperationModel();
            $typeOperationModel = new TypeOperationModel();
            $idTypeOperationRetrait = $typeOperationModel->getIdByLibelle('retrait');
            $montant += $fraisOperationModel->getFrais($idTypeOperationRetrait, $montant);
        }

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
    public function doTransfertMultiple()
    {
        $montant = (float)$this->request->getPost('montant');
        $numeros = $this->request->getPost('numeros') ?? [];
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') === '1';

        if (is_string($numeros)) {
            $numeros = array_filter(array_map('trim', explode(',', $numeros)));
        }

        $transactionModel = new TransactionModel();

        try {
            $transactionModel->createTransfertMultiple(
                session()->get('client_numero'),
                date('Y-m-d H:i:s'),
                $montant,
                $numeros,
                $inclureFraisRetrait
            );

            return redirect()->to('/home')
                ->with('success', 'Transfert multiple effectué avec succès.');
        } catch (\Throwable $e) {
            return redirect()->to('/home')
                ->with('error', $e->getMessage());
        }
    }

    public function historique()
    {
        $transactionModel = new TransactionModel();

        $historique = $transactionModel->getHistoriqueTransactions(
            session()->get('client_numero')
        );

        return view('client/historique', [
            'historique' => $historique
        ]);
    }
}
