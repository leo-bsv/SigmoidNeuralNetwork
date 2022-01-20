<?php


namespace Src\DataMining\NeuralNetwork;

/**
 * Class NeuralNetworkService
 * @package Src\DataMining\NeuralNetwork
 */
class NeuralNetworkService
{
    /**
     * Количество эпох обучения
     */
    public $epochs;

    /**
     * @var NeuralNetwork null
     */
    public $neuralNetwork = null;

    /**
     * NeuralNetworkService constructor.
     * @param int $epochs
     */
    public function __construct($epochs = 5000)
    {
        $this->neuralNetwork = new NeuralNetwork();
        $this->epochs = $epochs;
    }

    /**
     * Тренировка нейросети
     * @param array $data
     */
    public function trainNeuralNetwork(array $data)
    {
        echo str_pad('', 80, ' ');

        for ($i = 1; $i <= $this->epochs; $i++) {
            $error = 0;

            foreach ($data as $arr) {
                list($in, $expected) = $arr;
                $error += $this->neuralNetwork->train($in, $expected);
            }

            $error /= count($data);
            echo "\033[80D";
            $str = 'Эпоха ' . $i . ' ошибка ' . $error;
            echo str_pad($str, 80, ' ');
            // sleep(1);
        }
        echo "\n";
    }

    /**
     * Тестирование нейросети
     * @param array $data
     */
    public function testNeuralNetwork(array $data)
    {
        foreach ($data as $date => $arr) {
            list($in, $expected) = $arr;
            // $result = round($nn->predict($in)[0]);
            $result = $this->neuralNetwork->predict($in)[0];
            echo implode(' - ',$in) . ' = ' .$result . '; ожидалось: ' . $date . ' -> ' . $expected[0] . PHP_EOL;
        }
    }
}
