<?php
namespace Chat\Connection;

use Chat\Repository\ChatRepositoryInterface;
use Ratchet\ConnectionInterface;

class ChatConnection implements ChatConnectionInterface{
	private $connection;
	private $name;
	private $repository;

	public function __construct(ChatConnectionInterface $conn, ChatRepositoryInterface $repository, $name=""){
		$this->connection=$conn;
		$this->name=$name;
		$this->repository=$repository;
	}

	public function sendMsg(){
		$this->send([
			'action'=>'message',
			'username'=>$sender,
			'msg'=>$msg
		]);
	}

	public function getConnection(){
		return $this->connection;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		if($name===""){
			return;
		}
		if($this->$repository->getClientByName($name) !== null){
			$this->send([
				'action'=>'setname',
				'success'=>false,
				'username'=>$this->name
			]);
			return;
		}

		$this->name=$name;
		$this->send([
			'action'=>'setname',
			'success'=>true,
			'username'=>$this->name
		]);
	}

	private function send(array $data){
		$this->connection->send(json_encode($data));
	}
}
