<?php

class ShippingCosts
{
    private $apiKey;    # export ZIPCODEBASE_API_KEY='48fe1520-124c-11ef-9894-d3522cbb4570'
    private static float $COST = 0.5;

    public function __construct($apiKey) 
    {
        $this->apiKey = getenv('ZIPCODEBASE_API_KEY');
    }

    public function getDistance(string $zipCode1, string $zipCode2) : float
    {
        $url = "https://app.zipcodebase.com/api/v1/distance";
    
        $data = [
            "code" => $zipCode1,
            "compare" => $zipCode2,
            "country" => "pt",
            "apikey" => "48fe1520-124c-11ef-9894-d3522cbb4570"
        ];
        
        $url .= '?' . http_build_query($data);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Content-Type: application/json\r\n"
            ]
        ]);
        
        $response = file_get_contents($url, false, $context);
        
        $json = json_decode($response);
        
        $distance = $json->results->{$zipCode2};
        
        return $distance * ShippingCosts::$COST;
    }

}
