<?php


namespace Src\DataMining\NeuralNetwork;

/**
 * Class ActivationFunctions
 * @package Src\DataMining\NeuralNetwork
 */
class ActivationFunctions
{
    public static function sigmoid($x)
    {
	    return 1 / (1 + exp(-$x));
    }
}
