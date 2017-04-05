<?php

namespace Elemecca\HipchatBundle\Model;

class Installation {
    private $id;
    private $secret;
    private $group_id;
    private $room_id;
    private $token;
    private $token_expires;
    private $capability_url;
    private $token_url;
    private $api_url;

    public function __construct($id, $secret, $group_id, $room_id)
    {
        $this->id = $id;
        $this->secret = $secret;
        $this->group_id = $group_id;
        $this->room_id = $room_id;
    }
}