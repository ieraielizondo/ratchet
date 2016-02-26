<?php
namespace Chat;
use Chat\Repository\ChatRepository;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
	protected $repository;

	public function __construct() {
		$this->repository = new ChatRepository;
	}

	public function onOpen(ConnectionInterface $conn) {
		// Store the new connection to send messages to later
		$this->repository->addClient($conn);

		echo "New connection! ({$conn->resourceId})\n";
	}

	public function onMessage(ConnectionInterface $conn, $msg) {
		$data=$this->parseMessage($msg);
		$currClient=$this->repository->getClientByConnection($conn);
		if($data->action === 'setname'){
			$currClient->setName($data->username);
		}else if($data->action === 'message'){
			if($currClient->getName() === ""){
				return;
			}
			foreach ($this->repository->getClients() as $lient) {
				$client->sendMsg($currClient->getname(),$data->msg);
			}
		}
	}

	private function parseMessage($msg){
		return json_decode($msg);
	}

	public function onClose(ConnectionInterface $conn) {
		// The connection is closed, remove it, as we can no longer send it messages
		$this->cepository->removeClient($conn);

		echo "Connection {$conn->resourceId} has disconnected\n";
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "An error has occurred: ". $e->getMessage();

		$client= $this->repository->getClienByConnection($conn);
		if($client !== null){
			$client->getConnection()->close();
		}
	}
}