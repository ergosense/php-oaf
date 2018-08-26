<?php
namespace OAF\Auth;

class Context
{
    public function __construct()
    {
        $this->user = new GuestUser;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}