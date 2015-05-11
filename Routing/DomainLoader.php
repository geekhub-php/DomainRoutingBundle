<?php

namespace GeekHub\DomainRoutingBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader;
use Psr\Log\LoggerInterface;

class DomainLoader extends DelegatingLoader implements LoaderInterface
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var  string */
    protected $kernelRootDir;

    /** @var  string */
    protected $domainRoutingRelations;

    public function __construct(
        RequestStack $requestStack,
        ControllerNameParser $parser,
        LoggerInterface $logger = null,
        LoaderResolver $routingLoader,
        $kernelRootDir,
        $domainRoutingRelations
    )
    {
        $this->requestStack = $requestStack;
        $this->kernelRootDir = $kernelRootDir;
        $this->domainRoutingRelations = $domainRoutingRelations;

        parent::__construct($parser, $logger, $routingLoader);
    }

    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $domainSpecificRoutes = new RouteCollection();

        if ($request = $this->requestStack->getMasterRequest()) {
            $domain = $request->getHttpHost();
        } elseif (!$domain = $this->getDomain($_SERVER['argv'])) {
            return $domainSpecificRoutes;
        }

        if (!array_key_exists($domain, $this->domainRoutingRelations)) {
            return $domainSpecificRoutes;
        }

        foreach ($this->domainRoutingRelations[$domain] as $routeResource) {
            $collection = parent::load($this->kernelRootDir . '/' . $routeResource);

            foreach ($collection as $name => $route) {
                $domainSpecificRoutes->add($name, $route);
            }
        }

        return $domainSpecificRoutes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'domain_routing' === $type;
    }

    protected function getDomain(array $parameters)
    {
        foreach ($parameters as $parameter) {
            if (0 === strpos($parameter, '--hostname')) {
                if (false !== $pos = strpos($parameter, '=')) {
                    return substr($parameter, $pos + 1);
                }
            }
        }

        return false;
    }
}
