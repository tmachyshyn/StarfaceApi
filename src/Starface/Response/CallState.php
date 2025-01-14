<?php

namespace Starface\Response;

class CallState
{
    private array $data = [];

    public function __construct(array $data = null)
    {
        if (empty($data)) {
            return;
        }

        $this->data = $data;
    }

    public function getCallerNumber(): ?string
    {
        return $this->data['callerNumber'] ?? null;
    }

    public function setCallerNumber(string $callerNumber): self
    {
        $this->data['calledNumber'] = $callerNumber;

        return $this;
    }

    public function getCalledNumber(): ?string
    {
        return $this->data['calledNumber'] ?? null;
    }

    public function setCalledNumber(string $calledNumber): self
    {
        $this->data['calledNumber'] = $calledNumber;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        $timestamp = $this->data['timestamp'] ?? null;

        if (empty($timestamp)) {
            return null;
        }

        return $timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->data['timestamp'] = $timestamp;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->data['id'] ?? null;
    }

    public function setId(string $id): self
    {
        $this->data['id'] = $id;

        return $this;
    }

    public function getGroupID(): ?string
    {
        $groupId = $this->data['groupID'] ?? null;

        if (empty($groupId)) {
            return null;
        }

        return $groupId;
    }

    public function setGroupID($groupId): self
    {
        $this->data['groupID'] = $groupId;

        return $this;
    }

    public function getCalledName(): ?string
    {
        return $this->data['calledName'] ?? null;
    }

    public function setCalledName(string $calledName): self
    {
        $this->data['calledName'] = $calledName;

        return $this;
    }

    public function getState(): ?string
    {
        $state = $this->data['state'] ?? null;

        if (empty($state)) {
            return null;
        }

        return strtoupper($state);
    }

    public function setState(string $state): self
    {
        $this->data['state'] = $state;

        return $this;
    }

    public function getCallerName(): ?string
    {
        return $this->data['callerName'] ?? null;
    }

    public function setCallerName(string $callerName): self
    {
        $this->data['callerName'] = $callerName;

        return $this;
    }
}
