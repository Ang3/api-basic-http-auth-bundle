<?php

namespace Ang3\Bundle\ApiBasicHttpAuthBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface LockableUserInterface extends BaseUserInterface
{
    /**
     * Returns TRUE if the user account is disabled.
     */
    public function isDisabled(): bool;
}
