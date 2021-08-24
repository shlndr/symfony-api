<?php

namespace Application\Sonata\UserBundle\Services;

use Application\Sonata\UserBundle\Services\FetchService;

class NotifyService
{
    protected $fetcher;

    public function setFetcher(FetchService $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function getFetcher()
    {
        return $this->fetcher;
    }

    public function sendOtp($otp, $msisdn)
    {
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=" . $msisdn . "&msg=Your%20Blinkeo%20mobile%20verification%20code%20is%20" . $otp . "&msg_type=TEXT&userid=2000143992&auth_scheme=plain&password=Secure@123&%20v%20=%201.1%20&%20Format=Tex";
        //return $this->getFetcher()->getJSON($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return 1;
    }

    public function sendNotification($playerId, $message, $data)
    {
        $content = array(
            "en" => $message
        );

        //$url = "https://onesignal.com/api/v1/notifications";
        //$header = array('Content-Type: application/json', 'Authorization: MjJiOTMwZTMtYzdhNS00MjMzLWIxNWYtYmZmYWZhOTM1NDFm');
        $fields = array(
            'app_id' => "63b9ec7f-28ee-4a2f-aff2-397e787ee6f0",
            'include_player_ids' => [$playerId],
            'data' => $data,
            'contents' => $content
        );
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: MjJiOTMwZTMtYzdhNS00MjMzLWIxNWYtYmZmYWZhOTM1NDFm'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_exec($ch);
        curl_close($ch);

        return 1;
    }

    public function triggerEmergency($number, $email)
    {
        return 1;
    }
}