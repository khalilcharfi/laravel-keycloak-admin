<?php

namespace App\Keycloak\Admin\Tests\Traits;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\SupportsChrome;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

trait WithDuskBrowser
{
    use SupportsChrome;

    protected static $browsers = [];
    protected static $afterClassCallbacks = [];

    /**
     * @beforeClass
     */
    public static function setupDuskClass()
    {
        static::startChromeDriver();
    }

    /**
     * @afterClass
     */
    public static function teardownDuskClass()
    {
        Collection::make(static::$browsers)->each(function (Browser $browser) {
            $browser->quit();
        });
        static::$browsers = collect();
        foreach (static::$afterClassCallbacks as $callback) {
            $callback();
        }
    }

    public static function afterClass(\Closure $callback)
    {
        static::$afterClassCallbacks[] = $callback;
    }

    /**
     * @before
     */
    public function setupDuskTest()
    {
        Browser::$baseUrl = $_SERVER['DUSK_BASE_URL'];
        Browser::$storeScreenshotsAt = realpath($_SERVER['DUSK_SCREENSHOT_DIR']);
        $this->clearExistingScreenshots(Browser::$storeScreenshotsAt);
    }

    protected function clearExistingScreenshots($dir)
    {
        $filesystem = new Filesystem();
        if (!is_dir($dir)) {
            $filesystem->mkdir($dir);
        }
        $files = Finder::create()
            ->files()
            ->in($dir)
            ->name($this->getScreenshotPrefix() . '*');
        $filesystem->remove($files);
    }

    protected function getScreenshotPrefix()
    {
        return sprintf(
            'failure-%s-%s',
            class_basename($this),
            $this->getName()
        );
    }

    public function browse(\Closure $callback)
    {
        $browsers = $this->createBrowsersFor($callback);
        try {
            $callback(...$browsers->all());
        } catch (\Exception $e) {
            $this->captureFailuresFor($browsers);
            throw $e;
        } catch (\Throwable $e) {
            $this->captureFailuresFor($browsers);
            throw $e;
        } finally {
            static::$browsers = $this->closeAllButPrimary($browsers);
        }
    }

    protected function createBrowsersFor(\Closure $callback)
    {
        if (count(static::$browsers) === 0) {
            static::$browsers = collect([new Browser($this->createWebDriver())]);
        }
        $additional = $this->browsersNeededFor($callback) - 1;
        for ($i = 0; $i < $additional; ++$i) {
            static::$browsers->push(new Browser($this->createWebDriver()));
        }
        return static::$browsers;
    }

    protected function createWebDriver()
    {
        $options = (new ChromeOptions())->addArguments([
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
            '--window-size=1920,1080',
        ]);
        return RemoteWebDriver::create(
            "http://selenium:4444/wd/hub",
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    protected function browsersNeededFor(\Closure $callback)
    {
        return (new \ReflectionFunction($callback))->getNumberOfParameters();
    }

    protected function captureFailuresFor(Collection $browsers)
    {
        $browsers->each(function (Browser $browser, $key) {
            $browser->screenshot(sprintf(
                '%s-%s',
                $this->getScreenshotPrefix(),
                $key
            ));
        });
    }

    protected function closeAllButPrimary(Collection $browsers)
    {
        $browsers->slice(1)->each(function (Browser $browser) {
            $browser->quit();
        });
        return $browsers->take(1);
    }
}
