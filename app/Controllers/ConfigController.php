<?php

namespace App\Controllers;

use App\Models\ConfigModel;
use App\Models\OperateurModel;

class ConfigController extends BaseController
{
    public function index(): string
    {
        $configModel = new ConfigModel();
        $operateurModel = new OperateurModel();

        $operateurs = $operateurModel->getAll();
        $nomsById = array_column($operateurs, 'nom', 'id');

        return view('operateur/config', [
            'titre'      => 'Préfixes',
            'espace'     => 'operateur',
            'prefixes'   => $configModel->orderBy('prefixe', 'ASC')->findAll(),
            'operateurs' => $operateurs,
            'nomsById'   => $nomsById,
        ]);
    }

    public function store()
    {
        $configModel = new ConfigModel();

        $prefixe = $this->request->getPost('prefixe');
        $idOperateur = (int) $this->request->getPost('id_operateur');

        if ($configModel->ajouterPrefixe($prefixe, $idOperateur) === false) {
            session()->setFlashdata('error', implode(' ', $configModel->errors()));

            return redirect()->to('operateur/config');
        }

        session()->setFlashdata('success', 'Préfixe ajouté avec succès.');

        return redirect()->to('operateur/config');
    }

    public function update($id)
    {
        $configModel = new ConfigModel();

        $prefixe = $this->request->getPost('prefixe');
        $idOperateur = (int) $this->request->getPost('id_operateur');

        try {
            if ($configModel->modifierPrefixe((int) $id, $prefixe, $idOperateur) === false) {
                session()->setFlashdata('error', implode(' ', $configModel->errors()));

                return redirect()->to('operateur/config');
            }
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/config');
        }

        session()->setFlashdata('success', 'Préfixe modifié avec succès.');

        return redirect()->to('operateur/config');
    }

    public function delete($id)
    {
        $configModel = new ConfigModel();

        try {
            $configModel->supprimerPrefixe((int) $id);
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());

            return redirect()->to('operateur/config');
        }

        session()->setFlashdata('success', 'Préfixe supprimé avec succès.');

        return redirect()->to('operateur/config');
    }
}
