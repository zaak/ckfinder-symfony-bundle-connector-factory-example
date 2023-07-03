<?php

namespace App\CustomConnectorFactory;

use CKSource\Bundle\CKFinderBundle\Factory\ConnectorFactory;
use Symfony\Component\HttpKernel\Kernel;

class CustomConnectorFactory extends ConnectorFactory
{
    public function getConnector()
    {
        if ($this->connectorInstance) {
            return $this->connectorInstance;
        }

        // Set cookie in the browser console
        // document.cookie = "foo=bar; expires=Thu, 18 Dec 2033 12:00:00 UTC; path=/";
        if (isset($_COOKIE['foo']))
        {
            $this->connectorConfig['resourceTypes'][] = array(
                'name'              => 'Another',
                'directory'         => 'another',
                'maxSize'           => 0,
                'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
                'deniedExtensions'  => '',
                'backend'           => 'default'
            );
        }

        $connector = new $this->connectorConfig['connectorClass']($this->connectorConfig);

        $connector['authentication'] = $this->authenticationService;

        if (Kernel::MAJOR_VERSION === 4) {
            $this->setupForV4Kernel($connector);
        }

        $this->connectorInstance = $connector;

        return $connector;
    }
}
