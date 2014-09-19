<?php

namespace Ifensl\Bundle\PadManagerBundle\HttpClient;

use Da\ApiClientBundle\Http\Rest\RestApiClientBridge;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Exception\PadApiException;

class EtherpadApiClient extends RestApiClientBridge
{
    /**
     * Create a new EtherPad
     *
     * @param Pad $pad
     * @throws PadApiException
     * @throws ApiHttpResponseException
     */
    public function createNewPad(Pad & $pad)
    {
        if (null === $pad->getPadOwner()->getAuthorId()) {
            $data = $this->post('/1/createAuthorIfNotExistsFor', array(
                'apikey'       => $this->implementor->getSecurityToken(),
                'name'         => $pad->getPadOwner()->getEmail(),
                'authorMapper' => $pad->getPadOwner()->getEmail()
            ));
            $apiData = json_decode($data->getContent(), true);
            if ($apiData['code'] != 0) {
                throw new PadApiException($apiData);
            }
            $pad->getPadOwner()->setAuthorId($apiData['data']['authorID']);
        }

        if (null === $pad->getPadOwner()->getGroupId()) {
            $data = $this->post('/1/createGroupIfNotExistsFor', array(
                'apikey'      => $this->implementor->getSecurityToken(),
                'groupMapper' => $pad->getPadOwner()->getEmail()
            ));
            $apiData = json_decode($data->getContent(), true);
            if ($apiData['code'] != 0) {
                throw new PadApiException($apiData);
            }
            $pad->getPadOwner()->setGroupId($apiData['data']['groupID']);
        }

        $data = $this->post('/1/createGroupPad', array(
            'apikey'  => $this->implementor->getSecurityToken(),
            'groupID' => $pad->getPadOwner()->getGroupId(),
            'padName' => $pad->getPrivateToken(),
            'text'    => $pad->__toString()
        ));
        $apiData = json_decode($data->getContent(), true);
        if ($apiData['code'] != 0) {
            throw new PadApiException($apiData);
        }
    }

    /**
     * Create a pad owner session
     *
     * @param Pad $pad
     * @throws PadApiException
     * @throws ApiHttpResponseException
     * @return string
     */
    public function createPadOwnerSession(Pad $pad)
    {
        $date = new \DateTime('now');
        $date->modify('+1 day');

        $data = $this->get('/1/createSession', array(
            'apikey'     => $this->implementor->getSecurityToken(),
            'groupID'    => $pad->getPadOwner()->getGroupId(),
            'authorID'   => $pad->getPadOwner()->getAuthorId(),
            'validUntil' => $date->getTimestamp()
        ));
        $apiData = json_decode($data->getContent(), true);
        if ($apiData['code'] != 0) {
            throw new PadApiException($apiData);
        }

        return $apiData['data']['sessionID'];
    }
}
