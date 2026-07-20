<?php

namespace App\Controllers;

use App\Models\FraisOperationModel;
use App\Models\TypeOperationModel;

class FraisOperationController extends BaseController
{
    public function index(): string
    {
        $fraisModel = new FraisOperationModel();
        $typeOperationModel = new TypeOperationModel();

        $idTypeOperationFiltre = $this->request->getGet('type');
        $idTypeOperationFiltre = ($idTypeOperationFiltre !== null && $idTypeOperationFiltre !== '')
            ? (int) $idTypeOperationFiltre
            : null;

        $baremes = $fraisModel->getAll(10, $idTypeOperationFiltre);
        $fraisModel->pager->only(['type']);

        $typesOperation = $typeOperationModel->findAll();
        $libellesById = array_column($typesOperation, 'libelle', 'id');

        return view('operateur/frais', [
            'titre'                 => 'Barèmes de frais',
            'espace'                => 'operateur',
            'baremes'               => $baremes,
            'pager'                 => $fraisModel->pager,
            'typesOperation'        => $typesOperation,
            'libellesById'          => $libellesById,
            'idTypeOperationFiltre' => $idTypeOperationFiltre,
        ]);
    }

    public function store()
    {
        $fraisModel = new FraisOperationModel();

        $idTypeOperation = (int) $this->request->getPost('id_type_operation');
        $borneMin = (float) $this->request->getPost('borne_min');
        $borneMax = (float) $this->request->getPost('borne_max');
        $frais = (float) $this->request->getPost('frais');

        try {
            if ($fraisModel->ajouterBareme($idTypeOperation, $borneMin, $borneMax, $frais) === false) {
                session()->setFlashdata('error', implode(' ', $fraisModel->errors()));

                return redirect()->to('operateur/frais');
            }
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/frais');
        }

        session()->setFlashdata('success', 'Tranche de frais ajoutée avec succès.');

        return redirect()->to('operateur/frais');
    }

    public function update($id)
    {
        $fraisModel = new FraisOperationModel();

        $borneMin = (float) $this->request->getPost('borne_min');
        $borneMax = (float) $this->request->getPost('borne_max');
        $frais = (float) $this->request->getPost('frais');

        try {
            if ($fraisModel->modifierBareme((int) $id, $borneMin, $borneMax, $frais) === false) {
                session()->setFlashdata('error', implode(' ', $fraisModel->errors()));

                return redirect()->to('operateur/frais');
            }
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/frais');
        }

        session()->setFlashdata('success', 'Tranche de frais modifiée avec succès.');

        return redirect()->to('operateur/frais');
    }

    public function delete($id)
    {
        $fraisModel = new FraisOperationModel();

        try {
            $fraisModel->supprimerBareme((int) $id);
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/frais');
        }

        session()->setFlashdata('success', 'Tranche de frais supprimée avec succès.');

        return redirect()->to('operateur/frais');
    }
}
