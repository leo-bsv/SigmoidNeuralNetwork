<?php


namespace Src\DataMining\NeuralNetwork;

/**
 * Class Synapse
 * @package Src\DataMining\NeuralNetwork
 */
class Synapse
{
    public $weight;
    public $link;

    /**
     * Создание синапса связанного с конкретным нейроном и имеющего
     * рандомный вес
     */
    static function linkTo(Neuron &$neuron)
    {
        $d = new self();
        $d->link = $neuron;
        $d->weight = (0.3 + lcg_value() * (abs(0.7 - 0.3)));
        return $d;
    }

    /**
     * Получение сигнала синапса
     */
    public function __invoke()
    {
        return $this->link->axon() * $this->weight;
    }
}
