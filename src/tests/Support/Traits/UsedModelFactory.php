<?php


namespace Tests\Support\Traits;


use Illuminate\Database\Eloquent\Factory as EloquentFactory;

trait UsedModelFactory
{
    /**
     * @var EloquentFactory
     */
    private $modelFactory;

    private function initModelFactory()
    {
        $this->modelFactory = app(EloquentFactory::class);
    }

    /**
     * @param string $class
     * @param int|null $times
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    private function factory(string $class, ?int $times = null)
    {
        if (! $this->modelFactory) {
            $this->initModelFactory();
        }

        return $this->modelFactory->of($class)->times($times);
    }
}