<?php

namespace Elemecca\HipchatBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Elemecca\HipchatBundle\Model\Installation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InstallationManager
{
    private $router;
    private $model;
    private $config;

    public function __construct(
        UrlGeneratorInterface $router,
        ObjectManager $model,
        array $config
    ) {
        $this->router = $router;
        $this->model  = $model;
        $this->config = $config;
    }


    public function getDescriptor($isServer)
    {
        $config = $this->config;
        $desc = [
            'key' => $config['key'],
            'name' => $config['name'],
            'description' => $config['description'],
            'links' => [
                'self' => $this->generateUrl('elemecca_hipchat_descriptor'),
            ],
        ];

        if (isset($config['vendor'])) {
            $desc['vendor'] = $config['vendor'];
            if (isset($desc['vendor']['url'])) {
                $desc['vendor']['url'] =
                    $this->maybeGenerateUrl($desc['vendor']['url']);
            }
        }

        if (isset($config['homepage'])) {
            $desc['links']['homepage'] =
                $this->maybeGenerateUrl($config['homepage']);
        }

        $desc['apiVersion'] = '1:1';
        $desc['capabilities'] = [];


        $desc['capabilities']['installable'] = [
            'allowGlobal' => $config['allow_global'],
            'allowRoom' => $config['allow_room'],
        ];
        $install =& $desc['capabilities']['installable'];
        if ($isServer) {
            $install['callbackUrl'] =
                $this->generateUrl('elemecca_hipchat_install_server');
            $install['updateCallbackUrl'] = $install['callbackUrl'];
        } else {
            $install['installedUrl'] =
                $this->generateUrl('elemecca_hipchat_install_client');
            $install['uninstalledUrl'] =
                $this->generateUrl('elemecca_hipchat_remove_client');
            $install['updatedUrl'] = $install['installedUrl'];
        }


        $desc['capabilities']['hipchatApiConsumer'] = [
            'scopes' => [
                'send_notification',
            ],
        ];

        return $desc;
    }

    public function getDescriptorJson($isServer)
    {
        return json_encode(
            $this->getDescriptor($isServer),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function generateUrl($key) {
        return $this->router->generate($key, [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function maybeGenerateUrl($url)
    {
        if (false !== strpos($url, '://')) {
            return $url;
        } else {
            return $this->generateUrl($url);
        }
    }


    private function installableError($msg)
    {
        return new BadRequestHttpException(
            "received invalid installable JSON: $msg"
        );
    }

    private function parseInstallableJson($json)
    {
        if (is_array($json)) {
            $result = $json;
        } elseif (is_string($json)) {
            $result = json_decode($json, true);
            if (JSON_ERROR_NONE != json_last_error()) {
                throw $this->installableError(json_last_error_msg());
            }
        } else {
            throw new \LogicException('$json must be an array or JSON string');
        }

        if (!is_array($result)) {
            throw $this->installableError('root element must be an object');
        }

        if (empty($result['oauthId'])) {
            throw $this->installableError('oauthId property is required');
        }

        if (empty($result['oauthSecret'])) {
            throw $this->installableError('oauthSecret property is required');
        }

        if (empty($result['capabilitiesUrl'])) {
            throw $this->installableError('capabilitiesUrl property is required');
        }

        if (empty($result['groupId'])) {
            throw $this->installableError('groupId property is required');
        }

        return $result;
    }

    public function install($json)
    {
        $installable = $this->parseInstallableJson($json);

        $install = $this->model
            ->getRepository('ElemeccaHipchat:Installation')
            ->find($installable['oauthId']);
        if (!$install) {
            $install = new Installation(
                $installable['oauthId'],
                $installable['oauthSecret'],
                $installable['groupId'],
                $installable['roomId'] ?: null
            );
            $this->model->persist($install);
        }

        $this->model->flush();
    }
}
