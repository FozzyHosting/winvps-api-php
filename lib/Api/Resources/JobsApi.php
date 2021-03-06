<?php

/**
 * Fozzy Windows VPS resellers API
 *
 * Application Programming Interface (API) allows clients to manage the Windows VPS machines lifecycle.  ## Endpoint  `https://winvps.fozzy.com/api/v2/`  ## Authentication  To access the API, an existing client of Fozzy Inc. should be registered as Windows VPS Reseller by the company tech support through the ticket or using Sales Email. After that, the client will have an access to the winvps.fozzy.com and will be able to get an API Token (Signature) in `Settings -> API` section of main menu.  If you have already used the previous API version, then the token is known to you.  Note that the Token grants full access to your account and should be protected the same way you would protect your password. Also you can reset the Token on the receipt page.  To use the Token you should pass it to `Api-Key` header of each request like this:  ` curl -H 'API-KEY: TOKEN' https://winvps.fozzy.com/api/v2/products `  ## Content-Type  API v2 supports `application/json`, `application/x-www-form-urlencoded` and `multipart/form-data` content types.  In the first case HTTP request must be JSON-encoded with the body as a valid JSON string. The othres are default POST types with content in key=value format.  The response always has `application/json` type and contains JSON-encoded payload.  ## Response  A successful response will be returned as a JSON object with at least one of the following top-level members: - `data` - the document’s “primary data” - `error` - error message - `pagination` - pagination details  The members data and error cannot coexist in the same document.  ### Codes   - `200 OK` - Everything worked as expected.  - `201 Created` - The request was successful and a resource was created. This is typically a response from a POST request to create a resource which runs immediately.  - `202 Accepted` - The request has been accepted for processing. This is typically a response from a POST request that is handled async in our system, such as a request for some machine command.  - `204 No Content` - The request was successful but the response body is empty. This is typically a response from a DELETE request to delete a resource or cancel the command.  - `400 Bad Request` - A required parameter or the request is invalid.  - `403 Unauthorized` - The authentication credentials are invalid.  - `404 Not Found` - The requested resource doesn’t exist.  - `500 Server error` - something went wrong. Please contact our support team.  ### Examples  #### Error:  ```json {   \"error\": \"Error message\" } ```  #### Success - retrieve single record:  ```json {   \"data\": {     \"id\": 1,     \"name\": \"String\"   } } ```   #### Success - retrieve multiple records:  ```json {   \"data\": [     {       \"id\": 1,       \"name\": \"String\"     }, {       \"id\": 2,       \"name\": \"String\"     }   ],   \"pagination\": {     \"total\": 10,   } } ```  #### Success - response for some delayed action:  ```json {   \"data\": {     \"name\": \"String\",     \"jobs\": [       {         \"id\": 0,         \"parent_id\": 0,         \"machine_id\": 0,         \"type\": \"string\",         \"status\": \"string\",         \"start_time\": \"string\"       }     ]   } } ```  ## Pagination  Any API endpoint that returns a list of items requires pagination. By default we will return `50` records from any listing endpoint.  If an API endpoint returns a list of items, then it will include an additional object with pagination information.  The pagination information contains the following details:   - `total` - The total number of entries available in the entire collection  - `limit` - The number of entries returned per page (default: 50)  - `page` - The page currently returned (default: 1)  - `pages` - The total number of pages  To go through the pages you need to pass additional GET parameter `page` with the number of page wanted.  ## Entities meaning  ### Product  A product is a resources set with which a VPS will be created by default. This is a resources such ads CPU cores count, CPU power in percents of the maximum available limit, RAM minimum and maximum values, Disk Size etc.  ### Template  Template is an operating system version for VPS.  ### Brand  Brand is a set of custom software which installs on the machine automatically. Currently this set can be created only through the request to our administrators.  ### Location  Location is a list of regions in which the new VPS creation is available.  ### Job  Job is a command to perform specific actions on the machine such as creation, starting, changing, terminating, etc. Since most actions cannot be performed instantly, they are all queued and executed one after another. You will receive an additional property `jobs` in your response if any request generates new queue positions.  ### Machine  Machine is a virtual private server (VPS) which used to your own needs. Each Mahine has Operating System defined by **Template** installed on the server in a data center in a country specified by **Location** option. The machine has some specified by **Product** resources which can be used by your software installed automatically by the **Brand** option or manually from the RDP interface.  ## Changelog  ### Version 2.3.0 Methods `/machines` and `/machines/{name}` is now additionaly returns `notes` param.  ### Version 2.2.0  The machine creation command now supports an additional option `add_ipv6` to provide the IPv6 for the new machine.  ### Version 2.1.0  Added new command `run_updates_install` for starting Windows updates installation. Command can be used in the *_/machines/{name}/{command}* request.  The status of updates is displayed in the general information about the machine by the *_/machines/{name}* request.
 *
 * OpenAPI spec version: 2.1.0
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.13
 */

namespace Fozzy\WinVPS\Api\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\RequestOptions;
use Fozzy\WinVPS\Api\ApiException;
use Fozzy\WinVPS\Api\Configuration;
use Fozzy\WinVPS\Api\Resource;
use Fozzy\WinVPS\Api\HeaderSelector;
use Fozzy\WinVPS\Api\ObjectSerializer;

/**
 * JobsApi Resource
 *
 * @category Class
 * @package  Fozzy\WinVPS\Api
 * @author   Fozzy Inc.
 */
class JobsApi extends Resource
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    private $pagination = null;
    private $paginationCurrentPage = 1;
    private $paginationLimit = 50;

    public function paginationSetLimit($limit)
    {
        $this->paginationLimit = $limit;
}

public function paginationSetPage($num)
{
$this->paginationCurrentPage = $num;
}

public function paginationNext()
{
$this->paginationCurrentPage = $this->paginationCurrentPage + 1;

if (!$this->pagination || $this->paginationCurrentPage > $this->pagination->pages) {
$this->paginationCurrentPage = 1;
return false;
}
}

public function paginationPrev()
{
$this->paginationCurrentPage = $this->paginationCurrentPage - 1;

if ($this->paginationCurrentPage < 1) {
$this->paginationCurrentPage = 1;
return false;
}
}

public function paginationGetTotal()
{
return $this->pagination ? $this->pagination->total : null;
}
public function paginationGetPage()
{
return $this->pagination ? $this->pagination->page : null;
}
public function paginationGetPages()
{
return $this->pagination ? $this->pagination->pages : null;
}
public function paginationHasMore()
{
return $this->pagination ? $this->pagination->pages > $this->pagination->page : null;
}

/**
* @param ClientInterface $client
* @param Configuration   $config
* @param HeaderSelector  $selector
*/
public function __construct(
ClientInterface $client = null,
Configuration $config = null,
HeaderSelector $selector = null
) {
parent::__construct();
$this->client = $client ?: new Client();
$this->config = $config ?: new Configuration();
$this->headerSelector = $selector ?: new HeaderSelector();
}

/**
* @return Configuration
*/
public function getConfig()
{
return $this->config;
}

        /**
        * Operation jobsGet
            *
            * List of all planned and completed commands.
        *
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return \Fozzy\WinVPS\Api\Models\JobsListResponse
        */
        public function jobsGet()
        {
        list($response) = $this->jobsGetWithHttpInfo();
            return $response;
        }

        /**
        * Operation jobsGetWithHttpInfo
            *
            * List of all planned and completed commands.
        *
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return array of \Fozzy\WinVPS\Api\Models\JobsListResponse, HTTP status code, HTTP response headers (array of strings)
        */
        public function jobsGetWithHttpInfo()
        {
        $returnType = '\Fozzy\WinVPS\Api\Models\JobsListResponse';
        $request = $this->jobsGetRequest();

        try {
        $options = $this->createHttpClientOption();
        try {
        $response = $this->client->send($request, $options);
        } catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $request,
        $e->getResponse() ? $e->getResponse()->getHeaders() : null,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
        );
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
        $url = '';

        if (method_exists($request, 'getUri')) {
        $url = $request->getUri();
        }

        if (method_exists($request, 'getUrl')) {
        $url = $request->getUrl();
        }

        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $url
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
            $content = $responseBody; //stream goes to serializer
            } else {
            $content = $responseBody->getContents();
            if (!in_array($returnType, ['string','integer','bool'])) {
            $content = json_decode($content);
            }
            }

            if (!empty($content) && is_object($content) && property_exists($content, 'pagination')) {
            $this->pagination = $content->pagination;
            }

            $result = ObjectSerializer::deserialize($content, $returnType, []);

            if (is_object($result) && method_exists($result, 'getData')) {
            $result = $result->getData();
            }

            return [
            $result,
            $response->getStatusCode(),
            $response->getHeaders()
            ];

        } catch (ApiException $e) {

        $body = $e->getResponseBody();
        $data = ObjectSerializer::deserialize(
        $body,
        '\Fozzy\WinVPS\Api\Models\ErrorResponse',
        $e->getResponseHeaders()
        );
        try {
        //$content = $body->getContents();
        $content = $body;
        if ($content) {
        $content = json_decode($content, true);
        }
        if (!empty($content) && is_array($content) && !empty($content['error'])) {
        $data->setError($content['error']);
        }
        } catch (\Exception $e) {
        }
        $e->setResponseObject($data);

        throw $e;
        }
        }

        /**
        * Operation jobsGetAsync
        *
        * List of all planned and completed commands.
        *
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsGetAsync()
        {
        return $this->jobsGetAsyncWithHttpInfo()
        ->then(
        function ($response) {
        return $response[0];
        }
        );
        }

        /**
        * Operation jobsGetAsyncWithHttpInfo
        *
        * List of all planned and completed commands.
        *
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsGetAsyncWithHttpInfo()
        {
        $returnType = '\Fozzy\WinVPS\Api\Models\JobsListResponse';
        $request = $this->jobsGetRequest();

        return $this->client
        ->sendAsync($request, $this->createHttpClientOption())
        ->then(
        function ($response) use ($returnType) {
            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
            $content = $responseBody; //stream goes to serializer
            } else {
            $content = $responseBody->getContents();
            if ($returnType !== 'string') {
            $content = json_decode($content);
            }
            }

            $result = ObjectSerializer::deserialize($content, $returnType, []);

            if (is_object($result) && property_exists($result, 'getData')) {
            $result = $result->getData();
            }

            return [
            $result,
            $response->getStatusCode(),
            $response->getHeaders()
            ];
        },
        function ($exception) {
        $response = $exception->getResponse();
        $statusCode = $response->getStatusCode();
        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $exception->getRequest()->getUri()
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }
        );
        }

        /**
        * Create request for operation 'jobsGet'
        *
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Psr7\Request
        */
        protected function jobsGetRequest()
        {

        $resourcePath = '/jobs';
        $formParams = [];
        $queryParams = [
        'page' => $this->paginationCurrentPage,
        'limit' => $this->paginationLimit,
        ];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // body params
        $_tempBody = null;

        if ($multipart) {
        $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
        );
        } else {
        $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        []
        );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
        // $_tempBody is the method argument, if present
        $httpBody = $_tempBody;
        // \stdClass has no __toString(), so we should encode it manually
        if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
        }
        } elseif (count($formParams) > 0) {
        if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
        $multipartContents[] = [
        'name' => $formParamName,
        'contents' => $formParamValue
        ];
        }
        // for HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

        } elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

        } else {
        // for HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
        }
        }

                // this endpoint requires API key authentication
                $apiKey = $this->config->getApiKeyWithPrefix('Api-Key');
                if ($apiKey !== null) {
                $headers['Api-Key'] = $apiKey;
                }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
        $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return $this->createRequest(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
        );
        }

        /**
        * Operation jobsIdDelete
            *
            * Cancel specified Job.
        *
            * @param  int $id id (required)
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return void
        */
        public function jobsIdDelete($id)
        {
        $this->jobsIdDeleteWithHttpInfo($id);
        }

        /**
        * Operation jobsIdDeleteWithHttpInfo
            *
            * Cancel specified Job.
        *
            * @param  int $id (required)
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return array of null, HTTP status code, HTTP response headers (array of strings)
        */
        public function jobsIdDeleteWithHttpInfo($id)
        {
        $returnType = '';
        $request = $this->jobsIdDeleteRequest($id);

        try {
        $options = $this->createHttpClientOption();
        try {
        $response = $this->client->send($request, $options);
        } catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $request,
        $e->getResponse() ? $e->getResponse()->getHeaders() : null,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
        );
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
        $url = '';

        if (method_exists($request, 'getUri')) {
        $url = $request->getUri();
        }

        if (method_exists($request, 'getUrl')) {
        $url = $request->getUrl();
        }

        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $url
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }

            return [null, $statusCode, $response->getHeaders()];

        } catch (ApiException $e) {

        $body = $e->getResponseBody();
        $data = ObjectSerializer::deserialize(
        $body,
        '\Fozzy\WinVPS\Api\Models\ErrorResponse',
        $e->getResponseHeaders()
        );
        try {
        //$content = $body->getContents();
        $content = $body;
        if ($content) {
        $content = json_decode($content, true);
        }
        if (!empty($content) && is_array($content) && !empty($content['error'])) {
        $data->setError($content['error']);
        }
        } catch (\Exception $e) {
        }
        $e->setResponseObject($data);

        throw $e;
        }
        }

        /**
        * Operation jobsIdDeleteAsync
        *
        * Cancel specified Job.
        *
            * @param  int $id (required)
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsIdDeleteAsync($id)
        {
        return $this->jobsIdDeleteAsyncWithHttpInfo($id)
        ->then(
        function ($response) {
        return $response[0];
        }
        );
        }

        /**
        * Operation jobsIdDeleteAsyncWithHttpInfo
        *
        * Cancel specified Job.
        *
            * @param  int $id (required)
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsIdDeleteAsyncWithHttpInfo($id)
        {
        $returnType = '';
        $request = $this->jobsIdDeleteRequest($id);

        return $this->client
        ->sendAsync($request, $this->createHttpClientOption())
        ->then(
        function ($response) use ($returnType) {
            return [null, $response->getStatusCode(), $response->getHeaders()];
        },
        function ($exception) {
        $response = $exception->getResponse();
        $statusCode = $response->getStatusCode();
        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $exception->getRequest()->getUri()
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }
        );
        }

        /**
        * Create request for operation 'jobsIdDelete'
        *
            * @param  int $id (required)
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Psr7\Request
        */
        protected function jobsIdDeleteRequest($id)
        {
                // verify the required parameter 'id' is set
                if ($id === null || (is_array($id) && count($id) === 0)) {
                throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling jobsIdDelete'
                );
                }

        $resourcePath = '/jobs/{id}';
        $formParams = [];
        $queryParams = [
        'page' => $this->paginationCurrentPage,
        'limit' => $this->paginationLimit,
        ];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


            // path params
            if ($id !== null) {
            $resourcePath = str_replace(
            '{' . 'id' . '}',
            ObjectSerializer::toPathValue($id),
            $resourcePath
            );
            }

        // body params
        $_tempBody = null;

        if ($multipart) {
        $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
        );
        } else {
        $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        []
        );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
        // $_tempBody is the method argument, if present
        $httpBody = $_tempBody;
        // \stdClass has no __toString(), so we should encode it manually
        if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
        }
        } elseif (count($formParams) > 0) {
        if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
        $multipartContents[] = [
        'name' => $formParamName,
        'contents' => $formParamValue
        ];
        }
        // for HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

        } elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

        } else {
        // for HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
        }
        }

                // this endpoint requires API key authentication
                $apiKey = $this->config->getApiKeyWithPrefix('Api-Key');
                if ($apiKey !== null) {
                $headers['Api-Key'] = $apiKey;
                }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
        $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return $this->createRequest(
        'DELETE',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
        );
        }

        /**
        * Operation jobsIdGet
            *
            * View single Job details.
        *
            * @param  int $id id (required)
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return \Fozzy\WinVPS\Api\Models\JobDetailsResponse
        */
        public function jobsIdGet($id)
        {
        list($response) = $this->jobsIdGetWithHttpInfo($id);
            return $response;
        }

        /**
        * Operation jobsIdGetWithHttpInfo
            *
            * View single Job details.
        *
            * @param  int $id (required)
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return array of \Fozzy\WinVPS\Api\Models\JobDetailsResponse, HTTP status code, HTTP response headers (array of strings)
        */
        public function jobsIdGetWithHttpInfo($id)
        {
        $returnType = '\Fozzy\WinVPS\Api\Models\JobDetailsResponse';
        $request = $this->jobsIdGetRequest($id);

        try {
        $options = $this->createHttpClientOption();
        try {
        $response = $this->client->send($request, $options);
        } catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $request,
        $e->getResponse() ? $e->getResponse()->getHeaders() : null,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
        );
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
        $url = '';

        if (method_exists($request, 'getUri')) {
        $url = $request->getUri();
        }

        if (method_exists($request, 'getUrl')) {
        $url = $request->getUrl();
        }

        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $url
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
            $content = $responseBody; //stream goes to serializer
            } else {
            $content = $responseBody->getContents();
            if (!in_array($returnType, ['string','integer','bool'])) {
            $content = json_decode($content);
            }
            }

            if (!empty($content) && is_object($content) && property_exists($content, 'pagination')) {
            $this->pagination = $content->pagination;
            }

            $result = ObjectSerializer::deserialize($content, $returnType, []);

            if (is_object($result) && method_exists($result, 'getData')) {
            $result = $result->getData();
            }

            return [
            $result,
            $response->getStatusCode(),
            $response->getHeaders()
            ];

        } catch (ApiException $e) {

        $body = $e->getResponseBody();
        $data = ObjectSerializer::deserialize(
        $body,
        '\Fozzy\WinVPS\Api\Models\ErrorResponse',
        $e->getResponseHeaders()
        );
        try {
        //$content = $body->getContents();
        $content = $body;
        if ($content) {
        $content = json_decode($content, true);
        }
        if (!empty($content) && is_array($content) && !empty($content['error'])) {
        $data->setError($content['error']);
        }
        } catch (\Exception $e) {
        }
        $e->setResponseObject($data);

        throw $e;
        }
        }

        /**
        * Operation jobsIdGetAsync
        *
        * View single Job details.
        *
            * @param  int $id (required)
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsIdGetAsync($id)
        {
        return $this->jobsIdGetAsyncWithHttpInfo($id)
        ->then(
        function ($response) {
        return $response[0];
        }
        );
        }

        /**
        * Operation jobsIdGetAsyncWithHttpInfo
        *
        * View single Job details.
        *
            * @param  int $id (required)
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsIdGetAsyncWithHttpInfo($id)
        {
        $returnType = '\Fozzy\WinVPS\Api\Models\JobDetailsResponse';
        $request = $this->jobsIdGetRequest($id);

        return $this->client
        ->sendAsync($request, $this->createHttpClientOption())
        ->then(
        function ($response) use ($returnType) {
            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
            $content = $responseBody; //stream goes to serializer
            } else {
            $content = $responseBody->getContents();
            if ($returnType !== 'string') {
            $content = json_decode($content);
            }
            }

            $result = ObjectSerializer::deserialize($content, $returnType, []);

            if (is_object($result) && property_exists($result, 'getData')) {
            $result = $result->getData();
            }

            return [
            $result,
            $response->getStatusCode(),
            $response->getHeaders()
            ];
        },
        function ($exception) {
        $response = $exception->getResponse();
        $statusCode = $response->getStatusCode();
        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $exception->getRequest()->getUri()
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }
        );
        }

        /**
        * Create request for operation 'jobsIdGet'
        *
            * @param  int $id (required)
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Psr7\Request
        */
        protected function jobsIdGetRequest($id)
        {
                // verify the required parameter 'id' is set
                if ($id === null || (is_array($id) && count($id) === 0)) {
                throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling jobsIdGet'
                );
                }

        $resourcePath = '/jobs/{id}';
        $formParams = [];
        $queryParams = [
        'page' => $this->paginationCurrentPage,
        'limit' => $this->paginationLimit,
        ];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


            // path params
            if ($id !== null) {
            $resourcePath = str_replace(
            '{' . 'id' . '}',
            ObjectSerializer::toPathValue($id),
            $resourcePath
            );
            }

        // body params
        $_tempBody = null;

        if ($multipart) {
        $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
        );
        } else {
        $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        []
        );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
        // $_tempBody is the method argument, if present
        $httpBody = $_tempBody;
        // \stdClass has no __toString(), so we should encode it manually
        if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
        }
        } elseif (count($formParams) > 0) {
        if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
        $multipartContents[] = [
        'name' => $formParamName,
        'contents' => $formParamValue
        ];
        }
        // for HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

        } elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

        } else {
        // for HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
        }
        }

                // this endpoint requires API key authentication
                $apiKey = $this->config->getApiKeyWithPrefix('Api-Key');
                if ($apiKey !== null) {
                $headers['Api-Key'] = $apiKey;
                }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
        $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return $this->createRequest(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
        );
        }

        /**
        * Operation jobsPendingGet
            *
            * List of all planned commands.
        *
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return \Fozzy\WinVPS\Api\Models\JobsListResponse
        */
        public function jobsPendingGet()
        {
        list($response) = $this->jobsPendingGetWithHttpInfo();
            return $response;
        }

        /**
        * Operation jobsPendingGetWithHttpInfo
            *
            * List of all planned commands.
        *
        *
        * @throws \Fozzy\WinVPS\Api\ApiException on non-2xx response
        * @throws \InvalidArgumentException
        * @return array of \Fozzy\WinVPS\Api\Models\JobsListResponse, HTTP status code, HTTP response headers (array of strings)
        */
        public function jobsPendingGetWithHttpInfo()
        {
        $returnType = '\Fozzy\WinVPS\Api\Models\JobsListResponse';
        $request = $this->jobsPendingGetRequest();

        try {
        $options = $this->createHttpClientOption();
        try {
        $response = $this->client->send($request, $options);
        } catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $request,
        $e->getResponse() ? $e->getResponse()->getHeaders() : null,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
        );
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
        $url = '';

        if (method_exists($request, 'getUri')) {
        $url = $request->getUri();
        }

        if (method_exists($request, 'getUrl')) {
        $url = $request->getUrl();
        }

        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $url
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
            $content = $responseBody; //stream goes to serializer
            } else {
            $content = $responseBody->getContents();
            if (!in_array($returnType, ['string','integer','bool'])) {
            $content = json_decode($content);
            }
            }

            if (!empty($content) && is_object($content) && property_exists($content, 'pagination')) {
            $this->pagination = $content->pagination;
            }

            $result = ObjectSerializer::deserialize($content, $returnType, []);

            if (is_object($result) && method_exists($result, 'getData')) {
            $result = $result->getData();
            }

            return [
            $result,
            $response->getStatusCode(),
            $response->getHeaders()
            ];

        } catch (ApiException $e) {

        $body = $e->getResponseBody();
        $data = ObjectSerializer::deserialize(
        $body,
        '\Fozzy\WinVPS\Api\Models\ErrorResponse',
        $e->getResponseHeaders()
        );
        try {
        //$content = $body->getContents();
        $content = $body;
        if ($content) {
        $content = json_decode($content, true);
        }
        if (!empty($content) && is_array($content) && !empty($content['error'])) {
        $data->setError($content['error']);
        }
        } catch (\Exception $e) {
        }
        $e->setResponseObject($data);

        throw $e;
        }
        }

        /**
        * Operation jobsPendingGetAsync
        *
        * List of all planned commands.
        *
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsPendingGetAsync()
        {
        return $this->jobsPendingGetAsyncWithHttpInfo()
        ->then(
        function ($response) {
        return $response[0];
        }
        );
        }

        /**
        * Operation jobsPendingGetAsyncWithHttpInfo
        *
        * List of all planned commands.
        *
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Promise\PromiseInterface
        */
        public function jobsPendingGetAsyncWithHttpInfo()
        {
        $returnType = '\Fozzy\WinVPS\Api\Models\JobsListResponse';
        $request = $this->jobsPendingGetRequest();

        return $this->client
        ->sendAsync($request, $this->createHttpClientOption())
        ->then(
        function ($response) use ($returnType) {
            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
            $content = $responseBody; //stream goes to serializer
            } else {
            $content = $responseBody->getContents();
            if ($returnType !== 'string') {
            $content = json_decode($content);
            }
            }

            $result = ObjectSerializer::deserialize($content, $returnType, []);

            if (is_object($result) && property_exists($result, 'getData')) {
            $result = $result->getData();
            }

            return [
            $result,
            $response->getStatusCode(),
            $response->getHeaders()
            ];
        },
        function ($exception) {
        $response = $exception->getResponse();
        $statusCode = $response->getStatusCode();
        throw new ApiException(
        sprintf(
        '[%d] Error connecting to the API (%s)',
        $statusCode,
        $exception->getRequest()->getUri()
        ),
        $statusCode,
        $request,
        $response->getHeaders(),
        $response->getBody()
        );
        }
        );
        }

        /**
        * Create request for operation 'jobsPendingGet'
        *
        *
        * @throws \InvalidArgumentException
        * @return \GuzzleHttp\Psr7\Request
        */
        protected function jobsPendingGetRequest()
        {

        $resourcePath = '/jobs/pending';
        $formParams = [];
        $queryParams = [
        'page' => $this->paginationCurrentPage,
        'limit' => $this->paginationLimit,
        ];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // body params
        $_tempBody = null;

        if ($multipart) {
        $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
        );
        } else {
        $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        []
        );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
        // $_tempBody is the method argument, if present
        $httpBody = $_tempBody;
        // \stdClass has no __toString(), so we should encode it manually
        if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
        }
        } elseif (count($formParams) > 0) {
        if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
        $multipartContents[] = [
        'name' => $formParamName,
        'contents' => $formParamValue
        ];
        }
        // for HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

        } elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

        } else {
        // for HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
        }
        }

                // this endpoint requires API key authentication
                $apiKey = $this->config->getApiKeyWithPrefix('Api-Key');
                if ($apiKey !== null) {
                $headers['Api-Key'] = $apiKey;
                }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
        $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return $this->createRequest(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
        );
        }

/**
* Create http client option
*
* @throws \RuntimeException on file opening failure
* @return array of http client options
*/
protected function createHttpClientOption()
{
$options = [];
if ($this->config->getDebug()) {
$options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
if (!$options[RequestOptions::DEBUG]) {
throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
}
}

return $options;
}
}
