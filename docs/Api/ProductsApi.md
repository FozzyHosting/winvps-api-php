# Fozzy\WinVPS\Api\ProductsApi

All URIs are relative to *https://winvps.fozzy.com/api/v2*

Method | HTTP request | Description
------------- | ------------- | -------------
[**productsGet**](ProductsApi.md#productsget) | **GET** /products | Returns list of all available products.

# **productsGet**
> \Fozzy\WinVPS\Api\Models\ProductsListResponse productsGet()

Returns list of all available products.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
// Configure API key authorization: ApiKeyAuth
$host = 'Endpoint from API docs';
$key = 'API key from WinVPS client area';
$config = Fozzy\WinVPS\Api\Configuration::getDefaultConfiguration()->setHost($host)->setApiKey($key);
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = Fozzy\WinVPS\Api\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Api-Key', 'Bearer');

$apiInstance = new Fozzy\WinVPS\Api\Resources\ProductsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->productsGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProductsApi->productsGet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\Fozzy\WinVPS\Api\Models\ProductsListResponse**](../Model/ProductsListResponse.md)

### Authorization

[ApiKeyAuth](../../README.md#ApiKeyAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

