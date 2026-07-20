<?php

namespace App\Controllers;

use App\Models\ConfigModel;

class ConfigController extends BaseController
{
    public function index(): string
    {
        $configModel = new ConfigModel();

        return view('operateur/config', [
            'titre'    => 'Préfixes',
            'espace'   => 'operateur',
            'prefixes' => $configModel->orderBy('prefixe', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $configModel = new ConfigModel();

        $prefixe = $this->request->getPost('prefixe');

        if ($configModel->ajouterPrefixe($prefixe) === false) {
            session()->setFlashdata('error', implode(' ', $configModel->errors()));

            return redirect()->to('operateur/config');
        }

        session()->setFlashdata('success', 'Préfixe ajouté avec succès.');

        return redirect()->to('operateur/config');
    }
}
