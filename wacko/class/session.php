<?php

// TODO:
// - do not store session id in filename or db index - store hash instead!
// - log of IP changes and other possible security alerts
// - allocate internal unique session which lives thru lifetime of uber-session
// - do not delete old sessions, but use them as hijack pointers
// - all sids used later than ~5secs of regenerations is hijacks

abstract class Session extends ArrayObject // for concretization extend by some SessionStoreInterface'd class
{
	private $active = false;
	private $send_cookie = false;
	private $regenerated = false;
	private $name = '';				// NB [0-9a-zA-Z]+ -- should be short and descriptive (i.e. for users with enabled cookie warnings)
	private $id = null;				// NB [0-9a-zA-Z,-]+

	public $cf_ip;					// set by http class... STS must decide on bad coupling between session & http class
	public $cf_tls;					// if !isset - must not act on this values (i.e. from freecap)

	public $cf_nonce_lifetime = 7200;
	public $cf_prevent_replay = 0;	// STS XXX some bug to be fixed
	public $cf_gc_probability = 2;
	public $cf_gc_maxlifetime = 1440;
	public $cf_max_idle = 1440;
	public $cf_max_session = 7200;		// time to unconditionally destroy active session
	public $cf_regen_time = 500;	// seconds between forced id regen
	public $cf_regen_probability = 2;		// percentile probability of forced id regen
	public $cf_cookie_lifetime = 0;	// lifetime of the cookie in seconds which is sent to the browser. The value 0 means "until the browser is closed."
	public $cf_cookie_path = '/';		// path to set in the session cookie
	public $cf_cookie_domain = '';		// domain to set in the session cookie. '' for host name of the server which generated the cookie, according to cookies specification
									// .php.net - to make cookies visible on all subdomains
	public $cf_cookie_secure = false;	// cookie should only be sent over secure connections.
	public $cf_cookie_httponly = true;// Marks the cookie as accessible only through the HTTP protocol. This means that the cookie won't be accessible by js and such
	public $cf_referer_check = '';
	public $cf_cache_limiter = 'none';
	public $cf_cache_expire = 180*60;	// ttl for cached session pages in seconds
	public $cf_cache_mtime = 0;		// should be set before start() for cache limiters


	public function __construct()
	{
		register_shutdown_function([$this, 'terminator'], getcwd());
		parent::__construct([], parent::ARRAY_AS_PROPS);
	}

	public function toArray()
	{
		return $this->getArrayCopy();
	}

	// effectively destroy(true) + start()
	// or... regenerate + filter!
	public function restart()
	{
		if (!$this->regenerate_id(true, 'restart'))
		{
			return false;
		}

		$this->clean_vars();

		return true;
	}

	// replace the current session id with a newly generated
	// one, create and lock new file, and keep the current session information
	public function regenerate_id($delete_old = false, $message = '')
	{
		if (headers_sent())
		{
			//ERROR "Cannot regenerate session id - headers already sent"
		}
		else if ($this->active && !isset($this->__reg_expire))
		{
			if ($this->regenerated)
			{
				// single regeneration in one session would be enough
				return true;
			}

			$now = time();

			$this->__regenerations[] = [$now, $message];		// XXX trim

			if ($delete_old)
			{
				$this->store_destroy();
			}
			else
			{
				// let old page live for some seconds to gather missing requests (ajax etc)
				$this->__reg_expire = $now;
				$this->write_session();
				unset($this->__reg_expire);
			}

			// and pray so it will stop.. ;)
			do
			{
				$this->set_new_id();
			}
			while ($this->store_read($this->id) !== false);

			// create & lock new jar
			if ($this->store_read($this->id, true) !== '')
			{
				// error!
			}

			$this->__regenerated = $now;
			$this->regenerated = true;

			return true;
		}

		return false;
	}

	public function start($name = null, $id = null)
	{
		if ($this->active)
		{
			return true;
		}

		// allow to reuse original session name on session restarts
		if ($name || !$this->name)
		{
			// filter name
			$name = preg_replace('/[^0-9a-zA-Z_\-]+/', '', $name);
			if (!$name || ctype_digit($name))
			{
				$name = 'DefaultSessionId';
			}
			$this->name = $name;
		}

		$this->send_cookie = 1;

		if (!$id && ($id = @$_COOKIE[$this->name]))
		{
			$this->send_cookie = 0;
		}

		if ($id && $this->cf_referer_check
			&& strstr($_SERVER['HTTP_REFERER'], $this->cf_referer_check) === false)
		{
			$id = null;
		}

		if ($id && !$this->store_validate_id($id))
		{
			$id = null;
		}

		$this->id = $id;
		$this->regenerated = false;
		$this->store_open($this->name);
		$this->initialize();
		$this->cache_limiter(); // TODO - why it is in the session?

		$now = time();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'Trident') !== false)
		{
			// microsoft changing ua string anytime
			$user_agent = 'IE';
		}

		if (isset($this->__started))
		{
			$message = '';
			if (isset($this->__reg_expire) && $now - $this->__reg_expire > 8)
			{
				unset($this->__reg_expire);
				$message = 'reg_expire';
				$destroy = 2;
			}
			else if ($now - $this->__started > $this->cf_max_session)
			{
				unset($this->__started);
				$message = 'max_session';
				$destroy = 2;
			}
			else if ($now - $this->__updated > $this->cf_max_idle)
			{
				$message = 'max_idle';
				$destroy = 2;
			}
			else if ($this->cf_prevent_replay && !$this->verify_nonce('NoReplay', @$_COOKIE[$this->name . 'NoReplay']))
			{
				$message = 'replay';
				$destroy = 2;
			}
			else if (!similar_text($this->__user_agent, $user_agent, $perc) || $perc < 95)
			{
				$message = 'ua';
				$destroy = 2;
			}
			else if (isset($this->cf_tls) && isset($this->__user_tls) && $this->cf_tls != $this->__user_tls)
			{
				$message = 'tls';
				$destroy = 2;
			}
			else if (isset($this->cf_ip) && isset($this->__user_ip) && $this->cf_ip != $this->__user_ip)
			{
				$message = 'ip';
				$destroy = 1;
				$this->__ip_list[$this->__user_ip] = 1 + @$this->__ip_list[$this->__user_ip];
			}
			else if ($now - $this->__regenerated > $this->cf_regen_time || Ut::rand(0, 99) < $this->cf_regen_probability)
			{
				$destroy = 0;
			}

			if (isset($destroy))
			{
				Diag::dbg($destroy, $message);
				$this->regenerate_id($destroy, $message);

				if ($destroy == 2)
				{
					$this->clean_vars();
				}
			}
		}

		if (!isset($this->__created))	$this->__created			= $now;
		if (!isset($this->__started))	$this->__started			= $now;
		if (!isset($this->__regenerated)) $this->__regenerated		= $now;

		$this->__user_agent				= $user_agent;

		if (isset($this->cf_tls))		$this->__user_tls			= $this->cf_tls;
		if (isset($this->cf_ip))		$this->__user_ip			= $this->cf_ip;

		return $this->active;
	}

	public function active()
	{
		return $this->active;
	}

	public function id()
	{
		return $this->id;
	}

	public function name()
	{
		return $this->name;
	}

	// write session data, end session
	public function write_close()
	{
		if ($this->active)
		{
			$this->write_session();
			// check error!
			$this->store_close();
			$this->active = false;
		}
	}

	// our little flash data toolkit
	// set variable which can be available in this and NEXT session(s)
	// and then automagically purged
	public function set_flash($name, $value, $lifetime = 2)
	{
		$this[$name] = $value;
		$this->__flashdata[$name] = $lifetime;
	}

	// shutdown-registered worker
	public function terminator($cwd)
	{
		// expire flashdata
		if (isset($this->__flashdata))
		{
			foreach ($this->__flashdata as $var => $age)
			{
				if (--$age <= 0 || !isset($this[$var]))
				{
					unset($this[$var]);
					unset($this->__flashdata[$var]);
				}
				else
				{
					$this->__flashdata[$var] = $age;
				}
			}
		}

		// shutdown run with cwd == /
		chdir($cwd);

		$this->write_close();

		if (Ut::rand(0, 99) < $this->cf_gc_probability)
		{
			$this->store_gc();
			// "purged $returned expired session objects"
		}
	}

	private static function nonce_index($action, $code)
	{
		return (string)$action . '.' . substr(base64_encode(hash('sha1', (string)$code, 1)), 1, 11);
	}

	public function create_nonce($action, $expires = null)
	{
		$code = Ut::random_token(11);
		$this->nonces_db[static::nonce_index($action, $code)] = time() + ($expires ?: $this->cf_nonce_lifetime);
		return $code;
	}

	public function verify_nonce($action, $code)
	{
		if (!($nonces = @$this->nonces_db))
		{
			return false;
		}

		$now = time();
		foreach ($nonces as $index => $expires)
		{
			if ($expires < $now)
			{
				unset($nonces[$index]);
			}
		}

		$index = static::nonce_index($action, $code);

		if (($ret = isset($nonces[$index])))
		{
			unset($nonces[$index]);
		}

		$this->nonces_db = $nonces;

		return $ret;
	}

	// those two is for possible override in store methods
	protected function store_generate_id()
	{
		return Ut::random_token(21);
	}

	protected function store_validate_id($id)
	{
		return preg_match('/^[0-9a-zA-Z]{21}$/', $id);
	}

	private function write_session()
	{
		if ($this->cf_prevent_replay)
		{
			$this->_send_cookie($this->name . 'NoReplay', 
				$id = $this->create_nonce('NoReplay', $this->cf_max_session));
		}
		$this->__updated = time();
		$this->store_write($this->id, serialize($this->toArray()));
	}

	// clean vars on quasi-hard reset, leave __ and sticky_ vars in place
	private function clean_vars()
	{
		foreach ($this->toArray() as $var => $val) // do not optimize toArray - php likes it this way
		{
			if (strncmp($var, 'sticky_', 7) && strncmp($var, '__', 2))
			{
				unset($this[$var]);
			}
		}
	}

	private function set_new_id()
	{
		$this->id = $this->store_generate_id();
		$this->send_cookie();
	}

	private function reset_id()
	{
		if ($this->send_cookie && $this->id)
		{
			$this->send_cookie();
			$this->send_cookie = 0;
		}
	}

	private function initialize()
	{
		$text = $this->store_read($this->id);

		if ($text === false)
		{
			// here we generate new session id for utterly new, or stale/evil id offered by client
			// (or file error, per se)
			$this->set_new_id();

			// create & lock new jar
			if ($this->store_read($this->id, true) !== '')
			{
				// error!
			}
		}
		else if (!$this->active)
		{
			$this->reset_id(); // STS lone use
		}

		$this->active = true;

		if (!$text || !($data = unserialize($text)))
		{
			$data = [];
		}
		$this->exchangeArray($data);
	}

	private function cache_limiter()
	{
		if (!headers_sent())
		{
			$age = $this->cf_cache_expire;

			switch ($this->cf_cache_limiter)
			{
				case 'public':
					header('Expires: ' . Ut::http_date(time() + $age));
					header("Cache-Control: public, max-age=$age");
					break;

				case 'private':
					header('Expires: ' . Ut::http_date(-1)); // looong ago
					// FALLTHRU

				case 'private_no_expire':
					header("Cache-Control: private, max-age=$age, pre-check=$age");
					break;

				case 'nocache':
					header('Expires: ' . Ut::http_date(-1));
					header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
					header('Pragma: no-cache');
					return; // suppress last-modified

				default:
				case 'none':
					return;
			}

			if ($this->cf_cache_mtime > 0)
			{
				header('Last-Modified: ' . Ut::http_date($this->cf_cache_mtime));
			}
		}
	}

	private function remove_cookie($cookie)
	{
		$set = 'Set-Cookie';
		$clen = strlen($cookie);
		$found = 0;
		$readd = [];
		foreach (headers_list() as $name => $value)
		{
			if (!strcasecmp($name, $set))
			{
				if (!strncmp($value, $cookie, $clen) && substr($value, $clen, 1) == '=')
				{
					++$found;
				}
				else
				{
					$readd[] = $value;
				}
			}
		}
		if ($found)
		{
			header_remove($set);
			foreach ($readd as $value)
			{
				header($set . ': ' . $value);
			}
		}
	}

	protected function send_cookie($remove = false)
	{
		$this->_send_cookie($this->name, ($remove? '' : $this->id));
	}

	protected function _send_cookie($name, $value)
	{
		$name = rawurlencode($name);
		$this->remove_cookie($name);

		$this->setcookie($name, $value,
			($this->cf_cookie_lifetime > 0? time() + $this->cf_cookie_lifetime : 0),
			$this->cf_cookie_path, $this->cf_cookie_domain, $this->cf_cookie_secure, $this->cf_cookie_httponly);
	}

	public function setcookie($name, $value = '', $expires = 0, $path = '', $domain = '', $secure = false, $httponly = false, $url_encode = true)
	{
		if (headers_sent($filename, $linenum))
		{
			// ERROR "Headers already sent in $filename on line $linenum\n";
			return;
		}

		if (Ut::is_empty($value))
		{
			$expires = 1;
			$value = 'deleted';
		}

		if ($url_encode)
		{
			$value = rawurlencode($value);
		}

		if (preg_match('/[=,;\s]/', $name) || preg_match('/[,;\s]/', $value))
		{
			return false;
		}

		$cookie = 'Set-Cookie: '. $name . '=' . $value;

		if ($expires > 0)
		{
			$cookie .= '; expires=' . Ut::http_date($expires);

			if (($expires -= time()) < 0)
			{
				$expires = 0;
			}
			$cookie .= '; Max-Age=' . $expires;
		}

		if ($path)
		{
			$cookie .= '; path=' . $path;
		}
		if ($domain)
		{
			$cookie .= '; domain=' . $domain;
		}
		if ($secure)
		{
			$cookie .= '; secure';
		}
		if ($httponly)
		{
			$cookie .= '; httponly';
		}

		header($cookie, false); // false -- add, not replace

		return true;
	}
}
