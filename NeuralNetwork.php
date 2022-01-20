<?php


namespace Src\DataMining\NeuralNetwork;

/**
 * Class NeuralNetwork
 * @package Src\DataMining\NeuralNetwork
 */
class NeuralNetwork
{
    public $topology = [];

    // построение структуры нейросети
    public function build(...$topology)
    {
        foreach ($topology as $layer => $neuronsCount) {
            // если не входной слой сети - подключим связи к предыдущему
            $synapses = function() use ($layer){
                $result = [];
                if ($layer) {
                    foreach ($this->topology[$layer-1] as &$neuron){
                        $result[] = Synapse::linkTo($neuron);
                    }
                }
                return $result;
            };
            $this->topology[$layer] = [];
            for ($i=0; $i<$neuronsCount; $i++) {
                $this->topology[$layer][] = new Neuron($synapses());
            }
        }
    }

    public function export()
    {
        return serialize($this->topology);
    }

    public function import($packed)
    {
        $this->topology = unserialize($packed);
    }

    public function reset()
    {
        foreach ($this->topology as &$neurons) {
            foreach ($neurons as &$neuron) {
                $neuron->out = null;
                $neuron->weightsChanged = false;
            }
        }
    }

    /**
     * Функция предсказания
     * $in - входящие данные в виде массива размерностью эквивалентного первому слою
     * возвращаемое значение - массив содержащий предсказанные значения
     */
    public function predict($in)
    {
        $this->reset();
        foreach ($this->topology[0] as $i => &$neuron) {
            $neuron->input = $in[$i];
        }
        end($this->topology);
        $lastLayer = key($this->topology);
        $out = [];
        foreach ($this->topology[$lastLayer] as &$neuron) {
            $out[] = $neuron->axon();
        }
        return $out;
    }

    /**
     * Функция тренировки нейросети
     * $in - входящие данные в виде массива размерностью эквивалентного первому слою
     * $expected - ожидаемые результаты в виде массива размерностью эквивалентные последнему слою
     * возвращаемое значение - среднеквадратическая ошибка MSE
     */
    public function train($in, $expected)
    {
        $this->predict($in);
        end($this->topology);
        $lastLayer = key($this->topology);
        $errSum = 0;
        foreach ($this->topology[$lastLayer] as $i => &$neuron) {
            $error = $neuron->out - $expected[$i];
            $neuron->changeWeights($error);
            $errSum += $error;
        }
        return $errSum**2; // среднеквадратическая ошибка (MSE)
    }
}
