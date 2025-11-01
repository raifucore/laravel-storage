<?php

namespace RaifuCore\Storage\Dto;

class ObjectDto
{
    protected string|null $storage = null;
    protected string|null $path = null;
    protected string|null $filename = null;
    protected string|null $directory = null;
    protected bool|null $exists = null;
    protected int|null $size = null;

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function getExists(): ?bool
    {
        return $this->exists;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setStorage(string $storage): self
    {
        $this->storage = $storage;
        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;
        return $this;
    }

    public function setExists(bool $exists): self
    {
        $this->exists = $exists;
        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }
}
