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

namespace Fozzy\WinVPS\Api;

/**
 * ObjectSerializer Class Doc Comment
 *
 * @category Class
 * @package  Fozzy\WinVPS\Api
 * @author   Fozzy Inc.
 */
class ObjectSerializer
{
    /**
     * Serialize data
     *
     * @param mixed  $data   the data to serialize
     * @param string $type   the SwaggerType of the data
     * @param string $format the format of the Swagger type of the data
     *
     * @return string|object serialized form of $data
     */
    public static function sanitizeForSerialization($data, $type = null, $format = null)
    {

        if (is_scalar($data) || null === $data) {
            return $data;
        } elseif ($data instanceof \DateTime) {
            return ($format === 'date') ? $data->format('Y-m-d') : $data->format(\DateTime::ATOM);
} elseif (is_array($data)) {
foreach ($data as $property => $value) {
$data[$property] = self::sanitizeForSerialization($value);
}
return $data;
} elseif (is_object($data)) {
$values = [];
$formats = $data::swaggerFormats();
foreach ($data::swaggerTypes() as $property => $swaggerType) {
$getter = $data::getters()[$property];
$value = $data->$getter();
if ($value !== null
&& !in_array($swaggerType, ['DateTime', 'bool', 'boolean', 'byte', 'double', 'float', 'int', 'integer', 'mixed', 'number', 'object', 'string', 'void'], true)
&& method_exists($swaggerType, 'getAllowableEnumValues')
&& !in_array($value, $swaggerType::getAllowableEnumValues())) {
$imploded = implode("', '", $swaggerType::getAllowableEnumValues());
throw new \InvalidArgumentException("Invalid value for enum '$swaggerType', must be one of: '$imploded'");
}
if ($value !== null) {
$values[$data::attributeMap()[$property]] = self::sanitizeForSerialization($value, $swaggerType, $formats[$property]);
}
}
return (object)$values;
} else {
return (string)$data;
}
}

/**
* Sanitize filename by removing path.
* e.g. ../../sun.gif becomes sun.gif
*
* @param string $filename filename to be sanitized
*
* @return string the sanitized filename
*/
public static function sanitizeFilename($filename)
{
if (preg_match("/.*[\/\\\\](.*)$/", $filename, $match)) {
return $match[1];
} else {
return $filename;
}
}

/**
* Take value and turn it into a string suitable for inclusion in
* the path, by url-encoding.
*
* @param string $value a string which will be part of the path
*
* @return string the serialized object
*/
public static function toPathValue($value)
{
return rawurlencode(self::toString($value));
}

/**
* Take value and turn it into a string suitable for inclusion in
* the query, by imploding comma-separated if it's an object.
* If it's a string, pass through unchanged. It will be url-encoded
* later.
*
* @param string[]|string|\DateTime $object an object to be serialized to a string
*
* @return string the serialized object
*/
public static function toQueryValue($object)
{
if (is_array($object)) {
return implode(',', $object);
} else {
return self::toString($object);
}
}

/**
* Take value and turn it into a string suitable for inclusion in
* the header. If it's a string, pass through unchanged
* If it's a datetime object, format it in ISO8601
*
* @param string $value a string which will be part of the header
*
* @return string the header string
*/
public static function toHeaderValue($value)
{
return self::toString($value);
}

/**
* Take value and turn it into a string suitable for inclusion in
* the http body (form parameter). If it's a string, pass through unchanged
* If it's a datetime object, format it in ISO8601
*
* @param string|\SplFileObject $value the value of the form parameter
*
* @return string the form string
*/
public static function toFormValue($value)
{
if ($value instanceof \SplFileObject) {
return $value->getRealPath();
} else {
return self::toString($value);
}
}

/**
* Take value and turn it into a string suitable for inclusion in
* the parameter. If it's a string, pass through unchanged
* If it's a datetime object, format it in ISO8601
*
* @param string|\DateTime $value the value of the parameter
*
* @return string the header string
*/
public static function toString($value)
{
if ($value instanceof \DateTime) { // datetime in ISO8601 format
return $value->format(\DateTime::ATOM);
} else {
return $value;
}
}

/**
* Serialize an array to a string.
*
* @param array  $collection                 collection to serialize to a string
* @param string $collectionFormat           the format use for serialization (csv,
* ssv, tsv, pipes, multi)
* @param bool   $allowCollectionFormatMulti allow collection format to be a multidimensional array
*
* @return string
*/
public static function serializeCollection(array $collection, $collectionFormat, $allowCollectionFormatMulti = false)
{
if ($allowCollectionFormatMulti && ('multi' === $collectionFormat)) {
// http_build_query() almost does the job for us. We just
// need to fix the result of multidimensional arrays.
return preg_replace('/%5B[0-9]+%5D=/', '=', http_build_query($collection, '', '&'));
}
switch ($collectionFormat) {
case 'pipes':
return implode('|', $collection);

case 'tsv':
return implode("\t", $collection);

case 'ssv':
return implode(' ', $collection);

case 'csv':
// Deliberate fall through. CSV is default format.
default:
return implode(',', $collection);
}
}

/**
* Deserialize a JSON string into an object
*
* @param mixed    $data          object or primitive to be deserialized
* @param string   $class         class name is passed as a string
* @param string[] $httpHeaders   HTTP headers
* @param string   $discriminator discriminator if polymorphism is used
*
* @return object|array|null an single or an array of $class instances
*/
public static function deserialize($data, $class, $httpHeaders = null)
{
if (null === $data) {
return null;
} elseif (substr($class, 0, 4) === 'map[') { // for associative array e.g. map[string,int]
$inner = substr($class, 4, -1);
$deserialized = [];
if (strrpos($inner, ",") !== false) {
$subClass_array = explode(',', $inner, 2);
$subClass = $subClass_array[1];
foreach ($data as $key => $value) {
$deserialized[$key] = self::deserialize($value, $subClass, null);
}
}
return $deserialized;
} elseif (strcasecmp(substr($class, -2), '[]') === 0) {
$subClass = substr($class, 0, -2);
$values = [];
foreach ($data as $key => $value) {
$values[] = self::deserialize($value, $subClass, null);
}
return $values;
} elseif ($class === 'object') {
settype($data, 'array');
return $data;
} elseif ($class === '\DateTime') {
// Some API's return an invalid, empty string as a
// date-time property. DateTime::__construct() will return
// the current time for empty input which is probably not
// what is meant. The invalid empty string is probably to
// be interpreted as a missing field/value. Let's handle
// this graceful.
if (!empty($data)) {
return new \DateTime($data);
} else {
return null;
}
} elseif (in_array($class, ['DateTime', 'bool', 'boolean', 'byte', 'double', 'float', 'int', 'integer', 'mixed', 'number', 'object', 'string', 'void'], true)) {
settype($data, $class);
return $data;
} elseif ($class === '\SplFileObject') {
/** @var \Psr\Http\Message\StreamInterface $data */

// determine file name
if (array_key_exists('Content-Disposition', $httpHeaders) &&
preg_match('/inline; filename=[\'"]?([^\'"\s]+)[\'"]?$/i', $httpHeaders['Content-Disposition'], $match)) {
$filename = Configuration::getDefaultConfiguration()->getTempFolderPath() . DIRECTORY_SEPARATOR . self::sanitizeFilename($match[1]);
} else {
$filename = tempnam(Configuration::getDefaultConfiguration()->getTempFolderPath(), '');
}

$file = fopen($filename, 'w');
while ($chunk = $data->read(200)) {
fwrite($file, $chunk);
}
fclose($file);

return new \SplFileObject($filename, 'r');
} elseif (method_exists($class, 'getAllowableEnumValues')) {
if (!in_array($data, $class::getAllowableEnumValues())) {
$imploded = implode("', '", $class::getAllowableEnumValues());
throw new \InvalidArgumentException("Invalid value for enum '$class', must be one of: '$imploded'");
}
return $data;
} else {
// If a discriminator is defined and points to a valid subclass, use it.
$discriminator = $class::DISCRIMINATOR;
if (!empty($discriminator) && isset($data->{$discriminator}) && is_string($data->{$discriminator})) {
$subclass = '{{invokerPackage}}\Model\\' . $data->{$discriminator};
if (is_subclass_of($subclass, $class)) {
$class = $subclass;
}
}
$instance = new $class();

if (empty($instance::swaggerTypes()) && !empty($data)) {
$instance->setContainer($data);
}

foreach ($instance::swaggerTypes() as $property => $type) {
$propertySetter = $instance::setters()[$property];

if (!isset($propertySetter) || !isset($data->{$instance::attributeMap()[$property]})) {
continue;
}

$propertyValue = $data->{$instance::attributeMap()[$property]};
if (isset($propertyValue)) {
$instance->$propertySetter(self::deserialize($propertyValue, $type, null));
}
}
return $instance;
}
}
}
