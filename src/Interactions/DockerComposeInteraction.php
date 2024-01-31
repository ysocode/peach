<?php

namespace YSOCode\Peach\Interactions;

use YSOCode\Peach\Basket;
use Symfony\Component\Yaml\Yaml;

class DockerComposeInteraction
{
    /**
     * The basket instance.
     *
     * @var Basket
     */
    protected Basket $basket;

    /**
     * The available PHP servers.
     *
     * @var array<int, string>
     */
    protected array $availablePHPServers = [
        'apache',
        'frankenphp'
    ];

    /**
     * The PHP server.
     *
     * @var string
     */
    protected string $phpServer = 'apache';

    /**
     * The available services that may be installed.
     *
     * @var array<int, string>
     */
    protected array $availableServices = [
        'mysql',
        'pgsql',
        'mariadb',
        'redis',
        'memcached',
        'meilisearch',
        'minio',
        'mailpit',
        'selenium',
        'soketi',
        'typesense',
        'phpmyadmin',
    ];

    /**
     * The default services.
     *
     * @var array<int, string>
     */
    protected array $defaultServices = ['mysql', 'phpmyadmin'];

    /**
     * Create a new DockerComposeInteraction instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    /**
     * Get the available PHP servers.
     *
     * @return array<int, string>
     */
    public function getAvailablePHPServers(): array
    {
        return $this->availablePHPServers;
    }

    /**
     * Set the PHP server.
     *
     * @return bool
     */
    public function setPHPServer(string $phpServer): bool
    {
        if (! in_array($phpServer, $this->getAvailablePHPServers())) {

            $this->basket->getOutput()
                ->writeOutputError(
                    "The PHP server '{$phpServer}' is not available. Available PHP servers: [" . implode(', ', $this->getAvailablePHPServers()) . ']'
                );

            return false;
        }

        $this->phpServer = $phpServer;

        return true;
    }

    /**
     * Get the PHP server.
     *
     * @return string
     */
    public function getPHPServer(): string
    {
        return $this->phpServer;
    }

    /**
     * Get the available services.
     *
     * @return array<int, string>
     */
    public function getAvailableServices(): array
    {
        return $this->availableServices;
    }

    /**
     * Get the default services.
     *
     * @return array<int, string>
     */
    public function getDefaultServices(): array
    {
        return $this->defaultServices;
    }

    /**
     * Verify if the service is available.
     *
     * @param string $service
     * @return bool
     */
    public function isAvailableService(string $service): bool
    {
        $availableServices = $this->getAvailableServices();

        if (! in_array($service, $availableServices)) {
            return false;
        }

        return true;
    }

    /**
     * Build the Docker Compose file.
     *
     * @param array<int, string> $services
     * @return void
     */
    public function buildDockerCompose(array $services): void
    {
        foreach ($services as $service) {
            if (! $this->isAvailableService($service)) {
                $this->basket->getOutput()
                    ->writeOutputError(
                        "The service '{$service}' is not available. Available services: [" . implode(', ', $this->getAvailableServices()) . ']'
                    );
            }
        }

        $composePath = $this->basket->getBasePath('/docker-compose.yml');

        $compose = file_exists($composePath)
            ? Yaml::parseFile($composePath)
            : Yaml::parse(file_get_contents(dirname(__FILE__, 3) . '/stubs/' . $this->getPHPServer() . '.stub'));

        if (! isset($compose['services']) || ! is_array($compose['services'])) {
            $compose['services'] = [];
        }

        // Adds the services as dependencies of the ysocode.test service...
        if (! array_key_exists('ysocode.test', $compose['services'])) {
            $this->basket->getOutput()->writeOutputError(
                'Couldn\'t find the ysocode.test service. Make sure you add [' . implode(', ', $services) . '] to the depends_on config.'
            );
        } else {
            $notBeIncludeInDependsOn = ['phpmyadmin'];
            $filteredServicesToBeInclude = array_filter(
                $services,
                function ($service) use ($notBeIncludeInDependsOn) {
                    return ! in_array($service, $notBeIncludeInDependsOn);
                }  
            );

            $compose['services']['ysocode.test']['depends_on'] = array_values(
                array_unique(
                    array_merge($compose['services']['ysocode.test']['depends_on'] ?? [], $filteredServicesToBeInclude)
                )
            );
        }

        // Add the services to the docker-compose.yml...
        foreach ($services as $service) {
            if (! array_key_exists($service, $compose['services'])) {
                $yamlData = Yaml::parseFile(dirname(__FILE__, 3) . "/stubs/{$service}.stub");
                if ($service === 'phpmyadmin') {
                    $phpmyadminDependsOn = array_filter($services, function ($service) {
                        return in_array($service, ['mysql', 'mariadb']);
                    });
                    if ($phpmyadminDependsOn) {
                        $yamlData[$service]['depends_on'] = array_values($phpmyadminDependsOn);
                    }
                }
                $compose['services'][$service] = $yamlData[$service];
            }
        }

        // Merge volumes...
        $servicesToMergeVolume = array_filter($services, function ($service) {
            return in_array($service, ['mysql', 'pgsql', 'mariadb', 'redis', 'meilisearch', 'minio', 'typesense']);
        });
        foreach ($servicesToMergeVolume as $service) {
            if (! array_key_exists($service, $compose['volumes'] ?? [])) {
                $compose['volumes']["peach-{$service}"] = ['driver' => 'local'];
            }
        }

        // If the list of volumes is empty, we can remove it...
        if (empty($compose['volumes'])) {

            unset($compose['volumes']);
        }

        // Replace Selenium with ARM base container on Apple Silicon...
        if (in_array('selenium', $services) && in_array(php_uname('m'), ['arm64', 'aarch64'])) {
            $compose['services']['selenium']['image'] = 'seleniarm/standalone-chromium';
        }

        file_put_contents($this->basket->getBasePath('/docker-compose.yml'), Yaml::dump($compose, Yaml::DUMP_OBJECT_AS_MAP));
    }
}
