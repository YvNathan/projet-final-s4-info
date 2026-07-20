<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

class SituationController extends BaseController
{
    public function index(): string
    {
        $transactionModel = new TransactionModel();
        $clientModel = new ClientModel();
        $typeOperationModel = new TypeOperationModel();

        $idTypeOperationFiltre = $this->request->getGet('type');
        $idTypeOperationFiltre = ($idTypeOperationFiltre !== null && $idTypeOperationFiltre !== '')
            ? (int) $idTypeOperationFiltre
            : null;

        $clients = $clientModel->getSituationsClients();
        $gains = $transactionModel->getSituationGains($idTypeOperationFiltre);

        return view('operateur/situation', [
            'titre'                 => 'Situation',
            'espace'                => 'operateur',
            'gains'                 => $gains,
            'clients'               => $clients,
            'pager'                 => $clientModel->pager,
            'typesOperation'        => $typeOperationModel->findAll(),
            'idTypeOperationFiltre' => $idTypeOperationFiltre,
        ]);
    }
}
