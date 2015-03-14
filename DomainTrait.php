<?php

namespace GeekHub\DomainRoutingBundle;

trait DomainTrait
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @param string $domain Http domain name
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getCacheDir()
    {
        return $this->rootDir.'/cache/'. $this->domain . '/' . $this->environment;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getLogDir()
    {
        return $this->rootDir.'/logs/'. $this->domain;
    }
}
