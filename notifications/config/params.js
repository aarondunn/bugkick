module.exports = {
	DEBUG: true,
	IO_PORT: 27000,
	SSL: {
		/*			Server:			*/
		//key: '/etc/pki/tls/private/bugkick.com.key',
		//cert: '/etc/pki/tls/certs/bugkick.com.crt'
		/*			Local:			*/
		key: __dirname+'/../sslcert/f0t0n-key.pem',
		cert: __dirname+'/../sslcert/f0t0n-cert.pem'
	}
};