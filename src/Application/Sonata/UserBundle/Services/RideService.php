<?php

namespace Application\Sonata\UserBundle\Services;


class RideService
{
    public function getDistance($src_lat, $src_lon, $des_lat, $des_lon, $decimal = 3)
    {
        $rad = M_PI / 180;
        return round(acos(sin($des_lat * $rad) * sin($src_lat * $rad) + cos($des_lat * $rad) * cos($src_lat * $rad) * cos($des_lon * $rad - $src_lon * $rad)) * 6371, $decimal);
    }

    public function getCoordinates($placeId)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $placeId . '&key=AIzaSyAmGStHKGsNvu0FXg7ni5Qsl9siO6sY60c';

    }
}