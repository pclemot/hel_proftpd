<?php

namespace OCA\Hel_Proftpd;

class Hooks {

	public static function createUser($params) {
		$connection = \OC::$server->getDatabaseConnection();
		self::createProftpdUser($connection, $params['uid'], $params['password']);
	}

	public static function deleteUser($params) {
		$connection = \OC::$server->getDatabaseConnection();
		self::deleteProftpdUser($connection, $params['uid']);
	}

	public static function updateUser($params) {
		$connection = \OC::$server->getDatabaseConnection();
		self::updateProftpdUser($connection, $params['uid'], $params['password']);
	}

	static protected function createProftpdUser($connection, $user, $password) {
		$encrypted_password = "{md5}".base64_encode(pack("H*", md5($password)));
		$ftp_dir = "/var/www/clouddatadir/" . $user . "/files";

		$queryBuilder = $connection->getQueryBuilder();
		$queryBuilder->insert('hel_proftpd')
			->values([
				'user' => $queryBuilder->createParameter('user'),
				'password' => $queryBuilder->createParameter('password'),
				'uid' => $queryBuilder->createParameter('uid'),
				'gid' => $queryBuilder->createParameter('gid'),
				'homedir' => $queryBuilder->createParameter('homedir')
			])
			->setParameters([
				'user' => $user,
				'password' => $encrypted_password,
				'uid' => 33,
				'gid' => 33,
				'homedir' => $ftp_dir
			])
			->execute();

		if (!is_dir($ftp_dir)) mkdir($ftp_dir, 0755, true);

		return true;
	}

	static protected function deleteProftpdUser($connection, $user) {
		$queryBuilder = $connection->getQueryBuilder();

		$queryBuilder->delete('hel_proftpd')
			->where($queryBuilder->expr()->eq('user', $queryBuilder->createParameter('user')))
			->setParameter('user', $user)
			->execute();

	}

	static protected function updateProftpdUser($connection, $user, $password) {
		$encrypted_password = "{md5}".base64_encode(pack("H*", md5($password)));

		$queryBuilder = $connection->getQueryBuilder();

		$queryBuilder->update('hel_proftpd')
			->set('password', $queryBuilder->createNamedParameter($encrypted_password))
			->where($queryBuilder->expr()->eq('user', $queryBuilder->createParameter('user')))
			->setParameter('user', $user)
			->execute();
	}
}
