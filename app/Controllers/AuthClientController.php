<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;

class AuthClientController extends BaseController
{
    function login() {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('client/login');
    }
    function doLogin()
    {
        $numero = $this->request->getPost('numero');
        $clientModel = new ClientModel();

        $motif = '/^(?:\\+261|0)\\s?(20|32|33|34|37|38|39)(?:[\\s]?\\d{2})(?:[\\s]?\\d{3})(?:[\\s]?\\d{2})$/';

        if (preg_match($motif, $numero)) {
            if (str_starts_with($numero, '+261')) {
                $numero = substr($numero, 4);
            }
            $numero = preg_replace('/\\s+/', '', $numero);
            $client = $clientModel->where('numero', $numero)->first();
            if ($client) {
                session()->regenerate(true);
                session()->setFlashdata('success', 'Connexion réussie.');
                session()->set([
                    'client_id' => $client['id'],
                    'client_nom' => $client['nom'],
                    'client_numero' => $client['numero'],
                    'isLoggedIn' => true,
                ]);
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('error', 'Numéro de téléphone introuvable.');
                return redirect()->to('/login');
            }
        } else {
            session()->setFlashdata('error', 'Numéro de téléphone invalide.');
            return redirect()->back();
        }
    }

    function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
