<?php


namespace App\Utils\FrameworkAdapters\Config;


use App\Utils\FrameworkAdapters\Config\Contracts\ConfigRepository as ConfigRepositoryInterface;
use Illuminate\Contracts\Config\Repository;

/**
 * Class ConfigRepository
 * @package App\Utils\FrameworkAdapters\Config
 */
class LaravelConfigRepositoryAdapter implements ConfigRepositoryInterface
{
    /**
     * @var Repository
     */
    private $configRepository;

    /**
     * ConfigRepository constructor.
     * @param Repository $configRepository
     */
    public function __construct(Repository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->configRepository->has($key);
    }

    /**
     * @param array|string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->configRepository->get($key, $default);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->configRepository->all();
    }

    /**
     * @param array|string $key
     * @param null $value
     */
    public function set($key, $value = null): void
    {
        $this->configRepository->set($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function prepend($key, $value): void
    {
        $this->configRepository->get($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function push($key, $value): void
    {
        $this->configRepository->push($key, $value);
    }

}
