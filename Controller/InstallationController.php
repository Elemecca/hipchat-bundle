<?php


namespace Elemecca\HipchatBundle\Controller;


use Elemecca\HipchatBundle\Model\Installation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InstallationController extends Controller
{
    public function descriptorAction()
    {
        $desc = $this
            ->get('elemecca_hipchat.installation_manager')
            ->getDescriptorJson(true);

        return new Response(
            $desc,
            Response::HTTP_OK,
            [ 'Content-Type' => 'application/json' ]
        );
    }

    public function redirectAction()
    {
        $desc = $this
            ->get('elemecca_hipchat.installation_manager')
            ->getDescriptorJson(false);

        return $this->redirect(
            "https://www.hipchat.com/addons/install?url=" . rawurlencode(
                "data:application/json;base64," . base64_encode($desc)
            )
        );
    }



    public function installServerAction(Request $request)
    {
        $installer = $this->get('elemecca_hipchat.installation_manager');
        $installer->install($request->getContent());

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