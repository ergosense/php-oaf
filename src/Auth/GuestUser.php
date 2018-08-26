<?php
namespace OAF\Auth;

class GuestUser implements UserInterface
{
    public function id()
    {
        return -1;
    }

    public function username()
    {
        return 'guest';
    }
}