<?php
class User
{
    private $mail = null;
    private $password = null;
    private $pdp = null;
    private $role = null;

    function __construct($mail, $password, $pdp, $role)
    {
        $this->mail = $mail;
        $this->password = $password;
        $this->pdp = $pdp;
        $this->role = $role;
    }

    function getMail()
    {
        return $this->mail;
    }

    function getPassword()
    {
        return $this->password;
    }

    function getPdp()
    {
        return $this->pdp;
    }

    function getRole()
    {
        return $this->role;
    }

    function setMail(string $mail)
    {
        $this->mail = $mail;
    }

    function setPassword(string $password)
    {
        $this->password = $password;
    }

    function setPdp(string $pdp)
    {
        $this->pdp = $pdp;
    }

    function setRole(string $role)
    {
        $this->role = $role;
    }
}
?>
