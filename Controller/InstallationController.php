<?php


namespace Elemecca\HipChatBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InstallationController extends Controller
{
    public function descriptorAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($this->generateDescriptor());
        return $response;
    }

    private function generateDescriptor()
    {
        $config = $this->getParameter('elemecca_hipchat.descriptor');
        $desc = [
            'name' => $config['name'],
            'description' => $config['description'],
            'links' => [
                'self' => $this->generateUrl('elemecca_hipchat_descriptor'),
            ],
            'apiVersion' => '1:1',
            'capabilities' => [
                'installable' => [
                    'callbackUrl' => $this->generateUrl('elemecca_hipchat_install'),
                ],
            ],
        ];

        if (isset($config['key'])) {
            $desc['key'] = $config['key'];
        }

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

        return json_encode($desc, JSON_UNESCAPED_UNICODE);
    }

    private function maybeGenerateUrl($url)
    {
        if (false !== strpos($url, '://')) {
            return $url;
        } else {
            return $this->generateUrl($url);
        }
    }

    public function installAction()
    {
    }

    public function removeAction($id)
    {
    }
}