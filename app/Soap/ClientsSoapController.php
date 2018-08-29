<?php

namespace App\Soap;

use App\Client;
use App\Types\ClientType;
use Illuminate\Contracts\Support\Arrayable;
use Zend\Config\Config;
use Zend\Config\Writer\Xml;

// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Http\Request;

class ClientsSoapController
{
    /**
     * @return string
     */
    public function listAll()
    {
        return $this->getXML(Client::all());
    }

    /**
     * @param \App\Types\ClientType $type
     * @return string
     */
    public function create($type)
    {
        $data = [
          'name' => $type->name,
          'email' => $type->email,
          'phone' => $type->phone
        ];

        $client = Client::create($data);

        return $this->getXML($client);
    }

    protected function getXML($data)
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $config = new Config(['result' => $data], true);

        $xmlWriter = new Xml();

        return $xmlWriter->toString($config);
    }
}
