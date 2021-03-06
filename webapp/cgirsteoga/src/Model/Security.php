<?php

namespace Webapp\Model;


class Security
{
    const USER_ID = 'user_id';

    /** @var Session */
    protected $session;
    /** @var UserDAO */
    private $userDAO;

    /**
     * Security constructor.
     * @param Session $session
     * @param UserDAO $userDAO
     */
    public function __construct(Session $session, UserDAO $userDAO)
    {
        $this->session = $session;
        $this->userDAO = $userDAO;
    }

    /**
     * @param string $userName
     * @param string $password
     * @return boolean
     */
    public function login(string $userName, string $password)
    {
        $user = $this->userDAO->getByUserName($userName);

        if (!$user) {
            return false;
        }

        if ($user->getPassword() !== $password) {
            return false;
        }

        $this->session->set(static::USER_ID, $user->getId());

        return true;
    }


    public function logout()
    {
        $this->session->remove(static::USER_ID);
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->session->has(static::USER_ID);
    }

    /**
     * @return int|null
     */
    public function getLoggedUserId()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->session->get(static::USER_ID);
    }

    /**
     * @return null|User
     */
    public function getLoggedUser()
    {
        if (!$this->getLoggedUserId()) {
            return null;
        }

        return $this->userDAO->getById($this->getLoggedUserId());
    }

}
