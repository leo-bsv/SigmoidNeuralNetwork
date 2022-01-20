<?php


namespace Src\DataMining\NeuralNetwork;

/**
 * Class Neuron
 * @package Src\DataMining\NeuralNetwork
 */
class Neuron
{
    public $learning_rate = null;

    // входящие данные для входящего слоя
    public $input;

    // флаг изменения весов
    public $weightsChanged = false;

    // кешированное значение выхода нейрона
    public $out;

    // синапсы - связи с нейронами предыдущего слоя
    public $synapses = [];

    /**
     * Создание нейрона с набором синапсов
     */
    public function __construct($synapses = [], $learning_rate = 0.4)
    {
        $this->synapses = $synapses;
        $this->learning_rate = $learning_rate;
    }

    /**
     * Сериализация только синапсов
     */
    public function __sleep()
    {
        return ['synapses'];
    }

    /**
     * Функция актвации или пороговая функция - сигмоид
     */
    public function axon()
    {
        if (!isset($this->out)) {
            if (empty($this->synapses)) {
                $this->out = ActivationFunctions::sigmoid($this->input);
            } else {
                foreach ($this->synapses as &$synapse){
                    $this->out += $synapse();
                }
                $this->out = ActivationFunctions::sigmoid($this->out);
            }
        }
        return $this->out;
    }

    /**
     * Функция корректировки весов
     * @param double $error
     */
    public function changeWeights($error)
    {
        if (empty($this->synapses) || $this->weightsChanged) return;
        $this->weightsChanged = true;
        $wDelta = $error * $this->out * (1 - $this->out);
        foreach ($this->synapses as &$synapse) {
            $synapse->weight = $synapse->weight - $synapse->link->out * $wDelta * $this->learning_rate;
            $nextError = $synapse->weight * $wDelta;
            $synapse->link->changeWeights($nextError);
        }
    }
}
