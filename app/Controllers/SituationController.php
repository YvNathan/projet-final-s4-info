<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;

class SituationController extends BaseController
{
    public function index(): string
    {
        $transactionModel = new TransactionModel();
        $clientModel = new ClientModel();

        return view('operateur/situation', [
            'titre'     => 'Situation',
            'gainTotal' => $transactionModel->getSituationGain(),
            'clients'   => $clientModel->getSituationsClients(),
        ]);
    }
}
