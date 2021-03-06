<?php

namespace Fozzy\WinVPS\Api\Models;

use \ArrayAccess;
use \Fozzy\WinVPS\Api\ObjectSerializer;

/**
 * MachineUserDefinition Model
 *
 * @category Class
 * @package  Fozzy\WinVPS\Api
 * @author   Fozzy Inc.
 */
class MachineUserDefinition implements ModelInterface, ArrayAccess
{
const DISCRIMINATOR = null;

/**
* The original model name.
*
* @var string
*/
protected static $swaggerModelName = 'MachineUserDefinition';

/**
* Array of property to type mappings. Used for (de)serialization
*
* @var string[]
*/
protected static $swaggerTypes = [
'username' => 'string',
'role' => 'string',
'password' => 'string'];

/**
* Array of property to format mappings. Used for (de)serialization
*
* @var string[]
*/
protected static $swaggerFormats = [
'username' => null,
'role' => null,
'password' => null];

/**
* Array of property to type mappings. Used for (de)serialization
*
* @return array
*/
public static function swaggerTypes()
{
return self::$swaggerTypes;
}

/**
* Array of property to format mappings. Used for (de)serialization
*
* @return array
*/
public static function swaggerFormats()
{
return self::$swaggerFormats;
}

/**
* Array of attributes where the key is the local name,
* and the value is the original name
*
* @var string[]
*/
protected static $attributeMap = [
'username' => 'username',
'role' => 'role',
'password' => 'password'];

/**
* Array of attributes to setter functions (for deserialization of responses)
*
* @var string[]
*/
protected static $setters = [
'username' => 'setUsername',
'role' => 'setRole',
'password' => 'setPassword'];

/**
* Array of attributes to getter functions (for serialization of requests)
*
* @var string[]
*/
protected static $getters = [
'username' => 'getUsername',
'role' => 'getRole',
'password' => 'getPassword'];

/**
* Array of attributes where the key is the local name,
* and the value is the original name
*
* @return array
*/
public static function attributeMap()
{
return self::$attributeMap;
}

/**
* Array of attributes to setter functions (for deserialization of responses)
*
* @return array
*/
public static function setters()
{
return self::$setters;
}

/**
* Array of attributes to getter functions (for serialization of requests)
*
* @return array
*/
public static function getters()
{
return self::$getters;
}

/**
* The original name of the model.
*
* @return string
*/
public function getModelName()
{
return self::$swaggerModelName;
}



    /**
    * Associative array for storing property values
    *
    * @var mixed[]
    */
    protected $container = [];

/**
* Constructor
*
* @param mixed[] $data Associated array of property values
*                      initializing the model
*/
public function __construct(array $data = null)
{
    $this->container['username'] = isset($data['username']) ? $data['username'] : null;
    $this->container['role'] = isset($data['role']) ? $data['role'] : null;
    $this->container['password'] = isset($data['password']) ? $data['password'] : null;
}

public function setContainer($data)
{
$this->container = $data;
}

/**
* Show all the invalid properties with reasons.
*
* @return array invalid properties with reasons
*/
public function listInvalidProperties()
{
    $invalidProperties = [];

return $invalidProperties;
}

/**
* Validate all the properties in the model
* return true if all passed
*
* @return bool True if all properties are valid
*/
public function valid()
{
return count($this->listInvalidProperties()) === 0;
}


    /**
    * Gets username
    *
    * @return string
    */
    public function getUsername()
    {
    return $this->container['username'];
    }

    /**
    * Sets username
    *
    * @param string $username username
    *
    * @return $this
    */
    public function setUsername($username)
    {
    $this->container['username'] = $username;

    return $this;
    }

    /**
    * Gets role
    *
    * @return string
    */
    public function getRole()
    {
    return $this->container['role'];
    }

    /**
    * Sets role
    *
    * @param string $role role
    *
    * @return $this
    */
    public function setRole($role)
    {
    $this->container['role'] = $role;

    return $this;
    }

    /**
    * Gets password
    *
    * @return string
    */
    public function getPassword()
    {
    return $this->container['password'];
    }

    /**
    * Sets password
    *
    * @param string $password password
    *
    * @return $this
    */
    public function setPassword($password)
    {
    $this->container['password'] = $password;

    return $this;
    }
/**
* Returns true if offset exists. False otherwise.
*
* @param integer $offset Offset
*
* @return boolean
*/
public function offsetExists($offset)
{
return isset($this->container[$offset]);
}

/**
* Gets offset.
*
* @param integer $offset Offset
*
* @return mixed
*/
public function offsetGet($offset)
{
return isset($this->container[$offset]) ? $this->container[$offset] : null;
}

/**
* Sets value based on offset.
*
* @param integer $offset Offset
* @param mixed   $value  Value to be set
*
* @return void
*/
public function offsetSet($offset, $value)
{
if (is_null($offset)) {
$this->container[] = $value;
} else {
$this->container[$offset] = $value;
}
}

/**
* Unsets offset.
*
* @param integer $offset Offset
*
* @return void
*/
public function offsetUnset($offset)
{
unset($this->container[$offset]);
}

/**
* Gets the string presentation of the object
*
* @return string
*/
public function __toString()
{
if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
return json_encode(
ObjectSerializer::sanitizeForSerialization($this),
JSON_PRETTY_PRINT
);
}

return json_encode(ObjectSerializer::sanitizeForSerialization($this));
}
}
