<?php
/**
 * CRedisCache class file
 *
 * use and modify it as you wish
 * 
 * @author Gustavo Salomé <gustavonips@gmail.com>
 * @license http://www.opensource.org/licenses/gpl-3.0.html
 */

/**
 * CRedisCache uses Predis client as redis php client{@link https://github.com/nrk/predis predis}.
 */
class CRedisCache extends CCache
{
	/**
	 * @var Redis the Redis instance
	 */
	private $_cache=null;
	/**
	 * @var array list of redis server configurations
	 */
	private $_servers=array();

	/**
	 * Initializes this application component.
	 * This method is required by the {@link IApplicationComponent} interface.
	 * It creates the redis instance and adds redis servers.
	 * @throws CException if redis extension is not loaded
	 */
	public function init()
	{
		parent::init();
		$servers=$this->getServers();
		if(!count($servers))
		{
			$this->_servers[]=$servers=array('host'=>'127.0.0.1','port'=>6379);
		}
        $this->getRedis();
	}

	/**
	 * @return mixed the redis instance (or redisd if {@link useRedisd} is true) used by this component.
	 */
	public function getRedis()
	{
		if($this->_cache!==null)
			return $this->_cache;
		else{
            include Yii::getPathOfAlias("ext.redis.Predis").'.php';
            Yii::log('Opening Redis connection',CLogger::LEVEL_TRACE);
			return $this->_cache=new Predis_Client($this->getServers());
        }
	}
    
	/**
	 * @return array list of redis server configurations.
	 */
	public function getServers()
	{
		return $this->_servers;
	}

	/**
	 * Retrieves a value from cache with a specified key.
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key a unique key identifying the cached value
	 * @return string the value stored in cache, false if the value is not in the cache or expired.
	 */
	protected function getValue($key)
	{
		return $this->_cache->get($key);
	}

	/**
	 * Retrieves multiple values from cache with the specified keys.
	 * @param array $keys a list of keys identifying the cached values
	 * @return array a list of cached values indexed by the keys
	 * @since 1.0.8
	 */
	protected function getValues($keys)
	{
		return $this->_cache->mget($keys);
	}

	/**
	 * Stores a value identified by a key in cache.
	 * This is the implementation of the method declared in the parent class.
	 *
	 * @param string $key the key identifying the value to be cached
	 * @param string $value the value to be cached
	 * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	protected function setValue($key,$value,$expire)
	{
		if($expire>0)
			return $this->_cache->setex($key,$expire,$value);
		else
			return $this->_cache->set($key,$value);
	}

	/**
	 * Stores a value identified by a key into cache if the cache does not contain this key.
	 * This is the implementation of the method declared in the parent class.
	 *
	 * @param string $key the key identifying the value to be cached
	 * @param string $value the value to be cached
	 * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	protected function addValue($key,$value,$expire)
	{
		if($expire>0){
            if($this->_cache->setnx($key,$time,$value))
                return $this->_cache->expire($key,$time);
            return false;
		}else
			return $this->_cache->setnx($key,$value);
	}

	/**
	 * Deletes a value with the specified key from cache
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key the key of the value to be deleted
	 * @return boolean if no error happens during deletion
	 */
	protected function deleteValue($key)
	{
		return $this->_cache->del($key);
	}

	/**
	 * Deletes all values from cache.
	 * This is the implementation of the method declared in the parent class.
	 * @return boolean whether the flush operation was successful.
	 * @since 1.1.5
	 */
	protected function flushValues()
	{
		return $this->_cache->flush();
	}
    /**
     * call unusual method
     * */
    public function __call($method,$args){
        return call_user_func_array(array($this->_cache,$method),$args);
    }
    /**
	 * Returns whether there is a cache entry with a specified key.
	 * This method is required by the interface ArrayAccess.
	 * @param string $id a key identifying the cached value
	 * @return boolean
	 */
	public function offsetExists($id)
	{
		return $this->_cache->exists($id);
	}
}

