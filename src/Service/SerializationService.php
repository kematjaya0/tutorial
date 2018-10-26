<?php

declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\RequestStack;

class SerializationService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * SerializationService constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return SerializationContext
     */
    public function createBaseOnRequest(): SerializationContext
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (false === !!$currentRequest) {
            return new SerializationContext();
        }

        //$expand = explode(',', $currentRequest->query->get('expand', []));

        return $this->createWithGroups([]);
    }

    /**
     * @param array $groups
     *
     * @return SerializationContext
     */
    public function createWithGroups(array $groups): SerializationContext
    {
        $serializationContext = SerializationContext::create();
        $serializationContext->setGroups(array_merge(['Default'], $groups));

        return $serializationContext;
    }
}
