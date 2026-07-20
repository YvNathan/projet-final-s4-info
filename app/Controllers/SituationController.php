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

        $clients = $clientModel->getSituationsClients();

        return view('operateur/situation', [
            'titre'     => 'Situation',
            'espace'    => 'operateur',
            'gainTotal' => $transactionModel->getSituationGain(),
            'clients'   => $clients,
            'pager'     => $clientModel->pager,
        ]);
    }
}
