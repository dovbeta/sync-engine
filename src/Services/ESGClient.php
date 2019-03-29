<?php
namespace ESG\SyncEngine\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class ESGClient
{
    private $token;
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->token = $this->getToken();
    }

    public function getToken()
    {
        $res = $this->httpClient->post(config('sync.ep.login'), [
            RequestOptions::JSON => [
                'tenant_id' => config('sync.tenant_id'),
                'username' => config('sync.user.username'),
                'password' => config('sync.user.password'),
                'claim' => config('sync.user.claim'),
            ],
        ]);
        $data = json_decode($res->getBody());
        if ($res->getStatusCode() != 200 || !isset($data->accessToken)) {
            Log::warning("Can't login on ESG host");
            Log::warning($res->getBody());
            return null;
        } else {
            return $data->accessToken;
        }
    }

    public function isAuthorized()
    {
        return !!$this->token;
    }

    /**
     * @param $host
     * @param $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function authRequest($method, $host, $data)
    {
        return $this->httpClient->request($method, $host,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'     => 'application/json',
                    'tenantId'      => config('sync.tenant_id'),
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                RequestOptions::JSON => $data
            ]);
    }

    public function authPut($host, $data)
    {
        return $this->authRequest('PUT', $host, $data);
    }

    public function authPost($host, $data)
    {
        return $this->authRequest('POST', $host, $data);
    }

    public function authDelete($host, $data)
    {
        return $this->authRequest('DELETE', $host, $data);
    }
}