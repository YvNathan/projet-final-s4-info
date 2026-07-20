<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\ConfigModel;

class AuthClientController extends BaseController
{
    function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('client/login');
    }

    function doLogin()
    {
        $numero = trim($this->request->getPost('numero'));

        $clientModel = new ClientModel();
        $configModel = new ConfigModel();

        $motif = '/^(?:\+261|0)\s?(\d{2})(?:[\s]?\d{2})(?:[\s]?\d{3})(?:[\s]?\d{2})$/';

        if (!preg_match($motif, $numero, $match)) {
            session()->setFlashdata('error', 'Numéro de téléphone invalide.');
            return redirect()->back();
        }

        $prefix = $match[1];



        if (str_starts_with($numero, '+261')) {
            $numero = substr($numero, 4);
        }

        $numero = preg_replace('/\s+/', '', $numero);

        $client = $clientModel->where('numero', $numero)->first();

        if (!$client) {
            $prefixExiste = $configModel
                ->where('prefixe', $prefix)
                ->first();

            if (!$prefixExiste) {
                session()->setFlashdata('error', 'Cet opérateur ne prend pas en charge ce numéro. Prefix invalide');
                return redirect()->back();
            }
            $clientModel->insert([
                'nom' => 'Client',
                'numero' => $numero
            ]);

            $client = $clientModel->find($clientModel->getInsertID());
        }

        session()->regenerate(true);

        session()->set([
            'client_id' => $client['id'],
            'client_nom' => $client['nom'],
            'client_numero' => $client['numero'],
            'isLoggedIn' => true,
        ]);

        session()->setFlashdata('success', 'Connexion réussie.');

        return redirect()->to('/home');
    }

    function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
