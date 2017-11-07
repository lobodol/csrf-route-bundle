<?php

namespace Genedys\CsrfRouteBundle\Routing\TokenProvider;

use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Genedys\CsrfRouteBundle\Routing\TokenProviderInterface;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
abstract class AbstractTokenProvider implements TokenProviderInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return CsrfToken
     */
    protected function getDefaultToken()
    {
        $token = new CsrfToken();
        $token->setToken($this->fieldName);
        $token->setIntention(null);
        $token->setMethods('GET');

        return $token;
    }

    /**
     * @param true|array $option
     * @return CsrfToken|null
     */
    protected function getTokenFromOption($option)
    {
        if (true === $option) {
            return $this->getDefaultToken();
        }

        if (!is_array($option)) {
            return null;
        }

        $token = new CsrfToken();
        $token->setToken(array_key_exists('token', $option) ? $option['token'] : $this->fieldName);
        $token->setIntention(array_key_exists('intention', $option) ? $option['intention'] : null);
        $token->setMethods(array_key_exists('methods', $option) ? $option['methods'] : 'GET');

        return $token;
    }
}
