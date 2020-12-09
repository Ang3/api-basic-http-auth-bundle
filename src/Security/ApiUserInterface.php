<?php

namespace Ang3\Bundle\ApiBasicHttpAuthBundle\Security;

interface ApiUserInterface
{
    public function getApiKey(): ?string;
}
