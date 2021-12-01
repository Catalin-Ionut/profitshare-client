<?php

namespace ProfitshareClient;

use JsonMapper;
use JsonMapper_Exception;
use ProfitshareClient\Model\Advertiser;
use ProfitshareClient\Model\Campaigns;
use ProfitshareClient\Model\Commissions;
use ProfitshareClient\Model\Products;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Profitshare
{
    private const API_URL = 'http://api.profitshare.ro/';

    /** @var string */
    private $apiKey;
    /** @var string */
    private $apiUser;
    /** @var JsonMapper */
    private $mapper;
    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(string $apiUser, string $apiKey)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;

        $this->initializeHttpClient();
        $this->initializeJsonMapper();
    }

    private function initializeHttpClient(): void
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClient::createForBaseUri(self::API_URL);
        }
    }

    private function initializeJsonMapper(): void
    {
        $this->mapper = new JsonMapper();
        $this->mapper->bStrictNullTypes = false;
        $this->mapper->bExceptionOnUndefinedProperty = false;
    }

    /**
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface|JsonMapper_Exception
     */
    public function getAdvertisers(): array
    {
        $response = json_decode(
            $this->request('affiliate-advertisers')->getContent()
        );

        return $this->mapper->mapArray($response->result, [], Advertiser::class);
    }

    /**
     * @param int $advertiserID
     * @param int $page
     *
     * @return Products
     *
     * @throws ClientExceptionInterface|RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface|JsonMapper_Exception
     */
    public function getProducts(int $advertiserID, int $page = 1): Products
    {
        $response = json_decode(
            $this->request('affiliate-products', [
                'filters' => ['advertiser' => $advertiserID],
                'page' => $page,
            ])->getContent()
        );

        return $this->mapper->mapArray($response, [], Products::class)['result'];
    }

    /**
     * @param int      $advertiserID
     * @param callable $callback (array Products[])
     *
     * @throws ClientExceptionInterface|RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface|JsonMapper_Exception
     */
    public function loopAllProducts(int $advertiserID, callable $callback): void
    {
        $firstPage = $this->getProducts($advertiserID);

        /* apply callback to the first page of products so we don't waste a call */
        $callback($firstPage->products);

        $requests = [];
        for ($page = 2; $page <= $firstPage->total_pages; ++$page) {
            $requests[] = $this->request('affiliate-products', [
                'filters' => ['advertiser' => $advertiserID],
                'page' => $page,
            ]);
        }

        /** @var ResponseInterface $response */
        foreach ($this->httpClient->stream($requests) as $response => $chunk) {
            if ($chunk->isLast()) {
                $callback($this->mapper->mapArray(
                    json_decode($response->getContent()), [], Products::class
                )['result']->products);
            }
        }
    }

    /**
     * @param array $filters
     * @param int   $page
     *
     * @return Commissions
     *
     * @throws ClientExceptionInterface|RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface|JsonMapper_Exception
     */
    public function getCommissions(array $filters, int $page = 1): Commissions
    {
        $response = json_decode(
            $this->request('affiliate-commissions', ['filters' => $filters, 'page' => $page,])->getContent()
        );

        return $this->mapper->mapArray($response, [], Commissions::class)['result'];
    }

    /**
     * @param int $page
     *
     * @return Campaigns
     *
     * @throws ClientExceptionInterface|RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface|JsonMapper_Exception
     */
    public function getCampaign(int $page = 1): Campaigns
    {
        $response = json_decode(
            $this->request('affiliate-campaigns', ['page' => $page])->getContent()
        );

        return $this->mapper->mapArray($response, [], Campaigns::class)['result'];
    }


    /**
     * @param callable $callback (array Campaigns[])
     *
     * @throws ClientExceptionInterface|RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface|JsonMapper_Exception
     */
    public function loopAllCampaigns(callable $callback): void
    {
        $firstPage = $this->getCampaign();

        /* apply callback to the first page of campaigns so we don't waste a call */
        $callback($firstPage->campaigns);

        $requests = [];
        for ($page = 2; $page <= $firstPage->paginator->totalPages; ++$page) {
            $requests[] = $this->request('affiliate-campaigns', ['page' => $page]);
        }

        /** @var ResponseInterface $response */
        foreach ($this->httpClient->stream($requests) as $response => $chunk) {
            if (!$chunk->isLast()) {
                $callback($this->mapper->mapArray(json_decode($response->getContent()), [], Campaigns::class));
            }
        }
    }

    private function request(string $api, array $params = [], string $type = 'GET'): ResponseInterface
    {
        $query_string = '';
        if (!empty($params) && is_array($params) && $type == "GET") {
            $query_string = http_build_query($params);
            $query_string = '/?'.urldecode($query_string);
        }

        $date = gmdate('D, d M Y H:i:s T', time());
        $signature = $type.$api.$query_string.'/'.$this->apiUser.$date;
        $authentication = hash_hmac('sha1', $signature, $this->apiKey);

        return $this->httpClient->request($type, $api.$query_string, [
            'headers' => [
                'Date' => $date,
                'X-PS-Client' => $this->apiUser,
                'X-PS-Accept' => 'json',
                'X-PS-Auth' => $authentication,
            ],
        ]);
    }
}
