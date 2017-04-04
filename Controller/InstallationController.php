<?php


namespace Elemecca\HipchatBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InstallationController extends Controller
{
    public function descriptorAction()
    {
        return new Response(
            $this->getDescriptor(true),
            Response::HTTP_OK,
            [ 'Content-Type' => 'application/json' ]
        );
    }

    public function redirectAction()
    {
        return $this->redirect(
            "https://www.hipchat.com/addons/install"
            . "?url=data:application/json;base64,"
            . rawurlencode(base64_encode($this->getDescriptor(false)))
        );
    }

    private function getDescriptor($server)
    {
        $config = $this->getParameter('elemecca_hipchat.descriptor');
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
        if ($server) {
            $install['callbackUrl'] =
                $this->generateAbsoluteUrl('elemecca_hipchat_install_server');
            $install['updateCallbackUrl'] = $install['callbackUrl'];
        } else {
            $install['installedUrl'] =
                $this->generateAbsoluteUrl('elemecca_hipchat_install_client');
            $install['uninstalledUrl'] =
                $this->generateAbsoluteUrl('elemecca_hipchat_remove_client');
            $install['updatedUrl'] = $install['installedUrl'];
        }


        $desc['capabilities']['hipchatApiConsumer'] = [
            'scopes' => [
                'send_notification',
            ],
        ];


        return json_encode(
            $desc,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function generateAbsoluteUrl($key) {
        return $this->generateUrl($key, [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function maybeGenerateUrl($url)
    {
        if (false !== strpos($url, '://')) {
            return $url;
        } else {
            return $this->generateAbsoluteUrl($url);
        }
    }

    public function installServerAction(Request $request)
    {
        dump($request->query, $request->request);
        return new Response();
    }

    public function installClientAction(Request $request)
    {
        dump($request->query, $request->request);
        return new Response();
    }

    public function removeServerAction(Request $request, $id)
    {
        dump($request->query, $request->request);
        return new Response();
    }

    public function removeClientAction(Request $request)
    {
        dump($request->query, $request->request);
        return new Response();
    }
}