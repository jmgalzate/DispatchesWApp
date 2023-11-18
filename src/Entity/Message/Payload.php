<?php 

namespace App\Entity\Contapyme;

class Payload {
    private string $parameters;
    private string $agent;
    private string $iapp;
    private int $random;

    
    public function getParameters(): string {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self {
        $this->parameters = json_encode($parameters);
        return $this;
    }

    public function getAgent(): string {
        return $this->agent;
    }

    public function setAgent(string $agent): self {
        $this->agent = $agent;
        return $this;
    }

    public function getIapp(): string {
        return $this->iapp;
    }

    public function setIapp(): self {
        $this->iapp = $_ENV['API_IAPP'];
        return $this;
    }

    public function getRandom(): int {
        return $this->random;
    }

    /**
     * @throws \Exception
     */
    public function setRandom(): self {
        $this->random = (int)random_int(0, 9);
        return $this;
    }
}