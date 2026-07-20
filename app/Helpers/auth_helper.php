<?php
if (! function_exists('user')) {
    function user(?string $champ = null)
    {
        $session = session();
        if (! $session->get('isLoggedIn')) return null;
        return $champ ? $session->get($champ) : $session->get();
    }
}
