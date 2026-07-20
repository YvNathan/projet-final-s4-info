<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class OperateurController extends BaseController
{
    public function index(): string
    {
        $operateurModel = new OperateurModel();

        return view('operateur/operateur', [
            'titre'      => 'Opérateurs',
            'espace'     => 'operateur',
            'operateurs' => $operateurModel->getAll(),
        ]);
    }

    public function store()
    {
        $operateurModel = new OperateurModel();

        $nom = $this->request->getPost('nom');
        $pctCommission = (float) $this->request->getPost('pct_commission');

        if ($operateurModel->ajouterOperateur($nom, $pctCommission) === false) {
            session()->setFlashdata('error', implode(' ', $operateurModel->errors()));

            return redirect()->to('operateur/operateurs');
        }

        session()->setFlashdata('success', 'Opérateur ajouté avec succès.');

        return redirect()->to('operateur/operateurs');
    }

    public function update($id)
    {
        $operateurModel = new OperateurModel();

        $nom = $this->request->getPost('nom');
        $pctCommission = (float) $this->request->getPost('pct_commission');

        try {
            if ($operateurModel->modify((int) $id, $nom, $pctCommission) === false) {
                session()->setFlashdata('error', implode(' ', $operateurModel->errors()));

                return redirect()->to('operateur/operateurs');
            }
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/operateurs');
        }

        session()->setFlashdata('success', 'Opérateur modifié avec succès.');

        return redirect()->to('operateur/operateurs');
    }

    public function delete($id)
    {
        $operateurModel = new OperateurModel();

        try {
            $operateurModel->delete((int) $id);
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/operateurs');
        }

        session()->setFlashdata('success', 'Opérateur supprimé avec succès.');

        return redirect()->to('operateur/operateurs');
    }
}
