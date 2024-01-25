<?php

namespace YSOCode\Peach\Interactions;

use YSOCode\Peach\Basket;

class DockerComposeInteraction
{
    /**
     * The basket instance.
     *
     * @var Basket
     */
    protected Basket $basket;

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
    ];

    /**
     * The default services used when the user chooses non-interactive mode.
     *
     * @var array<int, string>
     */
    protected array $defaultServices = ['mysql', 'redis', 'selenium', 'mailpit'];

    /**
     * Install the given services.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
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
     * Build the Docker Compose file.
     *
     * @param array<int, string> $services
     * @return void
     */
    public function buildDockerCompose(array $services): void
    {
        $composePath = $this->basket->getBasePath('/docker-compose.yml');

        $compose = file_exists($composePath)
            ? yaml_parse_file($composePath)
            : yaml_parse(file_get_contents(dirname(__FILE__, 3) . '/stubs/docker-compose.stub'));

        // Adds the new services as dependencies of the ysocode.test service...
        if (!array_key_exists('ysocode.test', $compose['services'])) {
            $this->basket->getOutput()->writeOutputError(
                'Couldn\'t find the ysocode.test service. Make sure you add [' . implode(',', $services) . '] to the depends_on config.'
            );
        } else {
            $compose['services']['ysocode.test']['depends_on'] = array_values(array_unique(
                array_merge($compose['services']['ysocode.test']['depends_on'] ?? [], $services)
            ));
        }

        // Add the services to the docker-compose.yml...
        foreach ($services as $service) {
            if (!array_key_exists($service, $compose['services'] ?? [])) {
                $yamlData = yaml_parse_file(dirname(__FILE__, 3) . "/stubs/{$service}.stub");
                $compose['services'][$service] = $yamlData[$service];
            }
        }

        // Merge volumes...
        foreach ($services as $service) {
            $allowedServices = ['mysql', 'pgsql', 'mariadb', 'redis', 'meilisearch', 'minio'];
            if (in_array($service, $allowedServices) && !array_key_exists($service, $compose['volumes'] ?? [])) {
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

        $yaml = yaml_emit($compose, YAML_UTF8_ENCODING);

        $lines = preg_split("/\r\n|\n|\r/", $yaml);
        $filteredLines = array_filter($lines, function ($line) {

            $YAMLIndicators = "/^\s*---.*$|^\s*\.\.\..*$/";
            return !preg_match($YAMLIndicators, $line);
        });

        $yamlWithoutIndicators = implode("\n", $filteredLines);

        file_put_contents($this->basket->getBasePath('/docker-compose.yml'), $yamlWithoutIndicators);
    }
}
