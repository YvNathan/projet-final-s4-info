<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class ReportController extends BaseController
{
    public function index(): string
    {
        $transactionModel = new TransactionModel();

        return view('operateur/report', [
            'titre'    => 'Montants à envoyer',
            'espace'   => 'operateur',
            'montants' => $transactionModel->getMontantsAEnvoyerAutresOperateurs(),
        ]);
    }
}
