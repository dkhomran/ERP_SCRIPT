<?php

namespace App\Http\Controllers;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
class HomeController extends Controller
{
    public function index() {
        return view('home');
    }

    public function share()
    {
        // Set up WebDriver
        $host = 'http://localhost:9515/wd/hub'; // URL to the WebDriver server
        $capabilities = [
            \Facebook\WebDriver\Remote\WebDriverCapabilityType::BROWSER_NAME => 'chrome',
        ];

        $driver = RemoteWebDriver::create($host, $capabilities);

        try {
            // Navigate to the Microsoft login page
            $driver->get('https://login.microsoftonline.com/');

            // Enter username and click "Next"
            $usernameField = $driver->findElement(WebDriverBy::name('loginfmt'));
            $usernameField->sendKeys('Elastic-ERP@smartskills.tn');
            $nextButton = $driver->findElement(WebDriverBy::id('idSIButton9'));
            $nextButton->click();

            // Wait for the password page to load
            $driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::name('passwd'))
            );

            // Enter password and submit
            $passwordField = $driver->findElement(WebDriverBy::name('passwd'));
            $passwordField->sendKeys('Joq51181');
            $submitButton = $driver->findElement(WebDriverBy::id('idSIButton9'));
            $submitButton->click();

            // Check for successful login (you need to customize this check)
            // Here, you can add logic to verify if the login was successful based on the next page's elements.
            // For example, you can wait for a specific element to appear after login.
            $driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('your_success_element_id'))
            );

            // Perform any further actions after successful login.

            $this->info('Login successful!');
        } catch (\Exception $e) {
            $this->error('Login failed: ' . $e->getMessage());
        } finally {
            // Close the browser, regardless of success or failure
            $driver->quit();
        }
        return redirect()->route('Home');
    }
}

