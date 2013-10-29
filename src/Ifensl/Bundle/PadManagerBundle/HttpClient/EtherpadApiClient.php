<?php

namespace Ifensl\Bundle\PadManagerBundle\HttpClient;

use Da\ApiClientBundle\HttpClient\RestApiClientBridge;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;

class EtherpadApiClient extends RestApiClientBridge
{
    /**
     * Create a new EtherPad
     *
     * @param Pad $pad
     * @return array
     */
    public function createNewPad(Pad & $pad)
    {
        try {
            if (null === $pad->getPadOwner()->getAuthorId()) {
                $data = $this->get('/1/createAuthorIfNotExistsFor', array(
                    'apikey'       => $this->implementor->getSecurityToken(),
                    'name'         => $pad->getPadOwner()->getEmail(),
                    'authorMapper' => $pad->getPadOwner()->getEmail()
                ));
                $apiData = json_decode($data, true);
                $pad->getPadOwner()->setAuthorId($apiData['data']['authorID']);
            }

            if (null === $pad->getPadOwner()->getGroupId()) {
                $data = $this->get('/1/createGroupIfNotExistsFor', array(
                    'apikey'      => $this->implementor->getSecurityToken(),
                    'groupMapper' => $pad->getPadOwner()->getEmail()
                ));
                $apiData = json_decode($data, true);
                $pad->getPadOwner()->setGroupId($apiData['data']['groupID']);
            }

            $data = $this->get('/1/createGroupPad', array(
                'apikey'  => $this->implementor->getSecurityToken(),
                'groupID' => $pad->getPadOwner()->getGroupId(),
                'padName' => $pad->getPrivateToken(),
                'text'    => $pad->__toString()
            ));
        } catch (\Da\AuthCommonBundle\Exception\ApiHttpResponseException $e) {
            var_dump($e->getMessage()); die;
        }
    }
}